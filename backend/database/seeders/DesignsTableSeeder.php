<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('designs')->insert([
            [
                'id' => 1,
                'name' => 'モダン',
            ],
            [
                'id' => 2,
                'name' => 'エレガント',
            ],
            [
                'id' => 3,
                'name' => 'ウッディ',
            ],
            [
                'id' => 4,
                'name' => 'クラシック',
            ],
            [
                'id' => 5,
                'name' => '和風',
            ],
        ]);
    }
}
