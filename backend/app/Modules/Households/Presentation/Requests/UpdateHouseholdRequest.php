<?php

namespace App\Modules\Households\Presentation\Requests;

use App\Modules\Households\Domain\Enums\OwnershipType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateHouseholdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'community_id' => 'required|integer',
            'leader_id' => 'nullable|integer',
            'ownership' => ['required', new Enum(OwnershipType::class)],
        ];
    }
}
