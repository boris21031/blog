<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'title' => 'designer'],
            ['id' => 2, 'title' => 'freelancer'],
            ['id' => 3, 'title' => 'tutor'],
            ['id' => 4, 'title' => 'marketer'],
            ['id' => 5, 'title' => 'programmer'],
            ['id' => 6, 'title' => 'production'],
            ['id' => 7, 'title' => 'photographer'],
        ]);
    }
}
