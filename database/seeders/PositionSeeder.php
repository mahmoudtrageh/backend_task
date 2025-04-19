<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['title' => 'HR Manager', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Sales Manager', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Finance Manager', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Employee', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('positions')->insert($positions);
    }
}
