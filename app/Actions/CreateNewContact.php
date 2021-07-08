<?php

namespace App\Actions;

use App\Http\Requests\ContactStoreRequest;
use App\Models\Catalog;
use App\Models\Contact;
use App\Models\User;

class CreateNewContact
{
    private ?ContactStoreRequest $request;

    private ?Catalog $catalog;

    public function __construct()
    {
        $this->request = null;
        $this->catalog = null;
    }

    public function fromRequest(ContactStoreRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function forCatalog(Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }

    public function make(): Contact
    {
        $this->areArgumentsSupplied();

        $validatedData = $this->request->validated();

        /** @var User $user */
        $user = $this->request->user();

        $newContact = new Contact();
        $newContact->name = $validatedData['name'];
        $newContact->email = $validatedData['email'];
        $newContact->phone = $validatedData['phone'];
        $newContact->author()->associate($user);
        $newContact->catalog()->associate($this->catalog);

        $newContact->save();

        return $newContact;
    }

    private function areArgumentsSupplied()
    {
        if ($this->request === null) {
            throw new \RuntimeException(
                'You have to set the Request for the creator using the fromRequest() setter method'
            );
        }

        if ($this->catalog === null) {
            throw new \RuntimeException(
                'You have to set the Catalog for the creator using the forCatalog() setter method'
            );
        }
    }
}
