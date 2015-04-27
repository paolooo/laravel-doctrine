<?php namespace Paolooo\LaravelDoctrine;

use Doctrine\ORM\EntityManager;
use Paolooo\LaravelDoctrine\Contracts\LaravelDoctrineEntityManager;
use Paolooo\LaravelDoctrine\Providers\EntityManagerProvider;

class ProxyEntityManager extends EntityManager implements LaravelDoctrineEntityManager
{
    protected $provider;

    public function __construct(EntityManagerProvider $provider)
    {
        $this->provider = $provider;

        $connection = $provider->getDriver()->connection();
        $eventManager = $connection->getEventManager();
        $config = $provider->getConfiguration()->config();

        parent::__construct($connection, $config, $eventManager);
    }

    /**
     * {@inheritdoc}
     */
    public function on($key=null)
    {
        $key = (empty($key)) ? 'default' : $key;

        return $this->provider->create($key);
    }
}
