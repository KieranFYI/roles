<?php

namespace KieranFYI\Roles\Listeners;

use KieranFYI\Roles\Core\Traits\Roles\HasRolesTrait;
use KieranFYI\Roles\Models\User;
use KieranFYI\UserUI\Services\RegisterUserComponent;

class RegisterUserInfoListener
{
    /**
     * Handle the event.
     *
     * @param User $user
     * @return RegisterUserComponent
     */
    public function handle(User $user): ?RegisterUserComponent
    {
        if (!in_array(HasRolesTrait::class, class_uses_recursive($user))) {
            return null;
        }

        return RegisterUserComponent::create()
            ->view('roles::info', [
                'user' => $user,
            ]);
    }
}