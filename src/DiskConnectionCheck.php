<?php

namespace FredBradley\FilesystemHealthCheck;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Illuminate\Support\Facades\Storage;

class DiskConnectionCheck extends Check
{
    protected string $diskName = 'default';
    protected bool $expectsWritable = true;

    public function disk(string $diskName): static
    {
        $this->diskName = $diskName;

        return $this;
    }

    public function readOnly(): static
    {
        $this->expectsWritable = false;

        return $this;
    }

    public function run(): Result
    {
        $result = Result::make();

        try {
            $disk = Storage::disk($this->diskName);

            if ($this->expectsWritable) {
                $path = '.health-check-'.str()->random(8).'.txt';

                $disk->put($path, 'health-check-'.now()->toIso8601String());

                if (!$disk->exists($path)) {
                    return $result->failed("Write succeeded but file not found on disk [{$this->diskName}]");
                }

                $disk->delete($path);

                return $result->ok("Disk [{$this->diskName}] is reachable and writable");
            }

            // Read-only check — just verify we can list the root
            $disk->directories('/');

            return $result->ok("Disk [{$this->diskName}] is reachable");

        } catch (\Exception $e) {
            return $result->failed("Disk [{$this->diskName}] failed: ".$e->getMessage());
        }
    }
}
