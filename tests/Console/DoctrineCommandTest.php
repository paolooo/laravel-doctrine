<?php namespace Paolooo\Test\Console;

use Paolooo\LaravelDoctrine\Console\DoctrineCommand;

class DoctrineCommandTest extends CommandTestCase
{
    use CommandTesterTrait;

    /** @test */
    public function should_instantiate()
    {
        $commandProvider = $this->getCommandProvider();
        $commandProvider->console()->setAutoExit(false);

        $command = new DoctrineCommand($commandProvider);
        $command->autoExit(false);

        $this->assertInstanceOf(
            'Paolooo\LaravelDoctrine\Console\DoctrineCommand',
            $command
        );

        return $command;
    }

    /**
     * @depends should_instantiate
     * @test
     */
    public function should_filter_options($command)
    {
        $options = [
            'dump-sql' => '',
            'em' => 'read',
            'help' => false,
            'version' => false,
            'verbose' => false,
            'ansi' => false,
        ];

        $filtered = $command->filterOptions($options);

        $this->assertArrayHasKey('dump-sql', $filtered);
        $this->assertArrayHasKey('em', $filtered);
    }

    /**
     * @depends should_instantiate
     * @test
     */
    public function should_transform_option($command)
    {
        $options = [
            'dump-sql' => '',
            'em' => 'read',
        ];

        $transOption = $command->transOption($options);

        $this->assertContains('--dump-sql', $transOption);
        $this->assertContains('--em=read', $transOption);
    }

    // /**
    //  * @depends should_instantiate
    //  * @test
    //  */
    // public function should_execute_doctrine_command($command)
    // {
    //     $this->registerCommand($command);
    //
    //     $this->execute([]);
    //     $this->assertContains('Doctrine Command Line Interface', $this->tester->getDisplay());
    // }

    // /**
    //  * @depends should_instantiate
    //  * @test
    //  */
    // public function should_display_help_for_orminfo_argument($command)
    // {
    //     $this->registerCommand($command);
    //
    //     $this->execute(['help', 'orm:info']);
    //     $this->assertContains("Usage:\n orm:info", $this->tester->getDisplay());
    // }

    /**
     * @depends should_instantiate
     * @test
     */
    public function should_accept_options($command)
    {
        $this->registerCommand($command);

        $this->execute([
                'orm:schema-tool:create',
            ],
            [
                '--dump-sql' => '',
                '--em' => 'read',
        ]);

        $this->assertContains("CREATE TABLE", $this->tester->getDisplay());
    }

    /**
     * @depends should_instantiate
     * @test
     */
    public function should_accept_em_option($command)
    {
        $this->registerCommand($command);

        $this->execute(
            [
                'orm:schema-tool:create',
            ],
            [
                '--dump-sql',
                '--em read',
            ]
        );

        $this->assertContains("CREATE TABLE", $this->tester->getDisplay());
    }

}
