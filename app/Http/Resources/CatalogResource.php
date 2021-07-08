<?php

namespace App\Http\Resources;

use App\Models\Catalog;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Catalog $catalog */
        $catalog = $this;

        return [
            'id'             => $catalog->getKey(),
            'name'           => $catalog->name,
            'contacts_count' => $catalog->contacts_count,
            'created_at'     => $catalog->created_at,
            'updated_at'     => $catalog->updated_at,
            'integrations'   => $this->whenLoaded('integrations', function () use ($catalog) {
                return new ApiIntegrationCollection($catalog->integrations);
            })
        ];
    }
}
