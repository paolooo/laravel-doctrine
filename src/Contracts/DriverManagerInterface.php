<?php namespace Paolooo\LaravelDoctrine\Contracts;

interface DriverManagerInterface
{
    /**
     *  @param string $key Laravel db config key for multiple connection.
     *
     *  @return Doctrine\DBAL\Connection
     */
    public function connection($key);
}
