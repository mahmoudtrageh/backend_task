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
            ['title' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'HR Manager', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Developer', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Designer', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Sales Representative', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Marketing Specialist', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Accountant', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('positions')->insert($positions);
    }
}
