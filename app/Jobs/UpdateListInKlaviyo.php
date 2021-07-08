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

class UpdateListInKlaviyo implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ApiIntegration $apiIntegration;

    public int $uniqueFor = 300;

    public bool $deleteWhenMissingModels = true;

    public function __construct(ApiIntegration $apiIntegration)
    {
        $this->apiIntegration = $apiIntegration;
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
        /** @var User $user */
        $user = $this->apiIntegration->syncable->author;

        if (
            $user->hasKlaviyoApiKey()
            && $user->klaviyo_api_key === $this->apiIntegration->api_vendor_key
        ) {
            $klaviyoClient = new Klaviyo(
                $user->klaviyo_api_key,
                'NOT_NEEDED_KEY'
            );

            $klaviyoClient->lists->updateListNameById(
                $this->apiIntegration->syncable_external_id,
                $this->apiIntegration->syncable->name
            );

            $this->apiIntegration->update([
                'syncable_synced_at' => now(),
            ]);
        }
    }
}
