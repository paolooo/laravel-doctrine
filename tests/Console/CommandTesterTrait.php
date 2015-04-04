<?php namespace Paolooo\Test\Console;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;

trait CommandTesterTrait
{
    public $tester;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->tester = null;
    }

    public function registerCommand($command)
    {
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
            'command' => 'doctrine',
            'commands' => $arguments,
            $options
        ]);
    }
}
