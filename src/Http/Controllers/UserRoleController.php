<?php

namespace KieranFYI\Roles\Http\Controllers;

use AppUser as User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use KieranFYI\Roles\Core\Models\Roles\Role;
use KieranFYI\Roles\Http\Requests\UpdateRequest;
use KieranFYI\UserUI\Policies\UserPolicy;

class UserRoleController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(UserPolicy::class, 'user', ['only' => ['update']]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $roles = collect($request->validated('roles'));

        $user->roles
            ->whereNotIn('id', $roles)
            ->each(function (Role $role) use ($user) {
                $user->removeRole($role);
            });

        Role::whereIn('id', $roles->diff($user->roles->pluck('id')))
            ->get()
            ->each(function(Role $role) use ($user) {
                $user->addRole($role);
            });

        return response()->json($user->roles()->get());
    }

}