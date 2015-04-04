<?php namespace Paolooo\Test;

use Mockery as m;
use Paolooo\LaravelDoctrine\EntityManagerProvider;
use Paolooo\LaravelDoctrine\DriverManagerProvider;
use Paolooo\LaravelDoctrine\ConfigurationProvider;
use Paolooo\LaravelDoctrine\ProxyEntityManager;
use Paolooo\LaravelDoctrine\CacheFactory;

class ProxyEntityManagerTest extends \PHPUnit_Framework_TestCase
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

        $driver = new DriverManagerProvider($dbParams);

        $configuration = new ConfigurationProvider($cache, $params);

        return [
            [$driver, $configuration, $dbParams, $params, $cache]
        ];
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_instantiate($driver, $configuration, $dbParams, $params, $cache)
    {
        $provider = EntityManagerProvider::getInstance();
        $provider->driver($driver);
        $provider->configuration($configuration);

        $em = new ProxyEntityManager($provider);

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $em);
        $this->assertTrue(in_array(
            $dbParams['database'],
            $em->getConnection()->getParams()
        ));
    }

    /**
     * @dataProvider provider
     * @test
     */
   public function should_create_new_instance_with_method_on($driver, $configuration, $dbParams)
   {
        $provider = EntityManagerProvider::getInstance();
        $em = new ProxyEntityManager($provider);
        $emRead = $em->on('read');

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $emRead);
        $this->assertTrue(in_array(
            $dbParams['read']['database'],
            $emRead->getConnection()->getParams()
        ));

        $this->assertEquals(
            $dbParams['read']['username'],
            $emRead->getConnection()->getUsername()
        );
   }

}
