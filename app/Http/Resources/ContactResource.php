<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Contact $contact */
        $contact = $this;

        return [
            'id'              => $contact->getKey(),
            'name'            => $contact->name,
            'email'           => $contact->email,
            'phone'           => $contact->phone,
            'phone_formatted' => $contact->phone_formatted,
            'catalog'         => $this->whenLoaded('catalog', function () use ($contact) {
                return new CatalogResource($contact->catalog);
            }),
            'created_at'      => $contact->created_at,
            'updated_at'      => $contact->updated_at,
            'integrations'    => $this->whenLoaded('integrations', function () use ($contact) {
                return new ApiIntegrationCollection($contact->integrations);
            })
        ];
    }
}
