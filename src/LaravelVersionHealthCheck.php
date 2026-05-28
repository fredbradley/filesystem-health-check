<?php

namespace FredBradley\FilesystemHealthCheck;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class LaravelVersionHealthCheck extends Check
{
    public function run(): Result
    {
        $result = Result::make();

        $result->shortSummary('TEST');

        return $result->ok();
    }
}
