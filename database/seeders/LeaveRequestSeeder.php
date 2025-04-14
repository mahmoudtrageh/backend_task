<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_requests')->insert([
            'user_id' => 4, // Muhammad
            'leave_type_id' => 1, // Annual Leave
            'department_id' => 2, // IT Department
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(15),
            'reason' => 'Family vacation',
            'status' => 'pending',
            'manager_id' => 2, // IT Manager
            'hr_manager_id' => null,
            'manager_comment' => null,
            'hr_comment' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('leave_requests')->insert([
            'user_id' => 4, // Muhammad
            'leave_type_id' => 2, // Sick Leave
            'department_id' => 2, // IT Department
            'start_date' => now()->subDays(15),
            'end_date' => now()->subDays(14),
            'reason' => 'Not feeling well',
            'status' => 'approved_by_hr',
            'manager_id' => 2, // IT Manager
            'hr_manager_id' => 1, // HR Manager
            'manager_comment' => 'Approved',
            'hr_comment' => 'Approved',
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDays(18),
        ]);

        DB::table('leave_requests')->insert([
            'user_id' => 4, // Muhammad
            'leave_type_id' => 1, // Annual Leave
            'department_id' => 2, // IT Department
            'start_date' => now()->subDays(30),
            'end_date' => now()->subDays(25),
            'reason' => 'Personal trip',
            'status' => 'rejected',
            'manager_id' => 2, // IT Manager
            'hr_manager_id' => null,
            'manager_comment' => 'We have a critical project deadline during this time',
            'hr_comment' => null,
            'created_at' => now()->subDays(35),
            'updated_at' => now()->subDays(33),
        ]);

        DB::table('leave_requests')->insert([
            'user_id' => 5, // Sarah
            'leave_type_id' => 1, // Annual Leave
            'department_id' => 3, // Sales Department
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(10),
            'reason' => 'Personal vacation',
            'status' => 'approved_by_hr',
            'manager_id' => 3, // Sales Manager
            'hr_manager_id' => 1, // HR Manager
            'manager_comment' => 'Approved',
            'hr_comment' => 'Approved and recorded',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(8),
        ]);

        DB::table('leave_requests')->insert([
            'user_id' => 6, // John
            'leave_type_id' => 3, // Personal Leave
            'department_id' => 5, // Finance Department
            'start_date' => now()->addDays(15),
            'end_date' => now()->addDays(16),
            'reason' => 'Family matters',
            'status' => 'pending',
            'manager_id' => null, // No manager assigned yet
            'hr_manager_id' => null,
            'manager_comment' => null,
            'hr_comment' => null,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);
    }
}
