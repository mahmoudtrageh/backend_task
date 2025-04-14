<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_department_position', 'department_id', 'user_id')
                    ->withPivot('position_id', 'is_manager')
                    ->withTimestamps();
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'user_department_position', 'department_id', 'user_id')
                    ->wherePivot('is_manager', true)
                    ->withTimestamps();
    }

    public function userDepartmentPositions()
    {
        return $this->hasMany(userDepartmentPosition::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
