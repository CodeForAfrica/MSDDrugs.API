<?php

namespace App\Database;

use Illuminate\Database\MySqlConnection;

class LegacyMySqlConnection extends MySqlConnection
{
    protected function getDefaultSchemaGrammar(): LegacyMySqlSchemaGrammar
    {
        return new LegacyMySqlSchemaGrammar($this);
    }
}
