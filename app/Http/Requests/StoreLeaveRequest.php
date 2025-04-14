<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
     /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'leave_type_id' => 'required|exists:leave_types,id',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'leave_type_id.required' => 'Please select a leave type',
            'leave_type_id.exists' => 'Selected leave type is invalid',
            'department_id.required' => 'Please select a department',
            'department_id.exists' => 'Selected department is invalid',
            'start_date.required' => 'Start date is required',
            'start_date.date' => 'Start date must be a valid date',
            'start_date.after_or_equal' => 'Start date must be today or in the future',
            'end_date.required' => 'End date is required',
            'end_date.date' => 'End date must be a valid date',
            'end_date.after_or_equal' => 'End date must be after or equal to start date',
            'reason.required' => 'Reason for leave is required',
            'reason.min' => 'Reason must be at least 5 characters',
            'reason.max' => 'Reason cannot exceed 1000 characters',
        ];
    }
}
