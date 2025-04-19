<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeFilterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index(EmployeeFilterRequest $request)
    {
        $validated = $request->validated();
    
        $query = User::query();

        if (isset($validated['department_id'])) {
            $departmentId = $validated['department_id'];
            
            $query->select('users.*')
              ->join('user_department_position', 'users.id', '=', 'user_department_position.user_id')
              ->where('user_department_position.department_id', $departmentId)
              ->distinct();
        }

        // Eager load relationships
        $query->with(['departments', 'positions', 'userDepartmentPositions' => function($q) {
            $q->with(['department', 'position']);
        }]);

        $perPage = $validated['per_page'] ?? 15;
        $employees = $query->paginate($perPage);

        return UserResource::collection($employees);
    }
}
