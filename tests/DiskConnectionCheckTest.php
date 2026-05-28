<?php

use FredBradley\FilesystemHealthCheck\DiskConnectionCheck;
use Illuminate\Support\Facades\Storage;

it('returns ok when the default writable disk is reachable', function () {
    Storage::fake('default');

    $result = DiskConnectionCheck::new()->run();

    expect($result->status->value)->toBe('ok')
        ->and($result->notificationMessage)->toBe('Disk [default] is reachable and writable');
});

it('returns ok when a named writable disk is reachable', function () {
    Storage::fake('s3');

    $result = DiskConnectionCheck::new()->disk('s3')->run();

    expect($result->status->value)->toBe('ok')
        ->and($result->notificationMessage)->toBe('Disk [s3] is reachable and writable');
});

it('returns ok when a read-only disk is reachable', function () {
    Storage::fake('local');

    $result = DiskConnectionCheck::new()->disk('local')->readOnly()->run();

    expect($result->status->value)->toBe('ok')
        ->and($result->notificationMessage)->toBe('Disk [local] is reachable');
});

it('disk() returns the same instance for fluent chaining', function () {
    $check = DiskConnectionCheck::new();

    expect($check->disk('local'))->toBe($check);
});

it('readOnly() returns the same instance for fluent chaining', function () {
    $check = DiskConnectionCheck::new();

    expect($check->readOnly())->toBe($check);
});

it('returns failed when an exception is thrown during a writable check', function () {
    Storage::shouldReceive('disk')
        ->with('broken')
        ->andThrow(new Exception('Connection refused'));

    $result = DiskConnectionCheck::new()->disk('broken')->run();

    expect($result->status->value)->toBe('failed')
        ->and($result->notificationMessage)->toBe('Disk [broken] failed: Connection refused');
});

it('returns failed when an exception is thrown during a read-only check', function () {
    Storage::shouldReceive('disk')
        ->with('broken')
        ->andThrow(new Exception('Connection refused'));

    $result = DiskConnectionCheck::new()->disk('broken')->readOnly()->run();

    expect($result->status->value)->toBe('failed')
        ->and($result->notificationMessage)->toBe('Disk [broken] failed: Connection refused');
});

it('returns failed when a file cannot be found after a successful write', function () {
    $mockDisk = Mockery::mock();
    $mockDisk->shouldReceive('put')->once()->andReturn(true);
    $mockDisk->shouldReceive('exists')->once()->andReturn(false);

    Storage::shouldReceive('disk')
        ->with('local')
        ->andReturn($mockDisk);

    $result = DiskConnectionCheck::new()->disk('local')->run();

    expect($result->status->value)->toBe('failed')
        ->and($result->notificationMessage)->toBe('Write succeeded but file not found on disk [local]');
});
