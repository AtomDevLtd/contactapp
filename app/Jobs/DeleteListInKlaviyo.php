<?php

namespace App\Jobs;

use App\Models\ApiIntegration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Klaviyo\Klaviyo;

class DeleteListInKlaviyo implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ApiIntegration $apiIntegration;

    public User $user;

    public int $uniqueFor = 300;

    public bool $deleteWhenMissingModels = true;

    public function __construct(ApiIntegration $apiIntegration, User $user)
    {
        $this->apiIntegration = $apiIntegration;
        $this->user = $user;
        $this->onQueue('api-operations');
    }

    public function uniqueId(): string
    {
        return $this->apiIntegration->getKey();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user->hasKlaviyoApiKey()) {
            if ($this->user->klaviyo_api_key === $this->apiIntegration->api_vendor_key) {
                $klaviyoClient = new Klaviyo(
                    $this->user->klaviyo_api_key,
                    'NOT_NEEDED_KEY'
                );
            } else {
                $klaviyoClient = new Klaviyo(
                    $this->apiIntegration->api_vendor_key,
                    'NOT_NEEDED_KEY'
                );
            }

            try {
                $klaviyoClient->lists->deleteList($this->apiIntegration->syncable_external_id);
            } catch (\Exception $exception) {}
        }

        $this->apiIntegration->delete();
    }
}
