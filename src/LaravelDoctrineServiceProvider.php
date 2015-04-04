<?php namespace Paolooo\LaravelDoctrine;

use Illuminate\Support\ServiceProvider;

class LaravelDoctrineServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('Doctrine\ORM\EntityManager', function() {

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

            return new ProxyEntityManager($provider);
        });

        $this->commands([
            'Paolooo\LaravelDoctrine\Console\DoctrineCommand'
        ]);
    }

}
