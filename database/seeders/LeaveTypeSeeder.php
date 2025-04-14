<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'description' => 'Regular vacation time',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sick Leave',
                'description' => 'Leave due to illness',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Personal Leave',
                'description' => 'Leave for personal matters',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Maternity/Paternity Leave',
                'description' => 'Leave for new parents',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bereavement Leave',
                'description' => 'Leave following the death of a family member',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('leave_types')->insert($leaveTypes);
    }
}
