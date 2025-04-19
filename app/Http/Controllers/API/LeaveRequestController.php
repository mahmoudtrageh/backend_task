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
    public function __construct(
        protected LeaveRequestService $leaveRequestService
    ) {
    }

    public function index(Request $request)
    {

        $leaveRequests = $this->leaveRequestService->index($request);
        
        return response()->json([
            'success' => true,
            'data' => LeaveRequestResource::collection($leaveRequests)
        ]);
    }
    public function StoreLeaveRequest(StoreLeaveRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $belongsToDept = $user->departments()
            ->where('departments.id', $validated['department_id'])
            ->exists();

        if (!$belongsToDept) {
            return response()->json([
                'message' => 'You do not belong to this department'
            ], 403);
        }

        $directManager = $this->leaveRequestService->findDirectManager(
            $user->id, 
            $validated['department_id']
        );

        $leaveRequest = $this->leaveRequestService->create([
            'user_id' => $user->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
            'direct_manager' => $directManager ? $directManager->id : null,
            'department_id' => $validated['department_id'],
        ]);

        if($leaveRequest) {
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

        if($leaveRequest->manager_id != $user->id || $leaveRequest->status != 'pending') {
            return response()->json([
                'message' => 'You are not authorized to update this leave request'
            ], 403);
        }

        if ($validated['status'] == 'approved') 
        { 
            $this->leaveRequestService->update([
                'status' => 'approved_by_manager',
                'manager_comment' => $validated['comment'] ?? null
            ], $leaveRequest->id);

            $this->leaveRequestService->notifyHrManagers($leaveRequest->fresh());
            
            $message = 'Leave request approved and forwarded to HR';
                
        } else {

            $this->leaveRequestService->update([
                'status' => 'rejected',
                'manager_comment' => $validated['comment'] ?? null
            ], $leaveRequest->id);
            
            $this->leaveRequestService->notifyRejection($leaveRequest->fresh());
            
            $message = 'Leave request rejected';
        }

        return response()->json([
            'message' => $message,
            'data' => new LeaveRequestResource($leaveRequest->fresh()->load(['user', 'leaveType', 'department', 'manager']))
        ]);
    }
}
