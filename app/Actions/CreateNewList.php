<?php

namespace App\Actions;

use App\Http\Requests\ListStoreRequest;
use App\Models\Catalog;
use App\Models\User;

class CreateNewList
{
    public function createFromRequest(ListStoreRequest $request): Catalog
    {
        $validatedData = $request->validated();

        /** @var User $user */
        $user = $request->user();

        $newCatalog = new Catalog;
        $newCatalog->name = $validatedData['name'];
        $newCatalog->author()->associate($request->user());

        $newCatalog->save();

        return $newCatalog;
    }
}
