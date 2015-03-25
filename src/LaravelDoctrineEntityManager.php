<?php namespace Paolooo\LaravelDoctrine;

interface LaravelDoctrineEntityManager
{
    /**
     * @param string $connection Connection to be used
     * @return Doctrine\ORM\EntityManager
     */
    public function on($key);
}
