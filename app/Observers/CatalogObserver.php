<?php

namespace App\Observers;

use App\Jobs\CreateListInKlaviyo;
use App\Jobs\DeleteListInKlaviyo;
use App\Jobs\UpdateListInKlaviyo;
use App\Models\ApiIntegration;
use App\Models\Catalog;

class CatalogObserver
{
    /**
     * Handle the Catalog "created" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function created(Catalog $catalog)
    {
        if ($catalog->author->hasKlaviyoApiKey()) {
            $catalog->integrations()->create([
                'api_vendor'     => ApiIntegration::API_VENDOR_KLAVIYO,
                'api_vendor_key' => $catalog->author->klaviyo_api_key,
            ]);

            CreateListInKlaviyo::dispatchIf(
                $catalog->integrations->first(),
                $catalog->integrations->first()
            );
        }
    }

    /**
     * Handle the Catalog "updated" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function updated(Catalog $catalog)
    {
        UpdateListInKlaviyo::dispatchIf(
            $catalog->integrations->first(),
            $catalog->integrations->first()
        );
    }

    /**
     * Handle the Catalog "deleting" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function deleting(Catalog $catalog)
    {
        //
    }

    /**
     * Handle the Catalog "deleted" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function deleted(Catalog $catalog)
    {
        DeleteListInKlaviyo::dispatchIf(
            $catalog->integrations->first(),
            $catalog->integrations->first(),
            $catalog->author
        );
    }

    /**
     * Handle the Catalog "restored" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function restored(Catalog $catalog)
    {
        //
    }

    /**
     * Handle the Catalog "force deleted" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function forceDeleted(Catalog $catalog)
    {
        //
    }
}
