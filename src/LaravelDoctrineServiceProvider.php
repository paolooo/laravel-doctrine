<?php namespace Paolooo\LaravelDoctrine;

use Illuminate\Support\ServiceProvider;
use Paolooo\LaravelDoctrine\Providers\DriverManagerProvider;
use Paolooo\LaravelDoctrine\Providers\ConfigurationProvider;
use Paolooo\LaravelDoctrine\Providers\EntityManagerProvider;

class LaravelDoctrineServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @val bool
     */
    protected $defer = true;

    /**
     * Bootstrap the event Application
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/doctrine.php' => config_path('doctrine.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /**
         * @param Application $app Laravel Application
         * @param array $params Connection to be used
         *
         * @return EntityManager
         */
        $this->app->singleton('Doctrine\ORM\EntityManager', function($app) {

            $provider = $app->make('Paolooo\LaravelDoctrine\Providers\EntityManagerProvider');

            return new ProxyEntityManager($provider);
        });


        /**
         * Provides a config for EntityManager
         *
         * @return EntityManagerProvider
         */
        $this->app->singleton('Paolooo\LaravelDoctrine\Providers\EntityManagerProvider', function() {

            // Cache
            $cacheType = config('cache.default');
            $path = config("cache.stores.{$cacheType}.path");
            $cache = CacheFactory::make($cacheType, $path);

            // DB Driver
            $dbType = config('database.default');
            $dbParams = config("database.connections.{$dbType}");

            $driver = new DriverManagerProvider($dbParams);

            // Doctrin Params
            $params = config('doctrine');

            $configuration = new ConfigurationProvider($cache, $params);

            $provider = EntityManagerProvider::getInstance();
            $provider->driver($driver);
            $provider->configuration($configuration);

            return $provider;
        });

        $this->commands([
            'Paolooo\LaravelDoctrine\Console\DoctrineCommand'
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Doctrine\ORM\EntityManager',
            'Paolooo\LaravelDoctrine\Providers\EntityManagerProvider'
        ];
    }

}
