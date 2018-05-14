<?php
/**
 * Copyright (c) Padosoft.com 2018.
 */

namespace Padosoft\Laravel\Notification;

class NotificationFacade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Padosoft\Laravel\Notification\NotificationManager';
    }
}