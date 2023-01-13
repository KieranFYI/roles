<?php

namespace KieranFYI\Roles\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use KieranFYI\Misc\Exceptions\CacheableException;
use KieranFYI\Misc\Traits\ResponseCacheable;
use KieranFYI\Roles\Core\Models\Roles\Role;
use KieranFYI\Roles\Http\Requests\SearchRequest;

class RoleAPIController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use ResponseCacheable;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws CacheableException
     */
    public function index(): JsonResponse
    {
        $roles = Role::get();
        return response()->json($roles);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     * @throws CacheableException
     */
    public function show(Request $request, Role $role): JsonResponse
    {
        $this->setLastModified($role->updated_at);
        return response()->json($role);
    }

    /**
     * Display a listing of the resource.
     *
     * @param SearchRequest $request
     * @return JsonResponse
     * @throws CacheableException
     */
    public function search(SearchRequest $request): JsonResponse
    {
        $roles = Role::query();

        $validated = $request->validated();

        $roles->when(!empty($validated['search']), function (Builder $builder) use ($validated) {
            $search = $validated['search'];
            $builder->where(function ($builder) use ($search) {
                $builder->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        });

        return response()->json($roles->get());
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewAny',
            'search' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'create', 'store', 'search'];
    }
}