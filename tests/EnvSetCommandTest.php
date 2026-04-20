<?php

use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->envFile = tempnam(sys_get_temp_dir(), 'env_test_');
    file_put_contents($this->envFile, "APP_NAME=Laravel\nAPP_ENV=local\n");
});

afterEach(function () {
    if (file_exists($this->envFile)) {
        unlink($this->envFile);
    }
});

it('changes an existing variable', function () {
    $this->artisan('env:set', [
        'key' => 'APP_NAME',
        'value' => 'Example',
        'env_file' => $this->envFile,
    ])->assertSuccessful();

    expect(file_get_contents($this->envFile))->toContain('APP_NAME=Example');
});

it('sets a new variable', function () {
    $this->artisan('env:set', [
        'key' => 'NEW_KEY',
        'value' => 'newvalue',
        'env_file' => $this->envFile,
    ])->assertSuccessful();

    expect(file_get_contents($this->envFile))->toContain('NEW_KEY=newvalue');
});

it('accepts key=value syntax', function () {
    $this->artisan('env:set', [
        'key' => 'APP_NAME=Example',
        'env_file' => $this->envFile,
    ])->assertSuccessful();

    expect(file_get_contents($this->envFile))->toContain('APP_NAME=Example');
});

it('wraps values with spaces in quotes', function () {
    $this->artisan('env:set', [
        'key' => 'APP_NAME',
        'value' => 'Example App',
        'env_file' => $this->envFile,
    ])->assertSuccessful();

    expect(file_get_contents($this->envFile))->toContain('APP_NAME="Example App"');
});

it('accepts key=value with env file as second argument', function () {
    $this->artisan('env:set', [
        'key' => 'APP_NAME=TestApp',
        'value' => $this->envFile,
    ])->assertSuccessful();

    expect(file_get_contents($this->envFile))->toContain('APP_NAME=TestApp');
});

it('converts key to uppercase', function () {
    $this->artisan('env:set', [
        'key' => 'app_name',
        'value' => 'Example',
        'env_file' => $this->envFile,
    ])->assertSuccessful();

    expect(file_get_contents($this->envFile))->toContain('APP_NAME=Example');
});

it('fails with an invalid key containing special characters', function () {
    $this->artisan('env:set', [
        'key' => '@pp_n@me',
        'value' => 'Laravel',
        'env_file' => $this->envFile,
    ])->assertFailed();
});

it('fails when key contains equals sign as standalone key', function () {
    $this->artisan('env:set', [
        'key' => 'APP=NAME',
        'value' => 'Laravel',
        'env_file' => $this->envFile,
    ])->assertFailed();
});

it('does not change other variables when updating one', function () {
    $this->artisan('env:set', [
        'key' => 'APP_NAME',
        'value' => 'Changed',
        'env_file' => $this->envFile,
    ])->assertSuccessful();

    $content = file_get_contents($this->envFile);
    expect($content)
        ->toContain('APP_NAME=Changed')
        ->toContain('APP_ENV=local');
});

it('uses the default env file path when none is provided', function () {
    $defaultEnvPath = App::environmentFilePath();

    if (! file_exists($defaultEnvPath)) {
        file_put_contents($defaultEnvPath, "APP_NAME=Laravel\n");
    }

    $originalContent = file_get_contents($defaultEnvPath);

    $this->artisan('env:set', [
        'key' => 'APP_NAME',
        'value' => 'TestDefault',
    ])->assertSuccessful();

    file_put_contents($defaultEnvPath, $originalContent);
});
