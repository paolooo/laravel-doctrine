<?php namespace Paolooo\Tests;

use Mockery as m;
use Paolooo\LaravelDoctrine\Console\DoctrineCommand;

class DoctrineCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $em;
    protected $runner;
    protected $doctrineCommandFactory;

    public function setUp()
    {
        $this->em = m::mock('Doctrine\ORM\EntityManager');

        $this->runner = m::mock(
                'Doctrine\ORM\Tools\Console\ConsoleRunner[createHelperSet]'
            )
            ->shouldReceive('createHelperSet')
            ->andReturn(
                m::mock('Symfony\Component\Console\Helper\HelperSet')->makePartial()
            )
            ->getMock();

        $this->doctrineCommandFactory = m::mock(
                'Paolooo\LaravelDoctrine\Console\DoctrineCommandFactory',
                [$this->em, $this->runner]
            )
            ->makePartial();
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function should_be_able_to_instantiate()
    {
        $command = new DoctrineCommand($this->doctrineCommandFactory);

        $this->assertNotEmpty($command);
        $this->assertInstanceOf(
            'Paolooo\LaravelDoctrine\Console\DoctrineCommand',
            $command
        );
    }

    // /** @test */
    // public function should_fire()
    // {
    //     $command = m::mock(
    //             'Paolooo\LaravelDoctrine\Console\DoctrineCommand[info]',
    //             [$this->doctrineCommandFactory]
    //         )
    //         ->shouldReceive('info')
    //         ->with(m::type('string'))
    //         ->getMock();
    //
    //     $command->fire();
    // }
}
