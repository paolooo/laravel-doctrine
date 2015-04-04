<?php namespace Paolooo\Test;

use Mockery as m;
use Paolooo\LaravelDoctrine\ConfigurationProvider;
use Paolooo\LaravelDoctrine\CacheFactory;

class ConfigurationProviderTest extends \PHPUnit_Framework_TestCase
{
    use VarTrait;

    public function tearDown()
    {
        m::close();
    }

    public function provider()
    {
        $cache = CacheFactory::make('array');

        $params = $this->getDoctrineParams();

        return [
            [$cache, $params]
        ];
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_create_configuration($cache, $params)
    {
        $configuration = new ConfigurationProvider($cache, $params);
        $config = $configuration->config();

        $this->assertInstanceOf('Doctrine\ORM\Configuration', $config);
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_get_read_config($cache, $params)
    {
        $configuration = new ConfigurationProvider($cache, $params);
        $config = $configuration->create('read');

        $this->assertInstanceOf('Doctrine\ORM\Configuration', $config);
    }


}
