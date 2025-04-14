<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'department_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'manager_id',
        'hr_manager_id',
        'manager_comment',
        'hr_comment',
        'comments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function hrManager()
    {
        return $this->belongsTo(User::class, 'hr_manager_id');
    }

    public function getTotalDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApprovedByManager($query)
    {
        return $query->where('status', 'approved_by_manager');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeApprovedByHr($query)
    {
        return $query->where('status', 'approved_by_hr');
    }
}
