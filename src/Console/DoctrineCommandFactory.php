<?php namespace Paolooo\LaravelDoctrine\Console;

use Doctrine\ORM\Version;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Application as ConsoleApplication;

class DoctrineCommandFactory
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var Doctrine\ORM\Tools\Console\ConsoleRunner
     */
    protected $consoleRunner;

    /**
     * @var array
     */
    private $input;

    public function __construct(EntityManager $entityManager, ConsoleRunner $consoleRunner)
    {
        $this->entityManager = $entityManager;
        $this->consoleRunner = $consoleRunner;
    }

    /**
     * @return Symfony\Component\Console\Helper\HelperSet
     */
    protected function createHelper()
    {
        return $this->consoleRunner->createHelperSet($this->entityManager);
    }

    /**
     * Setup doctrine console
     *
     * @param Symfony\Component\Console\Application $cli Console
     * @return ConsoleApplication
     */
    public function createConsole(ConsoleApplication $cli = null)
    {
        $console = $cli ?: new ConsoleApplication(
            'Doctrine Command Line Interface',
            Version::VERSION
        );

        $helperSet = $this->createHelper();

        $console->setHelperSet($helperSet);
        $console->setCatchExceptions(true);

        $this->consoleRunner->addCommands($console);

        return $console;
    }

    /**
     * Set Argv Input
     *
     * @param Symfony\Component\Console\Input\ArgvInput $input
     * @return Symfony\Component\Console\Input\ArgvInput
     */
    public function setInput(ArgvInput $input)
    {
        $this->input = $input;
    }

    /**
     * @return Symfony\Component\Console\Input\ArgvInput
      */
    public function getInput()
    {
        return $this->input;
    }

}
