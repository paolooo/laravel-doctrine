<?php namespace Paolooo\LaravelDoctrine;

use Illuminate\Support\ServiceProvider;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\DBAL\DriverManager;

class LaravelDoctrineServiceProvider extends ServiceProvider
{
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
            return new ProxyEntityManager;
        });

        $this->commands([
            'Paolooo\LaravelDoctrine\Console\DoctrineCommand'
        ]);
    }

}
