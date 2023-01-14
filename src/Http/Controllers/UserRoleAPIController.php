<?php

namespace KieranFYI\Roles\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use KieranFYI\Misc\Traits\ResponseCacheable;
use KieranFYI\Roles\Core\Models\Roles\Role;
use KieranFYI\Roles\Core\Traits\Roles\HasRolesTrait;
use KieranFYI\Roles\Http\Requests\UpdateRequest;
use KieranFYI\Roles\Models\User;
use Throwable;

class UserRoleAPIController extends Controller
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
        $this->authorizeResource(User::class, 'user', ['only' => ['update', 'show']]);
    }

    /**
     * Show specified resource in storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws Throwable
     */
    public function show(User $user): JsonResponse
    {
        abort_unless(in_array(HasRolesTrait::class, class_uses_recursive($user)), 501);
        $this->cached();

        $user->load('roles');
        return response()->json($user->roles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        abort_unless(in_array(HasRolesTrait::class, class_uses_recursive($user)), 501);

        $roles = collect($request->validated('roles'));

        $user->roles
            ->whereNotIn('id', $roles)
            ->each(function (Role $role) use ($user) {
                $user->removeRole($role);
            });

        Role::whereIn('id', $roles->diff($user->roles->pluck('id')))
            ->get()
            ->each(function (Role $role) use ($user) {
                $user->addRole($role);
            });

        return response()->json($user->roles()->get());
    }

}