<?php

namespace App\Repositories;

use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Support\Facades\Auth;

class LeaveRequestRepository implements LeaveRequestRepositoryInterface
{
    public function index($request)
    {
        $user = Auth::user();
        $query = LeaveRequest::query();

        if (!$user->isManager()) {
            $query->where('user_id', $user->id);
        } 

        // Apply additional filters if provided
        if ($request->has('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Load relationships
        $query->with(['user', 'leaveType', 'department', 'manager', 'hrManager']);

        // Sort by latest by default
        $query->latest();

        $perPage = $request->input('per_page', 15);

        return $query->paginate($perPage);
    }

    public function create(array $data): ?LeaveRequest
    {
        // Create leave request
        $leaveRequest = LeaveRequest::create([
            'user_id' => $data['user_id'],
            'leave_type_id' => $data['leave_type_id'],
            'department_id' => $data['department_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'status' => 'pending',
            'manager_id' => $data['direct_manager'] ?? null,
        ]);
        
        // Load relationships
        $leaveRequest->load(['user', 'leaveType', 'department', 'manager']);

        return $leaveRequest;
    }

    public function update(array $data, int $id): int
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        return $leaveRequest->update($data);
    }
}