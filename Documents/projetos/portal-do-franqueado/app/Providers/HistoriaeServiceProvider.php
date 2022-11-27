<?php

namespace App\Providers;

use Event;
use App\Observers\ChangesObserver;
use App\Listeners\LogAccessListener;
use Illuminate\Support\ServiceProvider;

class HistoriaeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (($this->getConfig('models') ? $this->getConfig('models') : []) as $model) {
            $model::observe(ChangesObserver::class);
        }

        Event::listen('kernel.handled', LogAccessListener::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @param   string  $name
     * @return  string|null
     */
    public function getConfig($name)
    {
        return $this->app['config']["historiae.{$name}"];
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
