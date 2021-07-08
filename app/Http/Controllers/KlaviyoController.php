<?php

namespace App\Http\Controllers;

use App\Actions\KlaviyoManager;
use App\Http\Requests\ManageKlaviyoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KlaviyoController extends Controller
{
    public function update(ManageKlaviyoRequest $request, KlaviyoManager $manager)
    {
        $manager->executeFromRequest($request);

        return response([
            'message' => 'All done'
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request, KlaviyoManager $manager)
    {
        $manager->removeKeyForUser($request->user());

        return response([
            'message' => 'All done'
        ], Response::HTTP_OK);
    }
}
