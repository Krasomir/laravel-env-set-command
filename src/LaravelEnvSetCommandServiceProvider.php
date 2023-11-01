<?php

namespace Krasomir\LaravelEnvSetCommand;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Krasomir\LaravelEnvSetCommand\Commands\LaravelEnvSetCommandCommand;

class LaravelEnvSetCommandServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-env-set-command')
            ->hasCommand(LaravelEnvSetCommandCommand::class);
    }
}
