<?php

namespace Krasomir\LaravelEnvSetCommand\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Krasomir\LaravelEnvSetCommand\LaravelEnvSetCommandServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Krasomir\\LaravelEnvSetCommand\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelEnvSetCommandServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-env-set-command_table.php.stub';
        $migration->up();
        */
    }
}
