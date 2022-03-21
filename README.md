![image](https://user-images.githubusercontent.com/45708520/152330764-01e40664-832a-47fd-8141-d2249e2e36a3.png)
# Laravel Chat System 💯
Pre-build Laravel chat package. You can use this package to create a chat/messaging Laravel application or you can use the pre-build front-end part to kick-start your project.


[![Packagist License][badge_license]](LICENSE) [![For PHP][badge_php]][link-github-repo] ![Scrutinizer Code Quality][badge_quality] [![Github Issues][badge_issues]][link-github-issues] ![Github Stars][badge_stars] ![Github Forks][badge_forks] [![Packagist][badge_package]][link-packagist] [![Packagist Release][badge_release]][link-packagist] [![Packagist Downloads][badge_downloads]][link-packagist]

Pre-build Laravel chat package. You can use this package to create a chat/messaging Laravel application or you can use the pre-build front-end part to kick-start your project.
## Installation

Via Composer

```bash
composer require sunarctech/laravel-chat
```
then
```
php artisan chat:install
```

## Instructions
Install this package and add authentication gurad, you can use some package like **Laravel UI**

This package uses **WebSockets** and for that dependent on [beyondcode/laravel-websockets](https://github.com/beyondcode/laravel-websockets)
#### Publish Files

```php
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config" --tag="migrations"
```

## Configurations

#### Make necessary change in order to change file validations.
**chat.php**
```
return [
    'image_validation' => "max:1024|mimes:png,jpeg",
    'video_validation' => "max:102400|mimes:mp4",
    'file_validation' => "max:1024|mimes:txt",
];
```

#### Make necessary change in order to activate the websocket.
**websockets.php**
```php
'enable_client_messages' => 'true',
```

**broadcasting.php**
```php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true,
        'host' => '127.0.0.1',
        'port' => 6001,
        'scheme' => 'http',
    ],
],
```

**Uncomment this provider from app.php, If not present please add this.**
```php

/*
* Application Service Providers...
*/
...
App\Providers\BroadcastServiceProvider::class,
...
```

**.env**

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=myappid
PUSHER_APP_KEY=myappkey
PUSHER_APP_SECRET=myappsecret
```
## Database
Package ships with some migration files, which is required in order to save the messages, defaul driver is used **MySQL**.
#### Migrate
```php
php artisan migrate
```

#### Link Storage
```php
php artisan storage:link
```

## How to use?
In order to use the chat, Fisrt need to start the websocket server Using Command:

```php
php artisan websocket:serve
```

Visit the route, registered with Package.
```php
www.yourdomain.com/chat
```
## Known Error

You might face dependency version mismatch issue at the time of installation, To fix the issue just add **-W**:
```bash
composer require sunarctech/laravel-chat -W
```

## Created by SunArc Technologies

We are the leading Software Development Company providing end-to-end IT services & solutions to our esteemed customers in multiple industries and domains for the past 18+ years? Give us a call.

https://sunarctechnologies.com/ <br>
info@sunarctechnologies.com <br>
+91-8764025209

## :wrench: Supported Versions

Versions supported.

| Version | Laravel Version | PHP Version | Support |
|---- |----|----|----|
| 0.1 | <=7.0 | 7.3 - 8.x | All features |

## License

The MIT Public License. Please see [LICENSE](LICENSE) for more information.
   
[badge_php]:         https://img.shields.io/badge/PHP-7.3%20to%208.x-orange.svg
[badge_issues]:      https://img.shields.io/github/issues/sunarc-technologies/laravel-chat
[badge_release]:     https://badgen.net/packagist/v/sunarctech/laravel-chat
[badge_quality]:     https://img.shields.io/scrutinizer/g/sunarc-technologies/laravel-chat.svg
[badge_downloads]:   https://img.shields.io/packagist/dt/sunarctech/laravel-chat
[badge_package]:     https://img.shields.io/badge/package-sunarctech/excel--import-blue
[badge_license]:     https://img.shields.io/github/license/sunarc-technologies/laravel-chat
[badge_stars]:       https://img.shields.io/github/stars/sunarc-technologies/laravel-chat
[badge_forks]:       https://img.shields.io/github/forks/sunarc-technologies/laravel-chat

[link-author]:        https://github.com/sunarc-technologies
[link-github-repo]:   https://github.com/sunarc-technologies/laravel-chat
[link-github-issues]: https://github.com/sunarc-technologies/laravel-chat/issues
[link-contributors]:  https://github.com/sunarc-technologies/laravel-chat/graphs/contributors
[link-packagist]:     https://packagist.org/packages/sunarctech/laravel-chat
