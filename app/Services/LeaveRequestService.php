<?php

namespace App\Services;

use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestForApproval;
use App\Mail\LeaveRequestRejected;
use App\Repositories\LeaveRequestRepositoryInterface;

class LeaveRequestService
{
    public function __construct(
        protected LeaveRequestRepositoryInterface $leaveRequestRepository
    ) {
    }

    public function index($request)
    {
        return $this->leaveRequestRepository->index($request);
    }
    public function create(array $data): LeaveRequest
    {
        return $this->leaveRequestRepository->create($data);
    }

    public function update(array $data, int $id): int
    {
        return $this->leaveRequestRepository->update($data, $id);
    }

    public function findDirectManager($userId, $departmentId)
    {
        $manager = User::whereHas('userDepartmentPositions', function($query) use ($departmentId) {
                $query->where('department_id', $departmentId)
                      ->where('is_manager', true);
            })
            ->first();
            
        return $manager;
    }
    
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
    
    public function notifyManager(LeaveRequest $leaveRequest, User $manager)
    {
        Mail::to($manager->email)
        ->send(new LeaveRequestForApproval($leaveRequest, $manager));
    }
    
    public function notifyHrManagers(LeaveRequest $leaveRequest)
    {
        $hrManagers = $this->findHrManagers();
        
        foreach ($hrManagers as $hrManager) {            
            Mail::to($hrManager->email)
            ->send(new LeaveRequestForApproval($leaveRequest, $hrManager));
        }
    }
    
    public function notifyRejection(LeaveRequest $leaveRequest)
    {
        $rejectedBy = 'manager';
        Mail::to($leaveRequest->user->email)
        ->send(new LeaveRequestRejected($leaveRequest, $rejectedBy));
    }
}