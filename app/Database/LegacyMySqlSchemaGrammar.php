<?php
// Laravel now selects generation_expression from information_schema; older deployed servers lack that column, so select NULL only when unsupported.


namespace App\Database;

use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use PDO;

final class LegacyMySqlSchemaGrammar extends MySqlGrammar
{
    private const GENERATED_COLUMN_EXPRESSION = 'generation_expression as `expression`';

    private const UNSUPPORTED_COLUMN_EXPRESSION = 'NULL as `expression`';

    public function compileColumns(mixed $schema, mixed $table): string
    {
        $columnQuery = parent::compileColumns($schema, $table);
        $serverVersion = (string) $this->connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);

        if (LegacyMySqlServerVersion::supportsGenerationExpression($serverVersion)) {
            return $columnQuery;
        }

        return str_replace(self::GENERATED_COLUMN_EXPRESSION, self::UNSUPPORTED_COLUMN_EXPRESSION, $columnQuery);
    }
}
