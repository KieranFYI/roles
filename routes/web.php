<?php

use Illuminate\Support\Facades\Route;
use KieranFYI\Admin\Facades\Admin;
use KieranFYI\Roles\Http\Controllers\RoleAPIController;
use KieranFYI\Roles\Http\Controllers\UserRoleAPIController;
use KieranFYI\UserUI\Http\Controllers\UserAPIController;

Admin::route()
    ->group(function () {
        Route::prefix('api')
            ->name('api.')
            ->group(function () {
                Route::post('roles/search', [RoleAPIController::class, 'search'])
                    ->name('roles.search');
                Route::resource('roles', RoleAPIController::class)
                    ->only('index', 'show');
                Route::prefix('users/{user}')
                    ->name('users.')
                    ->group(function () {
                        Route::get('roles', [UserRoleAPIController::class, 'show'])
                            ->name('roles.show');
                        Route::patch('roles', [UserRoleAPIController::class, 'update'])
                            ->name('roles.update');
                    });
            });
    });