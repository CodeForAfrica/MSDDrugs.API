<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PriceCheck;

class Drug extends Model
{
    protected $fillable = ['name', 'form', 'strength', 'uom', 'location', 'price'];

    public function pricechecks(){
        return $this->hasMany('App\PriceCheck');
    }
}