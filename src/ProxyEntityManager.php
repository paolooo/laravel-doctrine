<?php namespace Paolooo\LaravelDoctrine;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;

class ProxyEntityManager extends DoctrineEntityManager implements EntityManager
{
    protected $factory;

    public function __construct()
    {
        $this->factory = EntityManagerFactory::getInstance();

        $conn = $this->factory->getConnection('default');

        parent::__construct(
            $conn,
            $this->factory->getConfiguration(),
            $conn->getEventManager()
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
