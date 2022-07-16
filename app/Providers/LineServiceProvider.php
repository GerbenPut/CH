<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LineServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->alias('line-bot', LINEBot::class);
    }

    public function provides(): array
    {
        return [
            LINEBot::class,
        ];
    }
}
