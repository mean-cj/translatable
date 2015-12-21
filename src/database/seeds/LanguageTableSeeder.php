<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use AbbyLynn\Translatable\Services\LangNames;
use Carbon\Carbon;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $folders = array_diff(scandir(base_path('resources/lang')), array(".", ".."));
        $iso = new LangNames;
        $count = 0;

        foreach($folders as $code) {
            $default = 0;
            $count = $count+1;
            if($count == 1) {
                $default = 1;
            }
            DB::table('languages')->insert([
                'name'          => $iso->languageByCode1($code),
                'abbr'          => $code,
                'native'        => $iso->nativeByCode1($code),
                'active'        => 1,
                'default'       => $default,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }

        $this->command->info('Language seeding successful.');

    }
}