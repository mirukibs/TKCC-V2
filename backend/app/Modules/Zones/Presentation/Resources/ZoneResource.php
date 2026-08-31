<?php

namespace App\Modules\Zones\Presentation\Resources;

use App\Modules\Zones\Domain\Entities\Zone;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Zone $zone */
        $zone = $this->resource;

        return [
            'id' => $zone->getId(),
            'name' => $zone->getName(),
        ];
    }
}
