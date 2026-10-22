<?php
// Exercise old MySQL/MariaDB metadata boundaries and lazy connection registration so the runtime upgrade needs no schema migration.


namespace Tests\Unit;

use App\Database\LegacyMySqlConnection;
use App\Database\LegacyMySqlServerVersion;
use App\Providers\AppServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Foundation\Application;
use PDO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LegacyMySqlSchemaTest extends TestCase
{
    public static function serverVersions(): array
    {
        return [
            'MariaDB compatibility prefix' => ['5.5.5-10.1.16-MariaDB', false],
            'MariaDB before generated columns' => ['10.1.48-MariaDB', false],
            'MariaDB generated columns boundary' => ['10.2.0-MariaDB', true],
            'modern MariaDB' => ['5.5.5-10.11.8-MariaDB', true],
            'MySQL 5.5' => ['5.5.62', false],
            'MySQL 5.6 suffix' => ['5.6.51-log', false],
            'MySQL generated columns boundary' => ['5.7.0', true],
            'modern MySQL' => ['8.0.43', true],
            'unknown version keeps standard SQL' => ['unknown', true],
        ];
    }

    #[DataProvider('serverVersions')]
    public function test_column_metadata_matches_server_capabilities(string $serverVersion, bool $supportsGeneratedColumns): void
    {
        $this->assertSame(
            $supportsGeneratedColumns,
            LegacyMySqlServerVersion::supportsGenerationExpression($serverVersion),
        );

        $databaseConnection = $this->createMock(PDO::class);
        $databaseConnection->method('getAttribute')->with(PDO::ATTR_SERVER_VERSION)->willReturn($serverVersion);
        $databaseConnection->method('quote')->willReturnCallback(
            static fn (string $value): string => "'".str_replace("'", "''", $value)."'",
        );

        $connection = new LegacyMySqlConnection($databaseConnection, 'test_database');
        $connection->useDefaultSchemaGrammar();
        $standardGrammar = new MySqlGrammar($connection);
        $standardQuery = $standardGrammar->compileColumns('test_database', 'users');
        $expectedQuery = $supportsGeneratedColumns
            ? $standardQuery
            : str_replace('generation_expression as `expression`', 'NULL as `expression`', $standardQuery);

        $this->assertSame($expectedQuery, $connection->getSchemaGrammar()->compileColumns('test_database', 'users'));
    }

    public function test_registering_the_resolver_does_not_connect_to_the_database(): void
    {
        $application = new Application(dirname(__DIR__, 2));
        $provider = new AppServiceProvider($application);
        $provider->register();

        $databaseConnection = $this->createMock(PDO::class);
        $connectionAttempts = 0;
        $connect = static function () use (&$connectionAttempts, $databaseConnection): PDO {
            $connectionAttempts++;

            return $databaseConnection;
        };
        $resolver = Connection::getResolver('mysql');
        $connection = $resolver(
            $connect,
            'test_database',
            '',
            [],
        );
        $connection->useDefaultSchemaGrammar();

        $this->assertSame(0, $connectionAttempts);
        $this->assertInstanceOf(LegacyMySqlConnection::class, $connection);
    }
}
