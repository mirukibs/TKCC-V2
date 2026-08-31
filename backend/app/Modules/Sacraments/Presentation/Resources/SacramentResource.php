<?php

namespace App\Modules\Sacraments\Presentation\Resources;

use App\Modules\Sacraments\Infrastructure\Models\SacramentModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SacramentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        // Ensure enum strings are loaded appropriately if passing eloquent model
        if ($this->resource instanceof SacramentModel) {
            $data['member'] = [
                'id' => $this->resource->member->id ?? null,
                'first_name' => $this->resource->member->first_name ?? null,
                'last_name' => $this->resource->member->last_name ?? null,
                'name' => trim(($this->resource->member->first_name ?? '').' '.($this->resource->member->last_name ?? '')),
            ];
        }

        return $data;
    }
}
