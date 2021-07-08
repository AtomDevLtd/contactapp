<?php

namespace App\Actions;

use App\Http\Requests\ContactUpdateRequest;
use App\Models\Contact;

class UpdateContact
{
    public function update(ContactUpdateRequest $request, Contact $contact): Contact
    {
        $validatedData = $request->validated();

        $contact->name = $validatedData['name'];
        $contact->email = $validatedData['email'];
        $contact->phone = $validatedData['phone'];
        $contact->save();

        return $contact->refresh();
    }
}
