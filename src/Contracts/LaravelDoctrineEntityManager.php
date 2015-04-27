<?php namespace Paolooo\LaravelDoctrine\Contracts;

interface LaravelDoctrineEntityManager
{
    /**
     * @param string $connection Connection to be used
     * @return Doctrine\ORM\EntityManager
     */
    public function on($key);
}
