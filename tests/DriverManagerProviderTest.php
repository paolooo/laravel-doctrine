<?php namespace Paolooo\Test;

use Paolooo\LaravelDoctrine\Contracts\DriverManagerInterface;
use Paolooo\LaravelDoctrine\Providers\DriverManagerProvider;

class DriverManagerProviderTest extends \PHPUnit_Framework_TestCase
{
    use VarTrait;

    public function provider()
    {
        $params = $this->getDbParams();

        return [
            [$params]
        ];
    }


    /** @test */
    public function should_instantiate()
    {
        $driver = new DriverManagerProvider([]);

        $this->assertInstanceOf(
            'Paolooo\LaravelDoctrine\Contracts\DriverManagerInterface',
            $driver
        );
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_get_dbal_connection($dbParams)
    {
        $driver = new DriverManagerProvider($dbParams);
        $connection = $driver->connection();

        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $connection);
        $this->assertTrue(in_array($dbParams['database'], $connection->getParams()));
        $this->assertEquals($dbParams['host'], $connection->getHost());
        $this->assertEquals($dbParams['username'], $connection->getUsername());
        $this->assertEquals($dbParams['password'], $connection->getPassword());
        $this->assertEquals($dbParams['driver'], $connection->getDriver()->getName());
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_get_default_connection($dbParams)
    {
        $driver = new DriverManagerProvider($dbParams);
        $connection = $driver->connection('default');

        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $connection);
        $this->assertTrue(in_array($dbParams['database'], $connection->getParams()));
        $this->assertEquals($dbParams['host'], $connection->getHost());
        $this->assertEquals($dbParams['username'], $connection->getUsername());
        $this->assertEquals($dbParams['password'], $connection->getPassword());
        $this->assertEquals($dbParams['driver'], $connection->getDriver()->getName());
    }

    /**
     * @dataProvider provider
     * @test
     */
    public function should_get_read_connection_config($dbParams)
    {
        $driver = new DriverManagerProvider($dbParams);
        $connection = $driver->connection('read');

        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $connection);
        $this->assertTrue(in_array($dbParams['read']['database'], $connection->getParams()));
        $this->assertEquals($dbParams['host'], $connection->getHost());
        $this->assertEquals($dbParams['read']['username'], $connection->getUsername());
        $this->assertEquals($dbParams['password'], $connection->getPassword());
        $this->assertEquals($dbParams['driver'], $connection->getDriver()->getName());
    }

}
