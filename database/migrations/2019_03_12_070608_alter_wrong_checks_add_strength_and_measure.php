<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWrongChecksAddStrengthAndMeasure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wrong_checks', function($table) {
            $table->string('measure')->after('buying_amount')->nullable();
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
        Schema::table('wrong_checks', function($table) {
            $table->dropColumn('measure');
            $table->dropColumn('strength');
        });
    }
}
