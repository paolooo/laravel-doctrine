<?php namespace Paolooo\Test;

use Paolooo\LaravelDoctrine\Console\DoctrineCommandBuilder;

class DoctrineCommandBuilderTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        parent::setUp();

        $this->builder = new DoctrineCommandBuilder;
    }


    /** @test */
    public function should_instantiate()
    {
        $this->assertInstanceOf(
            'Paolooo\LaravelDoctrine\Console\DoctrineCommandBuilder',
            $this->builder
        );
    }

    /** @test */
    public function should_set_entity_manager()
    {
        $this->builder->setEntityManager();

        $type = \PHPUnit_Framework_Assert::readAttribute(
            $this->builder,
            'type'
        );

        $this->assertEquals(null, $type);
    }

    /** @test */
    public function should_build_entity_manager()
    {
        $this->builder->setEntityManager('default');
        $this->builder->buildEntityManager();
        $em = $this->builder->getEntityManager();

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $em);
    }

    /** @test */
    public function should_build_console()
    {
        $this->builder->buildEntityManager();
        $this->builder->buildConsole();

        $console = $this->builder->getConsole();

        $this->assertInstanceOf(
            'Symfony\Component\Console\Application',
            $console
        );

    }
}
