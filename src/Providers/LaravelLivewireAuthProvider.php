<?php

namespace Bastinald\LaravelLivewireAuth\Providers;

use Bastinald\LaravelLivewireAuth\Commands\MakeAuthCommand;
use Illuminate\Support\ServiceProvider;

class LaravelLivewireAuthProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAuthCommand::class,
            ]);
        }
    }
}
