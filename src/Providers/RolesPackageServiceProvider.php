<?php

namespace KieranFYI\Roles\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use KieranFYI\Roles\Listeners\RegisterUserInfoListener;
use KieranFYI\UserUI\Events\RegisterUserInfoEvent;

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

        $this->publishes([
            $root . '/public' => public_path('vendor/kieranfyi/roles'),
        ], ['laravel-assets']);

        $this->loadViewsFrom($root . '/resources/views', 'roles');
        $this->loadRoutesFrom($root . '/routes/web.php');

        Event::listen(RegisterUserInfoEvent::class, RegisterUserInfoListener::class);

    }
}
