<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnprocessedData extends Model
{
    protected $table = "unprocessed_data";

    protected $fillable = ['keyword', 'amount'];
}
