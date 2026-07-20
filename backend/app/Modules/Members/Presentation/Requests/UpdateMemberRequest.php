<?php

namespace App\Modules\Members\Presentation\Requests;

use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Enums\MaritalStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'dob' => 'nullable|date|before_or_equal:today',
            'gender' => ['nullable', new Enum(Gender::class)],
            'marital_status' => ['nullable', new Enum(MaritalStatus::class)],
            'phone' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'employment_status' => ['nullable', new Enum(EmploymentStatus::class)],
            'employment_notes' => 'nullable|string',
            'household_id' => 'nullable|integer',
        ];
    }
}
