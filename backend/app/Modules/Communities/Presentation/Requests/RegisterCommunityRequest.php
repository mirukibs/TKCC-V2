<?php

namespace App\Modules\Communities\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCommunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
        ];
    }
}
