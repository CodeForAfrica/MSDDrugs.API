<?php

namespace App\Database;

use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use PDO;

class LegacyMySqlSchemaGrammar extends MySqlGrammar
{
    public function compileColumns($schema, $table)
    {
        $sql = parent::compileColumns($schema, $table);
        // Resolve the server version only when introspection is requested, never at boot.
        $version = (string) $this->connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);
        if (!LegacyMySqlServerVersion::supportsGenerationExpression($version)) {
            return str_replace('generation_expression as `expression`', 'NULL as `expression`', $sql);
        }

        return $sql;
    }
}
