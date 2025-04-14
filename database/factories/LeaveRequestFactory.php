<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+30 days');
        $endDate = clone $startDate;
        $endDate->modify('+' . $this->faker->numberBetween(1, 14) . ' days');
        
        $statuses = ['pending', 'approved_by_manager', 'rejected', 'approved_by_hr'];
        
        return [
            'user_id' => User::factory(),
            'leave_type_id' => LeaveType::factory(),
            'department_id' => Department::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => $this->faker->sentence,
            'status' => $this->faker->randomElement($statuses),
            'manager_id' => null,
            'hr_manager_id' => null,
            'manager_comment' => null,
            'hr_comment' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }
    
    public function approvedByManager()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved_by_manager',
                'manager_id' => User::factory(),
                'manager_comment' => $this->faker->sentence,
            ];
        });
    }
    
    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'manager_id' => User::factory(),
                'manager_comment' => $this->faker->sentence,
            ];
        });
    }
}
