<?php namespace Paolooo\LaravelDoctrine;

interface EntityManager
{
    /**
     * @param string $connection Connection to be used
     * @return Doctrine\ORM\EntityManager
     */
    public function on($key);
}
