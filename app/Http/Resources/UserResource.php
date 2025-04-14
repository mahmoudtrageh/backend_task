<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_manager' => $this->isManager(),
            'departments' => DepartmentResource::collection($this->whenLoaded('departments')),
            'positions' => PositionResource::collection($this->whenLoaded('positions')),
            'employee_dept_positions' => UserDepartmentPositionsResource::collection($this->whenLoaded('userDepartmentPositions')),
            'managed_departments' => DepartmentResource::collection($this->whenLoaded('managedDepartments')),
        ];
    }
}
