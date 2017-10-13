<?php

use Illuminate\Database\Seeder;
use App\Keyword;

class KeywordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Keyword::truncate();

        // Creating a sample keywords in our database.
        Keyword::create([
            'name_short' => "MED",
            'name_long' => "Medicine",
            'description' => "Medicine category"
        ]);

        Keyword::create([
            'name_short' => "EDU",
            'name_long' => "Education",
            'description' => "Education category"
        ]);

        Keyword::create([
            'name_short' => "OUT",
            'name_long' => "Out",
            'description' => "Out category"
        ]);
    }
}
