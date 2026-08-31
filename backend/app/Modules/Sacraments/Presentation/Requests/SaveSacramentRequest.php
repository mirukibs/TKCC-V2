<?php

namespace App\Modules\Sacraments\Presentation\Requests;

use App\Modules\Sacraments\Domain\Enums\BaptismStatus;
use App\Modules\Sacraments\Domain\Enums\ConfirmationStatus;
use App\Modules\Sacraments\Domain\Enums\MarriageStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SaveSacramentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id' => 'required|integer|exists:members,id',
            'baptism_status' => ['required', new Enum(BaptismStatus::class)],
            'baptism_date' => 'nullable|date',
            'baptism_place' => 'nullable|string|max:255',
            'confirmation_status' => ['required', new Enum(ConfirmationStatus::class)],
            'confirmation_date' => 'nullable|date',
            'confirmation_place' => 'nullable|string|max:255',
            'marriage_status' => ['required', new Enum(MarriageStatus::class)],
            'marriage_date' => 'nullable|date',
            'marriage_place' => 'nullable|string|max:255',
        ];
    }
}
