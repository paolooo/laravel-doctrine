<?php namespace Paolooo\Test;

use Mockery as m;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Paolooo\LaravelDoctrine\Console\DoctrineCommand;
use Paolooo\LaravelDoctrine\Console\DoctrineCommandBuilder;


class DoctrineCommandTest extends TestCase
{
    protected $tester;

    public function setUp()
    {
        parent::setUp();

        $this->initCommand(
            new DoctrineCommand(new DoctrineCommandBuilder)
        );
    }

    public function initCommand($command)
    {
        $command->getConsole()->setAutoExit(false);

        $application = new Application;
        $application->add($command);

        $command->setApplication($application);
        $command->setLaravel($this->app);

        $this->tester = new CommandTester($command);
    }

    /**
     * Execute command tester
     *
     * @param array $arguments
     * @param array $options
     * @return void
     */
    public function execute(array $arguments, array $options = [])
    {
        $this->tester->execute([
            'commands' => array_merge(
                ['doctrine'],
                $arguments
            ),
            $options
        ]);
    }


    public function tearDown()
    {
        parent::tearDown();
        $this->tester = null;
    }

    /** @test */
    public function should_execute_doctrine_command()
    {
        $this->execute([]);
        $this->assertContains('Doctrine Command Line Interface', $this->tester->getDisplay());
    }

    /** @test */
    public function should_display_help_for_orminfo_argument()
    {
        $this->execute(['help', 'orm:info']);
        $this->assertContains("Usage:\n orm:info", $this->tester->getDisplay());
    }

    /** @test */
    public function should_accept_options()
    {
        $this->execute(['orm:schema-tool:create', '--dump-sql']);
        $this->assertContains("CREATE TABLE", $this->tester->getDisplay());
    }

    /** @test */
    public function should_accept_em_option()
    {
        $this->execute([
            'orm:schema-tool:create',
            '--dump-sql',
            '--em=read',
        ]) ;

        $this->assertContains("CREATE TABLE", $this->tester->getDisplay());
    }

}
