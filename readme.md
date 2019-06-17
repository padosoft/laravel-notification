# Laravel Notification

[![License](https://poser.pugx.org/anlutro/l4-settings/license.svg)](http://opensource.org/licenses/MIT)

System for managing Laravel Notifications via database.


## Installation 
This package can be used in Laravel 5.8 or higher.
For previus version or Laravel please check v 1.* of this package
    
1. `composer require padosoft/laravel-notification`
2. Publish the config file by running `php artisan vendor:publish --provider="Padosoft\Laravel\Notification\NotificationServiceProvider" --tag="migrations"`. 

##Config
If you want you can publish also the config of the package
Publish the config file by running `php artisan vendor:publish --provider="Padosoft\Laravel\Notification\NotificationServiceProvider" --tag="config"`.

## Usage

You can generate a Notification Class with an artisan command and register it into database.

```bash
php artisan notification-manager:create user_is_registered
```
This command will create a App\Notifications\user_is_registered class.
Now you can customize your notification.
To enable the notifications you can put to 1 the active field on the database.
To set the recipients of your notification you have to populate mynotifications_users and mynotifications_roles table.
The you can send your notification like this: 
```php
NotificationManager::dispatch(new \App\Notifications\user_is_registered('prova messaggio','prova'))
```

## Contact

Open an issue on GitHub if you have any problems or suggestions.


## License

The contents of this repository is released under the [MIT license](http://opensource.org/licenses/MIT).