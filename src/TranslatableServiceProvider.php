<?php

namespace AbbyLynn\Translatable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use AbbyLynn\Translatable\Services\LangFiles;

class TranslatableServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->setupRoutes($this->app->router);
        $this->publishes([
            __DIR__.'/config/translatable.php' => config_path('translatable.php'),
            __DIR__.'/views' => base_path('resources/views/translatable'),
            __DIR__.'/database/migrations/' => database_path('migrations'),
            __DIR__.'/database/seeds/' => database_path('seeds'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslatable();
        config(['config/translatable.php']);
    }


    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'AbbyLynn\Translatable\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

    private function registerTranslatable()
    {
        $this->app->bind('translatable',function($app){
            return new Translatable($app);
        });
    }

}
