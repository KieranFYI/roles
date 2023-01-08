<?php

namespace KieranFYI\Roles\Listeners;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use KieranFYI\Roles\Core\Models\Roles\Role;
use AppUser as User;
use KieranFYI\UserUI\Services\RegisterUserComponent;

class RegisterUserInfoListener
{
    /**
     * Handle the event.
     *
     * @param User $user
     * @return RegisterUserComponent
     */
    public function handle(User $user): RegisterUserComponent
    {
        return RegisterUserComponent::create()
            ->view('roles::info', [
                'user' => $user,
                'roles' => Role::orderBy('display_order', 'desc')
                    ->whereHas('permissions', function(Builder $query) {
                        $query->where('power', '>', Auth::user()->power);
                    }, 0)
                    ->get(),
            ]);
    }
}