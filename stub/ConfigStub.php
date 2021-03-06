<?php namespace Paolooo\Stub;

class ConfigStub
{
    public function get($path)
    {
        switch($path)
        {
            case 'cache.default':
                $config = 'file';
                break;

            case 'database.default':
                $config = 'mysql';
                break;

            case 'database.connections.mysql':
                $config = [
                    'read' => [
                        'database' => env('READ_DB_DATABASE', 'cqrs_read_db'),
                        'username' => 'paolo'
                    ],
                    'driver' => 'pdo_sqlite',
                    'username' => env('DB_USERNAME', 'root'),
                    'password' => env('DB_PASSWORD', ''),
                    'database' => env('DB_DATABASE', 'forge'),
                    'host' => env('DB_HOST', 'localhost'),
                ];
                break;

            case 'database.connections':
                $config = 'mysql';
                break;

            case 'cache.stores.file.path':
                $config = __DIR__ . '/../stub/storage';
                break;

            case 'doctrine':
                $config = [
                    'mappingDir' => 'examples/Acme/Domain/Model/Entity',
                    'proxyDir'   => 'examples/Acme/Domain/Model/Entity/Proxy',
                    'proxyNS'    => 'Acme\Domain\Model\Entity\Proxy',
                    'proxyAutogenerated' => false,

                    'read' => [
                        'mappingDir' => 'examples/Acme/Read/Entity',
                        'proxyDir'   => 'examples/Acme/Read/Entity/Proxy',
                        'proxyNS'    => 'Acme\Read\Entity\Proxy',
                        'proxyAutogenerated' => false,
                    ],

                    'eventstore' => [
                        'mappingDir' => 'examples/Acme/EventStore/Entity',
                        'proxyDir'   => 'examples/Acme/EventStore/Entity/Proxy',
                        'proxyNS'    => 'Acme\EventStore\Entity\Proxy',
                        'proxyAutogenerated' => false,
                    ]
                ];
                break;

            default:
                $config = '';
        }


        return $config;
    }
}
