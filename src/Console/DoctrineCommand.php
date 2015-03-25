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
     * @var DoctrineCommandBuilder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $argv;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DoctrineCommandBuilder $builder)
    {
        $this->setAndSanitizeInput($_SERVER['argv']);

        $this->build($builder);

        parent::__construct();
    }

    public function build($builder)
    {
        $this->builder = $builder;
        $this->builder->setEntityManager('read');
        $this->builder->buildEntityManager();
        $this->builder->buildConsole();

        $this->builder->getConsole();
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Executing Doctrine CLI...' . PHP_EOL);

        $input = $this->input->getArgument('commands');

        $em = null;
        $input = array_filter($input, function($v) use (&$em) {
            if (strpos($v, '--em') !== false) {
                $em = $v;
                return false;
            }
            return true;
        });

        if (!empty($em)) {
            list($tmp, $conn) = explode('=', $em);
        }

        $input = new ArgvInput($input);
        $input->bind($this->getDefinition());

        if (!empty($conn)) {
            $this->builder->setEntityManager('read');
            $this->builder->buildEntityManager();

            $helperSet = $this->builder->getHelperSet();

            $cli = $this->getConsole();
            $cli->setHelperSet($helperSet);
        } else {
            $cli = $this->getConsole();
        }

        $cli->run($input, $this->getOutput());
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

    /**
     * Get console Application
     *
     * @return Symfony\Component\Console\Application
     */
    public function getConsole()
    {
        return $this->builder->getConsole();
    }

}
