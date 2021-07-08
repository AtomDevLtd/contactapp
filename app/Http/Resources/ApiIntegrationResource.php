<?php

namespace App\Http\Resources;

use App\Models\ApiIntegration;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiIntegrationResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ApiIntegration $apiIntegration */
        $apiIntegration = $this;

        return [
            'id'                   => $apiIntegration->getKey(),
            'api_vendor'           => $apiIntegration->api_vendor,
            'syncable_external_id' => $apiIntegration->syncable_external_id,
            'syncable_synced_at'   => $apiIntegration->syncable_synced_at,
            'created_at'           => $apiIntegration->created_at,
            'updated_at'           => $apiIntegration->updated_at,
        ];
    }
}
