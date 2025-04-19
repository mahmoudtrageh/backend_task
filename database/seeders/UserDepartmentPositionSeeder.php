<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDepartmentPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_department_position')->insert([
            'user_id' => 1, // HR Manager
            'department_id' => 1, // HR Department
            'position_id' => 1, // HR Manager
            'is_manager' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 2, // Sales Direct Manager
            'department_id' => 2, // IT Department
            'position_id' => 2, // Sales Manager
            'is_manager' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 3, // Finance Direct Manager
            'department_id' => 3, // Finance Department
            'position_id' => 3, // Manager
            'is_manager' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 4, // Sales Employee
            'department_id' => 2, // IT Department
            'position_id' => 4, // Employee
            'is_manager' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 5, // Finance Employee
            'department_id' => 3, // Finance Department
            'position_id' => 4, // Employee
            'is_manager' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
