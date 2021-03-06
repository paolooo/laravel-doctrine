<?php namespace Paolooo\Test\Console;

use Mockery as m;
use Paolooo\Test\TestCase as TestCase;
use Paolooo\LaravelDoctrine\CacheFactory;
use Paolooo\LaravelDoctrine\Providers\DriverManagerProvider;
use Paolooo\LaravelDoctrine\Providers\ConfigurationProvider;
use Paolooo\LaravelDoctrine\Providers\EntityManagerProvider;
use Paolooo\LaravelDoctrine\ProxyEntityManager;
use Paolooo\LaravelDoctrine\Console\DoctrineCommandProvider;
use Paolooo\Test\VarTrait;

abstract class CommandTestCase extends TestCase
{
    use VarTrait;

    public function tearDown()
    {
        m::close();
    }

    public function getCommandProvider()
    {
        $cache = CacheFactory::make('array');

        $dbParams = $this->getDbParams();
        $params = $this->getDoctrineParams();

        $driver = new DriverManagerProvider($dbParams);
        $configuration = new ConfigurationProvider($cache, $params);

        $emProvider = EntityManagerProvider::getInstance();
        $emProvider->driver($driver);
        $emProvider->configuration($configuration);

        $emProxy = new ProxyEntityManager($emProvider);
        $commandProvider = new DoctrineCommandProvider($emProxy);

        return $commandProvider;
    }
}
