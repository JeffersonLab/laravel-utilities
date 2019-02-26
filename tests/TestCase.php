<?php

namespace Tests;

use Jlab\LaravelUtilities\PackageServiceProvider;


class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return
            [
                PackageServiceProvider::class,
            ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // app.key is needed for creating hashed passwords.
        $app['config']->set('app.key','base64:Ubu0Hrq9F2uecDIVK8sQdqNfs/PlpP4a7JpPXomBav0=');

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }


}