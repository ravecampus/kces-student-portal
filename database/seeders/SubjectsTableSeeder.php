<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            'id' => 1,
            'username' => 'admin',
            'email' => 'winsdev@gmail.com',
            'password' => bcrypt('admin'),
            'role' =>3,
        ]);

        DB::table('subjects')->delete();
        DB::table('subjects')->insert([
            'id' => 1,
            'subject_name' => 'Filipino',
        ]);

        DB::table('subjects')->insert([
            'id' => 2,
            'subject_name' => 'English',
        ]);
        DB::table('subjects')->insert([
            'id' => 3,
            'subject_name' => 'Mathematics',
        ]);
        DB::table('subjects')->insert([
            'id' => 4,
            'subject_name' => 'Science',
        ]);
        DB::table('subjects')->insert([
            'id' => 5,
            'subject_name' => 'Araling Panlipunan (AP)',
        ]);
        DB::table('subjects')->insert([
            'id' => 6,
            'subject_name' => 'EPP / TLE',
        ]);
        DB::table('subjects')->insert([
            'id' => 7,
            'subject_name' => 'Music',
        ]);
        DB::table('subjects')->insert([
            'id' => 8,
            'subject_name' => 'Art',
        ]);
        DB::table('subjects')->insert([
            'id' => 9,
            'subject_name' => 'Physical Education (PE)',
        ]);
        DB::table('subjects')->insert([
            'id' => 10,
            'subject_name' => 'Health',
        ]);
        DB::table('subjects')->insert([
            'id' => 11,
            'subject_name' => 'Edukasyon sa Pagpapakatao (EsP)',
        ]);
        DB::table('subjects')->insert([
            'id' => 12,
            'subject_name' => 'Homeroom Guidance',
        ]);
      
    }
}
