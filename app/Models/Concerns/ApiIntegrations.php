<?php

namespace App\Models\Concerns;

use App\Models\ApiIntegration;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ApiIntegrations
{
    public function integrations(): MorphMany
    {
        return $this->morphMany(ApiIntegration::class, 'syncable');
    }
}
