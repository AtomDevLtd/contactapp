<?php

namespace App\Http\Controllers;

use App\Actions\CreateNewContact;
use App\Actions\UpdateContact;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Catalog;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CatalogContactController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Contact::class);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, Catalog $catalog)
    {
        $this->authorize('view', $catalog);

        $contacts = Contact::query()
            ->with([
                'integrations',
            ])
            ->forCatalog($catalog)
            ->forUser($request->user())
            ->latest('id')
            ->cursorPaginate(25);

        return (new ContactCollection($contacts))
            ->additional([
                'nextCursor' => optional($contacts->nextCursor())->encode() ?? null
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show(Request $request, Contact $contact): JsonResponse
    {
        return (new ContactResource(
            $contact->load('integrations')
        ))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ContactStoreRequest $request, Catalog $catalog, CreateNewContact $creator): JsonResponse
    {
        $this->authorize('update', $catalog);

        $createdContact = $creator
            ->fromRequest($request)
            ->forCatalog($catalog)
            ->make();

        return (new ContactResource(
            $createdContact->load('integrations')
        ))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ContactUpdateRequest $request, Contact $contact, UpdateContact $updater): JsonResponse
    {
        $this->authorize('update', $contact->catalog);

        $updatedContact = $updater->update($request, $contact);

        return (new ContactResource(
            $updatedContact->load('integrations')
        ))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function destroy(Request $request, Contact $contact)
    {
        try {
            $contact->delete();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            throw ValidationException::withMessages([
                'contact' => 'Contact could not be deleted'
            ]);
        }

        return response([
            'message' => 'All done'
        ], Response::HTTP_ACCEPTED);
    }
}
