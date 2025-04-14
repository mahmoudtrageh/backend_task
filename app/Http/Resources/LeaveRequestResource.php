<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'leave_type_id' => $this->leave_type_id,
            'department_id' => $this->department_id,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'total_days' => $this->total_days,
            'reason' => $this->reason,
            'status' => $this->status,
            'manager_id' => $this->manager_id,
            'hr_manager_id' => $this->hr_manager_id,
            'manager_comment' => $this->manager_comment,
            'hr_comment' => $this->hr_comment,
            'comments' => $this->comments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'leave_type' => new LeaveTypeResource($this->whenLoaded('leaveType')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'manager' => new UserResource($this->whenLoaded('manager')),
            'hr_manager' => new UserResource($this->whenLoaded('hrManager')),
        ];
    }
}
