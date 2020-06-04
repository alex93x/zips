<?php

use Illuminate\Database\Seeder;

class ZipSeeder extends Seeder
{
    public function run()
    {
        DB::disableQueryLog();
        DB::table('zips')->truncate();
        $filename = base_path().'/database/seeds/csvs/uszips.csv';
        $csv = new ParseCsv\Csv($filename);
        $parts = array_chunk($csv->data, 100);
        foreach($parts as $part) {
            DB::table('zips')->insert($part);
        }
    }
}
