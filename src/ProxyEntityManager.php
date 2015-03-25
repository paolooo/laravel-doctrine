<?php namespace Paolooo\LaravelDoctrine;

use Doctrine\ORM\EntityManager;

class ProxyEntityManager extends EntityManager implements LaravelDoctrineEntityManager
{
    protected $factory;

    public function __construct()
    {
        $this->factory = EntityManagerFactory::getInstance();

        $dbalConnection = $this->factory->getConnection('default');

        parent::__construct(
            $dbalConnection,
            $this->factory->getConfiguration(),
            $dbalConnection->getEventManager()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function on($key='')
    {
        return $this->factory->make($key);
    }
}
