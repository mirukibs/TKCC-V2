<?php

namespace App\Modules\Zones\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
        ];
    }
}
