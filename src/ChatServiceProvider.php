<?php

namespace Sunarc\LaravelChat;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Sunarc\LaravelChat\Console\LaravelChatInstallCommand;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadSupportFiles();
        $this->registerCommands();

        if ($this->app instanceof \Illuminate\Foundation\Application && $this->app->runningInConsole()) {
            $this->getPublisher();
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->getConfigFile(),
            'LaravelChat'
        );
        $this->app->singleton('LaravelChat', function () {
            return new LaravelChat;
        });

    }

    /**
     * Register the package's artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            LaravelChatInstallCommand::class,
        ]);
    }

    /**
     * @return string
     */
    protected function getConfigFile(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'chat.php';
    }

    /**
     * Register Publishing Files
     *
     * @return void
     */
    protected function getPublisher(): void
    {
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'dist' => public_path('vendor/laravel-chat'),
        ], 'assets');

        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . 'Events' => app_path('Events'),
        ], 'events');

        $this->publishes([
            $this->getConfigFile() => config_path('chat.php'),
        ], 'config');
    }

    /**
     * Register Files To Load
     *
     * @return void
     */

    public function loadSupportFiles(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-chat');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
