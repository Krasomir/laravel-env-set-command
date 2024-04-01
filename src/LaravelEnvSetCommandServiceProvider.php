<?php

namespace Krasomir\LaravelEnvSetCommand;

use Krasomir\LaravelEnvSetCommand\Commands\LaravelEnvSetCommandCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelEnvSetCommandServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-env-set-command')
            ->hasCommand(LaravelEnvSetCommandCommand::class);
    }
}
