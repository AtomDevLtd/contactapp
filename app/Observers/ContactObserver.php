<?php

namespace App\Observers;

use App\Jobs\CreateContactInKlaviyo;
use App\Jobs\DeleteContactInKlaviyo;
use App\Jobs\UpdateContactInKlaviyo;
use App\Models\ApiIntegration;
use App\Models\Contact;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function created(Contact $contact)
    {
        if ($contact->author->hasKlaviyoApiKey()) {
            $contact->integrations()->create([
                'api_vendor'     => ApiIntegration::API_VENDOR_KLAVIYO,
                'api_vendor_key' => $contact->author->klaviyo_api_key,
            ]);

            CreateContactInKlaviyo::dispatchIf(
                $contact->integrations->first(),
                $contact->integrations->first()
            );
        }
    }

    /**
     * Handle the Contact "updated" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function updated(Contact $contact)
    {
        UpdateContactInKlaviyo::dispatchIf(
            $contact->integrations->first(),
            $contact->integrations->first()
        );
    }

    /**
     * Handle the Contact "deleting" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function deleting(Contact $contact)
    {
        DeleteContactInKlaviyo::dispatchIf(
            $contact->integrations->first(),
            $contact->integrations->first(),
            $contact->author
        );
    }

    /**
     * Handle the Contact "deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function deleted(Contact $contact)
    {

    }

    /**
     * Handle the Contact "restored" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function restored(Contact $contact)
    {
        //
    }

    /**
     * Handle the Contact "force deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function forceDeleted(Contact $contact)
    {
        //
    }
}
