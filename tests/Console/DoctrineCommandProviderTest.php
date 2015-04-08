<?php namespace Paolooo\Test\Console;

class DoctrineCommandProviderTest extends CommandTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function should_instantiate()
    {
        $commandProvider = $this->getCommandProvider();

        $this->assertInstanceOf(
            'Paolooo\LaravelDoctrine\Console\DoctrineCommandProvider',
            $commandProvider
        );

        return $commandProvider;
    }

    /**
     * @depends should_instantiate
     * @test
     */
    public function should_get_console($commandProvider)
    {
        $console = $commandProvider->console();

        $this->assertInstanceOf(
            'Symfony\Component\Console\Application',
            $console
        );
    }

    /**
     * @depends should_instantiate
     * @test
     */
    public function should_get_read_console($commandProvider)
    {
        $console = $commandProvider->buildConsole('read');

        $this->assertInstanceOf(
            'Symfony\Component\Console\Application',
            $console
        );

        return $console;
    }

}
