<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    /**
     * Get the users that have this position.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_department_position', 'position_id', 'user_id')
                    ->withPivot('department_id', 'is_manager')
                    ->withTimestamps();
    }

    public function userDeparmentPosition()
    {
        return $this->hasMany(userDepartmentPosition::class);
    }
}
