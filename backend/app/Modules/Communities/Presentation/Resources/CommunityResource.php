<?php

namespace App\Modules\Communities\Presentation\Resources;

use App\Modules\Communities\Domain\Entities\Community;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
{
    /**
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        /** @var Community $community */
        $community = $this->resource;

        return [
            'id' => $community->getId(),
            'name' => $community->getName(),
            'zone_id' => $community->getZoneId(),
        ];
    }
}
