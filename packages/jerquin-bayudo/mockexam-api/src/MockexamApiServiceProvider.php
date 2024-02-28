<?php

namespace Jerquin\Mockexam;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class MockexamApiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'jerquin-bayudo');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'jerquin-bayudo');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');
        $this->loadMiddleware();
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {      
        $this->mergeConfigFrom(__DIR__.'/../config/mockexam-api.php', 'mockexam-api');
        // Register the service the package provides.
            config([
            'auth' => File::getRequire(__DIR__ . '/../config/auth.php'),
            'cors' => File::getRequire(__DIR__ . '/../config/cors.php'),
            'permission' => File::getRequire(__DIR__ . '/../config/permission.php'),
        ]);
        $this->app->singleton('mockexam-api', function ($app) {
            return new MockexamApi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mockexam-api'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/mockexam-api.php' => config_path('mockexam-api.php'),
        ], 'mockexam-api.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/jerquin-bayudo'),
        ], 'chatbot-api.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/jerquin-bayudo'),
        ], 'chatbot-api.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/jerquin-bayudo'),
        ], 'chatbot-api.views');*/

        // Registering package commands.
        $this->commands([\Jerquin\Console\InstallCommand::class,]);
    }
        protected $routeMiddleware = [
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    ];
    protected function loadMiddleware(): void
    {
        if (!is_array($this->routeMiddleware) ||  empty($this->routeMiddleware)) {
            return;
        }

        foreach ($this->routeMiddleware as $alias => $middleware) {
            $this->app->router->aliasMiddleware($alias, $middleware);
        };
    }
}
