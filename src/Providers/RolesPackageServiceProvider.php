<?php

namespace KieranFYI\Roles\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use KieranFYI\Roles\Listeners\RegisterUserInfoListener;
use KieranFYI\UserUI\Events\RegisterUserInfoEvent;
use KieranFYI\UserUI\Policies\UserPolicy;

class RolesPackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $root = __DIR__ . '/../..';
        config([
            'ziggy.groups.admins-roles' => ['admin.roles.*'],
            'ziggy.groups.admins-roles-api' => ['admin.api.roles.*', 'admin.api.users.roles.*'],
        ]);
        $this->publishes([
            $root . '/public' => public_path('vendor/kieranfyi/roles'),
        ], ['laravel-assets']);

        $this->loadViewsFrom($root . '/resources/views', 'roles');
        $this->loadRoutesFrom($root . '/routes/web.php');

        class_alias(config('auth.providers.users.model'), 'KieranFYI\Roles\Models\User');
        Gate::policy('KieranFYI\Roles\Models\User', UserPolicy::class);

        Event::listen(RegisterUserInfoEvent::class, RegisterUserInfoListener::class);
    }
}
