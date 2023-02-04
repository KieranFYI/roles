<?php

namespace KieranFYI\Tests\Roles;

use Illuminate\Foundation\Application;
use KieranFYI\Admin\Providers\AdminPackageServiceProvider;
use KieranFYI\Logging\Providers\LoggingPackageServiceProvider;
use KieranFYI\Misc\Providers\MiscPackageServiceProvider;
use KieranFYI\Roles\Core\Providers\RolesCorePackageServiceProvider;
use KieranFYI\Roles\Providers\RolesPackageServiceProvider;
use KieranFYI\Services\Core\Providers\ServicesCorePackageServiceProvider;
use KieranFYI\UserUI\Providers\UserUIPackageServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Load package service provider.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            MiscPackageServiceProvider::class,
            LoggingPackageServiceProvider::class,
            ServicesCorePackageServiceProvider::class,
            RolesCorePackageServiceProvider::class,
            AdminPackageServiceProvider::class,
            UserUIPackageServiceProvider::class,
            RolesPackageServiceProvider::class
        ];
    }
}