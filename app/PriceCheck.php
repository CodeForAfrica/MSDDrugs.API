<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Drug;

class PriceCheck extends Model
{
    protected $fillable = ['drug_id', 'buying_price', 'status', 'extra_amount', 'checker_phone_number'];

    public function drug(){
        return $this->belongsTo('App\Drug');
    }
}
