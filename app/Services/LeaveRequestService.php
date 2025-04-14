<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestForApproval;
use App\Mail\LeaveRequestApproved;
use App\Mail\LeaveRequestRejected;

class LeaveRequestService
{
    /**
     * Find the direct manager of a user in a specific department
     *
     * @param int $userId
     * @param int $departmentId
     * @return User|null
     */
    public function findDirectManager($userId, $departmentId)
    {
        // Find manager in the same department
        $manager = User::whereHas('userDepartmentPositions', function($query) use ($departmentId) {
                $query->where('department_id', $departmentId)
                      ->where('is_manager', true);
            })
            ->first();
            
        return $manager;
    }
    
    /**
     * Find HR managers
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findHrManagers()
    {
        return User::whereHas('userDepartmentPositions', function($query) {
                $query->whereHas('position', function($q) {
                    $q->where('title', 'HR Manager');
                })
                ->where('is_manager', true);
            })
            ->get();
    }
    
    /**
     * Send notification to the direct manager for approval
     *
     * @param LeaveRequest $leaveRequest
     * @param User $manager
     * @return void
     */
    public function notifyManager(LeaveRequest $leaveRequest, User $manager)
    {
        Mail::to($manager->email)
        ->send(new LeaveRequestForApproval($leaveRequest, $manager));
    }
    
    /**
     * Send notification to HR managers after manager approval
     *
     * @param LeaveRequest $leaveRequest
     * @return void
     */
    public function notifyHrManagers(LeaveRequest $leaveRequest)
    {
        $hrManagers = $this->findHrManagers();
        
        foreach ($hrManagers as $hrManager) {            
            Mail::to($hrManager->email)
            ->send(new LeaveRequestForApproval($leaveRequest, $hrManager));
        }
    }
    
    /**
     * Send rejection notification to the employee
     *
     * @param LeaveRequest $leaveRequest
     * @return void
     */
    public function notifyRejection(LeaveRequest $leaveRequest)
    {
        // Send email notification
        $rejectedBy = 'manager';
        Mail::to($leaveRequest->user->email)
        ->send(new LeaveRequestRejected($leaveRequest, $rejectedBy));
    }
}