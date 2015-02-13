<?php namespace Paolooo\LaravelDoctrine;

use Illuminate\Console\Command;
use Doctrine\ORM\Version;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Application as ConsoleApplication;

class DoctrineCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'doctrine';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run doctrine commmands';

    /**
     * Entity Manager
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Console Runner
     *
     * @var ConsoleRunner
     */
    protected $consoleRunner;

    /** @var array */
    protected $argv;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
    public function __construct(EntityManager $entityManager, ConsoleRunner $consoleRunner)
    {
        $this->entityManager = $entityManager;
        $this->consoleRunner = $consoleRunner;

        $this->argv = $_SERVER['argv'];
        array_shift($this->argv);

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->info('Executing Doctrine CLI...' . PHP_EOL);
        $helperSet = $this->consoleRunner->createHelperSet($this->entityManager);

        $input = new ArgvInput($this->argv);

        $cli = new ConsoleApplication('Doctrine Command Line Interface', Version::VERSION);
        $cli->setCatchExceptions(true);
        $cli->setHelperSet($helperSet);
        $this->consoleRunner->addCommands($cli);
        $cli->run($input);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            ['commands', InputArgument::IS_ARRAY, 'Doctrine commands']
		];
	}

	/**
     * Get the console command options.
     *
     * You'll see these commands when running the following command line:
     *
     * $ php vendor/bin/doctrine help <command_name>
     * or
     * $ php vendor/bin/doctrine help orm:info
	 *
	 * @return array
	 */
	protected function getOptions()
	{
        $options = array_filter($this->argv, [$this, 'parseOption']);

        return array_map([$this, 'allowOption'], $options);
	}

    /**
     * Return all options (-[-name]) found in the command line arg, example,
     * `$ ... --dump-sql -v`
     *
     * @param
     * @return string
     */
    private function parseOption($token)
    {
        if (strpos($token, '--') !== false && $token !== '--') {
            return substr($token, 2);
        } elseif (strpos($token, '-') !== false && $token !== '-') {
            return substr($token, 1);
        }
    }

    /**
     * Generate config to allow options
     *
     * @param string $option Option name
     * @return array
     */
    private function allowOption($option)
    {
        return [$option, null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, null, null];
    }

}
