<?php

namespace Padosoft\Laravel\Notification;

use Padosoft\Laravel\Notification\Commands\CreateNotification;
use Padosoft\Laravel\Notification\NotificationFacade;

class NotificationServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations')
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('padosoft-notification.php')
        ], 'config');
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateNotification::class,
            ]);
        }

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php',
            'padosoft-notification'
        );
    }
}
