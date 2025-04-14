<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveType>
 */
class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    public function definition()
    {
        $types = [
            'Annual Leave', 
            'Sick Leave', 
            'Personal Leave', 
            'Maternity/Paternity Leave',
            'Bereavement Leave'
        ];
        
        return [
            'name' => $this->faker->unique()->randomElement($types),
            'description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
