# fredbradley/filesystem-health-check

A Spatie Laravel Health Check that checks it can connect to your Laravel Filesystems

## Instructions

First, ensure that [Spatie Laravel Health](https://spatie.be/docs/laravel-health/v1/introduction) is set up and working as expected on your instance. [Documentation can be found here](https://spatie.be/docs/laravel-health/v1/introduction).

Then install this package:
```
composer require fredbradley/filesystem-health-check
```

## Usage
To use this check you need to add it to your `Health::checks()` method. It will by default, assume that it should have full read/write access, and places a dummy file in the root of the filesystem and reads it back, then removes it. If you have a filesystem that you only read from, and don't have write access that's what the `readOnly()` method is for. 

Examples:
```php
use FredBradley\FilesystemHealthCheck\Checks\FilesystemHealthCheck;

Health::checks([
    FilesystemHealthCheck::new()->disk('sftp')->name('Check SFTP'), // checks read and write
    FilesystemHealthCheck::new()->disk('ext01')->readOnly()->name('Check EXT01 Read Only'), // checks read only
]);
```

## Contribution
You're very welcome to submit PRs. 
