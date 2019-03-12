<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPriceChecksAddStrengthAndMeasure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_checks', function($table) {
            $table->string('measure')->after('buying_price')->nullable();
            $table->string('strength')->after('measure')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_checks', function($table) {
            $table->dropColumn('measure');
            $table->dropColumn('strength');
        });
    }
}
