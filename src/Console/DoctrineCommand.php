<?php namespace Paolooo\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
        $options = $this->input->getOptions();

        $em = $options['em'];
        unset($options['em']);

        $input = $this->setInputArgs($input, $options);

        $this->getConsole($this->key)->run(
            $input, $this->getOutput()
        );
    }

    /**
     * Filter and set input for doctrine command line
     *
     * @param array $input
     */
    public function setInputArgs($input, $options)
    {
        $filteredOptions = $this->filterOptions($options);
        $transOption = $this->transOption($filteredOptions);

        $inputAndOptions = array_merge($input, $transOption);

        array_unshift($inputAndOptions, 'doctrine');

        $argvInput = new ArgvInput($inputAndOptions);
        $argvInput->bind($this->getDefinition());

        return $argvInput;
    }

    /**
     * Remove options that has a default value of false
     * in the array. Only return an option with value of
     * blank or with any value.
     *
     * @param array $options Input options
     * @return array
     */
    public function filterOptions($options)
    {
        return array_filter($options, function($v) {
            if ($v === false) {
                return false;
            }

            return true;
        });
    }

    /**
     * @param array $options
     * @param Input $input
     * @return array
     */
    public function transOption($options)
    {
        $a = [];

        array_walk($options, function($v, $k) use (&$a){

            $opt = $this->input->getOption($k);

            if (!sizeof($opt)) {
                return;
            }

            if ($v === true) {
                return $a[] = "--{$k}";
            }

            if (is_array($v) && empty($v[0])) {
                return $a[] = "--{$k}";
            }


            if (is_array($v) && !empty($v[0])) {
                return $a[] = "--{$k}={$v[0]}";
            }
        });

        return $a;
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
