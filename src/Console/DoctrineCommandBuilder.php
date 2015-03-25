<?php namespace Paolooo\LaravelDoctrine\Console;

use Paolooo\LaravelDoctrine\EntityManagerFactory;
use Doctrine\ORM\Version;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Application;


class DoctrineCommandBuilder
{
    protected $em;
    protected $type;
    protected $factory;
    protected $console;
    protected $helperSet;

    public function __construct()
    {
        $this->factory = EntityManagerFactory::getInstance();
    }

    /**
     * Sets entity manager
     *
     * @param string $type Entity manager key. See laravel config/database.php
     *
     * @return void
     */
    public function setEntityManager($type=null)
    {
        $this->type= $type;
    }


    /**
     * Build Entity Manager using a factory
     *
     * @return void
     */
    public function buildEntityManager()
    {
        $this->em = $this->factory->make($this->type);
    }

    /**
     * Get entity manager instance
     *
     * @return Doctrine\ORM\EntityManger
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Create Doctrine's console application
     *
     * @return void
     */
    public function buildConsole()
    {
        $this->helperSet = ConsoleRunner::createHelperSet($this->em);
        $this->createApplication($this->helperSet);
    }

    /**
     * Create application
     */
    public function createApplication($helperSet)
    {
        $this->console = ConsoleRunner::createApplication($helperSet);
    }

    /**
     * Get Doctrine's console application
     *
     * @return Symfony\Component\Console\Application
     */
    public function getConsole()
    {
        return $this->console;
    }

    public function getHelperSet()
    {
        return $this->helperSet;
    }



}
