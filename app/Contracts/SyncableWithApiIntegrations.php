<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface SyncableWithApiIntegrations
{
    public function integrations(): MorphMany;
}
