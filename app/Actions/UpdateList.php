<?php

namespace App\Actions;

use App\Http\Requests\ListUpdateRequest;
use App\Models\Catalog;

class UpdateList
{
    public function update(ListUpdateRequest $request, Catalog $catalog): Catalog
    {
        $validatedData = $request->validated();

        $catalog->name = $validatedData['name'];
        $catalog->save();

        return $catalog->refresh();
    }
}
