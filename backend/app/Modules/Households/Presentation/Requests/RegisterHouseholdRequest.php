<?php

namespace App\Modules\Households\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterHouseholdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'community_id' => ['required', 'integer', 'exists:communities,id'],
            'leader_id' => ['nullable', 'integer', 'exists:members,id'],
            'ownership' => ['required', 'string', 'in:owned,rented'],
        ];
    }
}
