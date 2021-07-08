<?php

namespace App\Jobs;

use App\Exceptions\ListInKlaviyo;
use App\Models\ApiIntegration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Klaviyo\Klaviyo;
use Klaviyo\Model\ProfileModel;

class UpdateContactInKlaviyo implements ShouldQueue, ShouldBeUnique
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
     * @throws ListInKlaviyo
     */
    public function handle()
    {
        /** @var User $user */
        $user = $this->apiIntegration->syncable->author;

        /** @var ApiIntegration $catalogApiIntegration */
        $catalogApiIntegration = $this->apiIntegration->syncable->catalog->integrations->first();

        if (
            $user->hasKlaviyoApiKey()
            && $user->klaviyo_api_key === $this->apiIntegration->api_vendor_key
            && $catalogApiIntegration
        ) {
            $klaviyoClient = new Klaviyo(
                $user->klaviyo_api_key,
                'NOT_NEEDED_KEY'
            );

            try {
                $listInKlaviyo = $klaviyoClient->lists->getListById($catalogApiIntegration->syncable_external_id);
            } catch (\Exception $exception) {
                throw ListInKlaviyo::notFoundById(
                    'The list was not found in Klaviyo',
                    $exception
                );
            }

            $klaviyoClient->profiles->updateProfile(
                $this->apiIntegration->syncable_external_id,
                [
                    'name'         => $this->apiIntegration->syncable->name,
                    '$email'       => $this->apiIntegration->syncable->email,
                    '$phone_number' => $this->apiIntegration->syncable->phone_formatted,
                ]
            );

            $this->apiIntegration->update([
                'syncable_synced_at' => now(),
            ]);
        }
    }
}
