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
            'user_id' => 1,
            'department_id' => 1, // HR Department
            'position_id' => 2, // HR Manager
            'is_manager' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 2,
            'department_id' => 2, // IT Department
            'position_id' => 1, // Manager
            'is_manager' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 3,
            'department_id' => 3, // Sales Department
            'position_id' => 1, // Manager
            'is_manager' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 4,
            'department_id' => 2, // IT Department
            'position_id' => 3, // Developer
            'is_manager' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 4,
            'department_id' => 4, // Marketing Department
            'position_id' => 6, // Marketing Specialist
            'is_manager' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 5,
            'department_id' => 3, // Sales Department
            'position_id' => 5, // Sales Representative
            'is_manager' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_department_position')->insert([
            'user_id' => 6,
            'department_id' => 5, // Finance Department
            'position_id' => 7, // Accountant
            'is_manager' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
