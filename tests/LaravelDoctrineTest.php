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
    public function should_return_metadata()
    {
        $metadata = $this->em
            ->getMetadataFactory()
            ->getAllMetadata();

        $this->assertCount(1, $metadata);
    }

}
