<?php namespace Paolooo\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\NullOutput;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class DoctrineCommand extends Command
{
    private static $ignore_options = [
        'help',
        'verbose',
        'version',
        'ansi',
        'no-ansi',
        'no-interaction',
        'env',
    ];

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
     * @var array
     */
    protected $argv;

    /**
     * @var DoctrineCommandProvider
     */
    protected $commandProvider;

    /**
     * @var boolean
     */
    private $autoExit;

    /**
     * Entity Manager Key
     * @var string
     */
    private $key;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DoctrineCommandProvider $provider)
    {
        $this->setAndSanitizeInput($_SERVER['argv']);
        $this->autoExit(true);

        $this->commandProvider = $provider;

        parent::__construct();
    }

    /**
     * @param $input array $_SERVER['argv']
     *
     * @return void
     */
    public function setAndSanitizeInput($input)
    {
        $this->argv = $input;

        array_shift($this->argv);
    }

    /**
     * @param boolean $isExit
     */
    public function autoExit($isExit)
    {
        $this->autoExit = $isExit;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Executing Doctrine CLI...' . PHP_EOL);

        $input = $this->input->getArgument('commands');

        $this->setInputArgs($input);

        $this->getConsole($this->key)->run(
            $input, $this->getOutput()
        );
    }

    /**
     * Filter and set input for doctrine command line
     *
     * @param array $input
     */
    public function setInputArgs(&$input)
    {
        array_unshift($input, 'doctrine');

        $this->key = $this->getEntityManagerKey($input);

        $input = new ArgvInput($input);
        $input->bind($this->getDefinition());

        return $input;
    }

    /**
     * Get key entity manager
     *
     * @param array $input Command line input
     * @return string
     */
    public function getEntityManagerKey(&$input)
    {
        $em = null;
        $input = array_filter($input, function($v) use (&$em) {
            if (strpos($v, '--em') !== false) {
                $em = $v;
                return false;
            }
            return true;
        });

        $key = null;
        if (!empty($em)) {
            list($tmp, $key) = preg_split('/=|\s/', $em);
        }

        return $key;
    }

    /**
     * Build console new console app if key exists
     *
     * @param string $key
     *
     * @return Symfony\Component\Console\Application
     */
    public function getConsole($key)
    {
        if (!empty($key)) {
            $this->commandProvider->buildConsole($key);
        }

        $cli = $this->commandProvider->console();
        $cli->setAutoExit($this->autoExit);

        return $cli;
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

    /**]]
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
        $options = array_filter(
            array_map([$this, 'parseOption'], $this->argv),
            'strlen'
        );
        $options = array_map([$this, 'allowOption'], $options);

        $options[] = $this->allowOption('dump-sql');
        $options[] = $this->allowOption('em');

        return $options;
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
        $option = null;

        if (strpos($token, '--') === 0 && $token !== '--') {
            $option = substr($token, 2);
        } elseif (strpos($token, '-') === 0 && $token !== '-') {
            $option = substr($token, 1);
        }

        if (($n = strpos($option, '=')) !== false) {
            $option = substr($option, 0, $n);
        }

        if (in_array($option, self::$ignore_options)) {
            return null;
        }

        return $option;
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
