<?php

use Illuminate\Support\Facades\Route;
use KieranFYI\Admin\Facades\Admin;
use KieranFYI\Roles\Http\Controllers\UserRoleController;

Admin::route()
    ->group(function () {
        Route::patch('users/tabs/{user}/roles', [UserRoleController::class, 'update'])
            ->name('users.tabs.roles.update');
    });