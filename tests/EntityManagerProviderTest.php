<?php namespace Paolooo\Test;

use Mockery as m;
use Doctrine\ORM\EntityManager;
use Paolooo\LaravelDoctrine\EntityManagerProvider;
use Paolooo\LaravelDoctrine\ConfigurationProvider;
use Paolooo\LaravelDoctrine\DriverManagerProvider;
use Paolooo\LaravelDoctrine\CacheFactory;

class EntityManagerProviderTest extends \PHPUnit_Framework_TestCase
{
    use VarTrait;

    public function tearDown()
    {
        m::close();
    }

    public function provider()
    {
        $cache = CacheFactory::make('array');
        $dbParams = $this->getDbParams();
        $params = $this->getDoctrineParams();

        return [
            [$cache, $dbParams, $params]
        ];
    }

    /** @test */
    public function should_get_single_instance()
    {
        $provider = EntityManagerProvider::getInstance();
        $provider2 = EntityManagerProvider::getInstance();

        $this->assertInstanceOf(
            'Paolooo\LaravelDoctrine\EntityManagerProvider',
            $provider
        );
        $this->assertEquals($provider, $provider2);
    }

    // /** @test */
    // public function should_throw_runtime_error_if_driver_connection_is_not_set()
    // {
    //     $this->setExpectedException('\RuntimeException', '/\$driver/');
    //     $provider = EntityManagerProvider::getInstance();
    //
    //     $ref = new \ReflectionProperty($provider, 'driver');
    //     $ref->setAccessible(true);
    //     $ref->setValue($provider, null);
    //
    //     $provider->create();
    // }

    // /** @test */
    // public function should_throw_runtime_error_if_configuration_is_not_set()
    // {
    //     $this->setExpectedException('\RuntimeException', '/\$configuration/');
    //
    //     $driver = m::mock('Paolooo\LaravelDoctrine\DriverManagerProvider');
    //
    //     $provider = EntityManagerProvider::getInstance();
    //     $provider->driver($driver);
    //     $provider->create();
    // }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_create_an_entity_manager($cache, $dbParams, $params)
    {
        $driver = new DriverManagerProvider($dbParams);

        $configuration = new ConfigurationProvider($cache, $params);

        $provider = EntityManagerProvider::getInstance();
        $provider->driver($driver);
        $provider->configuration($configuration);
        $em = $provider->create();

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $em);
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_create_read_entity_manager($cache, $dbParams, $params)
    {
        $driver = new DriverManagerProvider($dbParams);

        $configuration = new ConfigurationProvider($cache, $params);

        $provider = EntityManagerProvider::getInstance();
        $provider->driver($driver);
        $provider->configuration($configuration);
        $em = $provider->create('read');

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $em);
        $this->assertTrue(in_array(
            $dbParams['read']['database'],
            $em->getConnection()->getParams()
        ));

    }
}
