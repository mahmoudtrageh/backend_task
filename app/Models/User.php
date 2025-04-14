<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_department_position', 'user_id', 'department_id')
            ->withPivot('position_id', 'is_manager')
            ->withTimestamps();
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'user_department_position', 'user_id', 'position_id')
            ->withPivot('department_id', 'is_manager')
            ->withTimestamps();
    }

    public function userDepartmentPositions()
    {
        return $this->hasMany(UserDepartmentPosition::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function managedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'manager_id');
    }

    public function hrManagedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'hr_manager_id');
    }

    public function isManager()
    {
        return $this->userDepartmentPositions()->where('is_manager', true)->exists();
    }

    public function managedDepartments()
    {
        return $this->belongsToMany(Department::class, 'user_department_position', 'user_id', 'department_id')
                    ->wherePivot('is_manager', true);
    }
}
