<?php

namespace App\Database;

use Illuminate\Database\MySqlConnection;

class LegacyMySqlConnection extends MySqlConnection
{
    protected function getDefaultSchemaGrammar()
    {
        return new LegacyMySqlSchemaGrammar($this);
    }
}
