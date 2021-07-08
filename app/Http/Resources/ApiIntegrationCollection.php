<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiIntegrationCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ApiIntegrationResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
