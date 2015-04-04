<?php namespace Paolooo\Test;

use Mockery as m;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\DetectEnvironment;
use Paolooo\LaravelDoctrine\LaravelDoctrineServiceProvider;
use Paolooo\Stub\ConfigStub as Config;

class LaravelDoctrineTest extends TestCase
{
    /** @var Doctrine\ORM\EntityManager */
    protected $em;

    public function setUp()
    {
        parent::setUp();

        $this->app->register(
            'Paolooo\LaravelDoctrine\LaravelDoctrineServiceProvider'
        );

        $this->em = $this->app->make('Doctrine\ORM\EntityManager');
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->em = null;
    }

    /** @test */
    public function should_instantiate_entity_manager()
    {
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $this->em);
    }

    /** @test */
    public function should_instantiate_entity_manager_by_on_method()
    {
        $em = $this->em->on();
        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $em);
    }

    /** @test */
    public function should_be_able_to_select_second_connection()
    {
        $emRead = $this->em->on('read');

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $emRead);

        $params = config('database.connections.mysql');

        $connParams = $emRead->getConnection()->getParams();

        $this->assertTrue(in_array($params['read']['database'], $connParams));
        $this->assertTrue(in_array($params['read']['username'], $connParams));
    }

    /** @test */
    public function should_return_metadata()
    {
        $metadata = $this->em
            ->getMetadataFactory()
            ->getAllMetadata();

        $this->assertCount(1, $metadata);
    }
}
