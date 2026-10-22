<?php

namespace App\Database;

final class LegacyMySqlServerVersion
{
    public static function supportsGenerationExpression(string $serverVersion): bool
    {
        $isMariaDb = stripos($serverVersion, 'MariaDB') !== false;
        // MariaDB may prefix its actual version with MySQL's compatibility version.
        $version = $isMariaDb ? preg_replace('/^5\.5\.5-/', '', $serverVersion) : $serverVersion;
        if (!preg_match('/^(\d+\.\d+\.\d+)/', $version, $matches)) {
            // Unknown servers retain Laravel's standard behavior rather than guessing.
            return true;
        }

        return version_compare($matches[1], $isMariaDb ? '10.2.0' : '5.7.0', '>=');
    }
}
