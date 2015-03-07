<?php namespace Paolooo\Stub;

class ConfigStub
{
    public function get($path)
    {
        switch($path)
        {
            case 'database.default':
                $config = 'mysql';
                break;

            case 'database.connections.mysql':
                $config = [
                    'driver' => env('DB_DRIVER', 'pdo_mysql'),
                    'username' => env('DB_USERNAME', 'root'),
                    'password' => env('DB_PASSWORD', ''),
                    'database' => env('DB_DATABASE', 'forge'),
                    'host' => env('DB_HOST', 'localhost'), ];
                break;

            case 'database.connections':
                $config = 'mysql';
                break;

            case 'cache.stores.file.path':
                $config = __DIR__ . '/../stub/storage';
                break;

            default:
                $config = '';
        }


        return $config;
    }
}
