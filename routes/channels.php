<?php

use App\Models\Catalog;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('lists.forUser.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('contacts.forList.{catalog}', function (User $user, Catalog $catalog) {
    return (int) $user->getKey() === (int) $catalog->author->getKey();
});
