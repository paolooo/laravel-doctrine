<?php namespace Paolooo\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
     * @var Paolooo\LaravelDoctrine\Console\DoctrineCommandFactory
     */
    protected $doctrineCommandFactory;

    /**
     * @var array
     */
    protected $argv;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DoctrineCommandFactory $factory)
    {
        $this->sanitizeCommandLineInput();

        $this->doctrineCommandFactory = $factory;
        $this->doctrineCommandFactory->setInput(new ArgvInput($this->argv));

        parent::__construct();
    }

    public function sanitizeCommandLineInput()
    {
        $this->argv = $_SERVER['argv'];

        array_shift($this->argv);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Executing Doctrine CLI...' . PHP_EOL);

        $input = $this->doctrineCommandFactory->getInput();

        $exitCode = $this->doctrineCommandFactory
            ->createConsole()
            ->run($input);
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
