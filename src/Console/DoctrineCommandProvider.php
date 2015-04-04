<?php namespace Paolooo\LaravelDoctrine\Console;

use Paolooo\LaravelDoctrine\ProxyEntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;

class DoctrineCommandProvider
{
    public $proxyEntityManager;
    protected $console;

    public function __construct(ProxyEntityManager $proxyEntityManager)
    {
        $this->proxyEntityManager = $proxyEntityManager;

        $this->buildConsole();
    }

    /**
     * Create Doctrine's console application
     *
     * @return void
     */
    public function buildConsole($key=null)
    {
        $em = $this->proxyEntityManager->on($key);

        $helperSet = ConsoleRunner::createHelperSet($em);

        return $this->console = ConsoleRunner::createApplication($helperSet);
    }

    /**
     * Get Doctrine's console application
     *
     * @return Symfony\Component\Console\Application
     */
    public function console()
    {
        return $this->console;
    }
}
