<?php namespace Paolooo\LaravelDoctrine;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\DBAL\DriverManager;

use PhpDaddy\Contract\Singleton\SingletonTrait;

/**
 * Concrete Factory
 */
class EntityManagerFactory
{
    use SingletonTrait;

    protected $entityManagers;
    protected $driver;
    protected $database;

    protected function __construct()
    {
        $this->entityManagers = [];

        $this->driver(config('database.default'));
        $this->database(config('database.connections.'.$this->driver));
    }

    /**
     * Create new EntityManager if it exists use the existing one.
     *
     * @param string $key Key connection on laravel app/database.php config.
     * You can use 'read', 'write', or any string, that will depends on your
     * config.
     *
     * @return EntityManager
     */
    public function make($key='default')
    {
        if (!array_key_exists($key, $this->entityManagers)) {
            $this->entityManagers[$key] = DoctrineEntityManager::create(
                $this->getConnection($key),
                $this->getConfiguration()
            );
        }

        return $this->entityManagers[$key];
    }

    /**
     * Sets default driver
     *
     * @param string $driver This could be mysql, sqlite, postgresql, and etc
     */
    public function driver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * Set db config
     *
     * @param array $config Laravel database config
     */
    public function database(array $config)
    {
        $this->database = $config;
    }

    /**
     * Get DB conneciton
     */
    public function getConnection($key)
    {
        $dbConfig = array_get($this->database, $key) ?: [];
        $database = array_merge($this->database, $dbConfig);

        $dbParams = [
            'dbname'    => $this->database['database'],
            'user'      => $this->database['username'],
            'password'  => $this->database['password'],
            'host'      => $this->database['host'],
            'driver'    => $this->database['driver']
        ];

        return DriverManager::getConnection($dbParams);
    }

    public function getConfiguration()
    {
        $cache = CacheFactory::make(env('CACHE_DRIVER'));

        $config = new ConfigurationFactory(
            $cache,
            env('DOCTRINE_MAPPING_DIR', app_path()),
            env('DOCTRINE_PROXY_DIR'),
            env('DOCTRINE_PROXY_NAMESPACE', storage_path() . DIRECTORY_SEPARATOR . 'app'),
            env('DOCTRINE_PROXY_AUTOGENERATED')
        );
        $config->setConfig();
        return $config->make();
    }
}
