<?php

namespace App\Database;

final class LegacyMySqlServerVersion
{
    private const MARIADB_GENERATED_COLUMNS_VERSION = '10.2.0';

    private const MYSQL_GENERATED_COLUMNS_VERSION = '5.7.0';

    public static function supportsGenerationExpression(string $serverVersion): bool
    {
        $isMariaDb = stripos($serverVersion, 'MariaDB') !== false;
        $normalizedVersion = $isMariaDb ? preg_replace('/^5\.5\.5-/', '', $serverVersion) : $serverVersion;

        // An unrecognized server keeps Laravel's standard metadata query.
        if (preg_match('/^(\d+\.\d+\.\d+)/', $normalizedVersion, $versionParts) !== 1) {
            return true;
        }

        $minimumVersion = $isMariaDb
            ? self::MARIADB_GENERATED_COLUMNS_VERSION
            : self::MYSQL_GENERATED_COLUMNS_VERSION;

        return version_compare($versionParts[1], $minimumVersion, '>=');
    }
}
