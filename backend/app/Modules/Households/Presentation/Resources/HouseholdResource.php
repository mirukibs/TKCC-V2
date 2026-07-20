<?php

namespace App\Modules\Households\Presentation\Resources;

use App\Modules\Households\Domain\Entities\Household;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseholdResource extends JsonResource
{
    /**
     * @var Household
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}
