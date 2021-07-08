<?php

use App\Http\Controllers\CatalogContactController;
use App\Http\Controllers\KlaviyoController;
use App\Http\Controllers\CatalogController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inspiring::quote();
})->name('no-home');
Route::get('/needsLogin', function () {
    return redirect()->route('no-home');
})->name('login');

Route::get('/reset-password/{token}', function (Request $request, $token) {
    $email = $request->input('email');

    return redirect()->to(
        config('project.front_end_url') . "/reset-password/?token=$token&email=$email"
    );
})
->middleware(['guest:'.config('fortify.guard')])
->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('user/integrations/manage/klaviyo', [KlaviyoController::class, 'update'])
        ->name('api.integrations.klaviyo.update');
    Route::delete('user/integrations/manage/klaviyo', [KlaviyoController::class, 'destroy'])
        ->name('api.integrations.klaviyo.destroy');

    Route::apiResource('catalogs', CatalogController::class);
    Route::apiResource('catalogs.contacts', CatalogContactController::class)->shallow();
});
