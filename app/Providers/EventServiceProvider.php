<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\Contact;
use App\Observers\CatalogObserver;
use App\Observers\ContactObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Catalog::observe(CatalogObserver::class);
        Contact::observe(ContactObserver::class);
    }
}
