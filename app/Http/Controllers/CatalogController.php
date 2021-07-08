<?php

namespace App\Http\Controllers;

use App\Actions\CreateNewList;
use App\Actions\UpdateList;
use App\Http\Requests\ListStoreRequest;
use App\Http\Requests\ListUpdateRequest;
use App\Http\Resources\CatalogCollection;
use App\Http\Resources\CatalogResource;
use App\Models\Catalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Catalog::class);
    }

    public function index(Request $request)
    {
        $catalogs = Catalog::query()
            ->with([
                'integrations',
            ])
            ->withCount([
                'contacts',
            ])
            ->forUser($request->user())
            ->latest('id')
            ->cursorPaginate(25);

        return (new CatalogCollection($catalogs))
            ->additional([
                'nextCursor' => optional($catalogs->nextCursor())->encode() ?? null
            ])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show(Request $request, Catalog $catalog): JsonResponse
    {
        return (new CatalogResource(
            $catalog
                ->load('integrations')
                ->loadCount('contacts')
        ))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function store(ListStoreRequest $request, CreateNewList $listCreator): JsonResponse
    {
        $createdCatalog = $listCreator->createFromRequest($request);

        return (new CatalogResource(
            $createdCatalog
                ->load('integrations')
                ->loadCount('contacts')
        ))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(ListUpdateRequest $request, Catalog $catalog, UpdateList $listUpdater): JsonResponse
    {
        $updatedCatalog = $listUpdater->update($request, $catalog);

        return (new CatalogResource(
            $updatedCatalog
                ->load('integrations')
                ->loadCount('contacts')
        ))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function destroy(Request $request, Catalog $catalog)
    {
        //$catalog->delete();
        try {
            $catalog->delete();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            throw ValidationException::withMessages([
                'list' => 'List could not be deleted'
            ]);
        }

        return response([
            'message' => 'All done'
        ], Response::HTTP_ACCEPTED);
    }
}
