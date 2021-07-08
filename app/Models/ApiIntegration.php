<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApiIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_vendor',
        'api_vendor_key',
        'syncable_external_id',
        'syncable_synced_at',
    ];

    protected $casts = [
        'syncable_synced_at' => 'datetime',
    ];

    const API_VENDOR_KLAVIYO = 'klaviyo';

    /**
     * All of the models Mutators&Accessors
     * should begin from here.
     */

    /**
     * All of the models scopes methods
     * should begin from here.
     */

    /**
     * All of the models custom methods
     * should begin from here.
     */

    /**
     * All of the models method overwrites
     * should begin from here.
     */


    /**
     * All of the models relationships
     * should begin from here.
     */

    public function syncable(): MorphTo
    {
        return $this->morphTo();
    }
}
