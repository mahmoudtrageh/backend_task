<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    protected $leaveRequestService;
    
    /**
     * Constructor with dependency injection
     * 
     * @param LeaveRequestService $leaveRequestService
     */
    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->leaveRequestService = $leaveRequestService;
    }

    public function index(Request $request)
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
        $leaveRequests = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => LeaveRequestResource::collection($leaveRequests),
            'pagination' => [
                'total' => $leaveRequests->total(),
                'per_page' => $leaveRequests->perPage(),
                'current_page' => $leaveRequests->currentPage(),
                'last_page' => $leaveRequests->lastPage()
            ]
        ]);
    }
    public function StoreLeaveRequest(StoreLeaveRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // Check if user belongs to the department
        $belongsToDept = $user->departments()
            ->where('departments.id', $validated['department_id'])
            ->exists();

        if (!$belongsToDept) {
            return response()->json([
                'message' => 'You do not belong to this department'
            ], 403);
        }

        // Find direct manager
        $directManager = $this->leaveRequestService->findDirectManager(
            $user->id, 
            $validated['department_id']
        );

        // Create leave request
        $leaveRequest = LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type_id' => $validated['leave_type_id'],
            'department_id' => $validated['department_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
            'manager_id' => $directManager ? $directManager->id : null,
        ]);
        
        // Load relationships
        $leaveRequest->load(['user', 'leaveType', 'department', 'manager']);

        // Notify the direct manager if exists
        if ($directManager) {
            $this->leaveRequestService->notifyManager($leaveRequest, $directManager);
        }

        return response()->json([
            'message' => 'Leave request submitted successfully',
            'data' => new LeaveRequestResource($leaveRequest)
        ], 201);
    }

    public function updateLevelRequestStatus(UpdateLeaveRequest $request, $id)
    {
        $user = Auth::user();
        $leaveRequest = LeaveRequest::findOrFail($id);
        $validated = $request->validated();

        // Check if user is the assigned manager
        if ($leaveRequest->manager_id == $user->id && $leaveRequest->status == 'pending') {
            // Manager is updating the request
            if ($validated['status'] == 'approved') {
                $leaveRequest->update([
                    'status' => 'approved_by_manager',
                    'manager_comment' => $validated['comment'] ?? null
                ]);
                
                // Notify HR managers for final approval
                $this->leaveRequestService->notifyHrManagers($leaveRequest->fresh());
                
                return response()->json([
                    'message' => 'Leave request approved and forwarded to HR',
                    'data' => new LeaveRequestResource($leaveRequest->fresh()->load(['user', 'leaveType', 'department', 'manager']))
                ]);
            } else {
                // Request rejected by manager
                $leaveRequest->update([
                    'status' => 'rejected',
                    'manager_comment' => $validated['comment'] ?? 'No reason provided'
                ]);
                
                // Notify the employee of rejection
                $this->leaveRequestService->notifyRejection($leaveRequest->fresh());
                
                return response()->json([
                    'message' => 'Leave request rejected',
                    'data' => new LeaveRequestResource($leaveRequest->fresh()->load(['user', 'leaveType', 'department', 'manager']))
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized to update this leave request'
            ], 403);
        }
    }
}
