<?php

namespace App\Actions;

use App\Http\Requests\ListStoreRequest;
use App\Http\Requests\ManageKlaviyoRequest;
use App\Models\ApiIntegration;
use App\Models\Catalog;
use App\Models\User;

class KlaviyoManager
{
    public function executeFromRequest(ManageKlaviyoRequest $request): User
    {
        $validatedData = $request->validated();

        /** @var User $user */
        $user = $request->user();

        ApiIntegration::query()
            ->where('api_vendor_key', $user->klaviyo_api_key)
            ->delete();

        $user->klaviyo_api_key = $validatedData['klaviyo_api_key'];
        $user->save();

        return $user->refresh();
    }

    public function removeKeyForUser(User $user): User
    {
        ApiIntegration::query()
            ->where('api_vendor_key', $user->klaviyo_api_key)
            ->delete();

        $user->klaviyo_api_key = null;
        $user->save();

        return $user->refresh();
    }
}
