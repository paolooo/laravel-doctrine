<?php namespace Paolooo\LaravelDoctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver\Connection as DriverConnection;

use PhpDaddy\Contract\Singleton\SingletonTrait;

class EntityManagerProvider
{
    use SingletonTrait;

    protected $entityManagers;
    protected $driver;
    protected $configuration;

    protected function __construct()
    {
        $this->entityManagers = [];
    }

    /**
     * Set DB driver Connection
     *
     * @param DriverManagerProvider
     * @return void
     */
    public function driver(DriverManagerProvider $driver)
    {
        $this->driver= $driver;
    }

    /**
     * Set DB Configuration
     *
     * @param Doctrine\DBAL\Configuration $configuration
     * @return void
     */
    public function configuration(ConfigurationProvider $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Create new EntityManager if it exists use the existing one.
     *
     * @param string $key Key connection on laravel app/database.php config.
     *                    You can use 'read', 'write', or any string that
     *                    depends on your config.
     *
     * @return Doctrine\ORM\EntityManager
     */
    public function create($key=null)
    {
        $key = (empty($key)) ? 'default' : $key;

        if (empty($this->driver) ) {
            throw new \RuntimeException("Missing \$driver. Please set db driver driver thru `\$this->driver(\$driver).`");
        }

        if (empty($this->configuration) ) {
            throw new \RuntimeException("Missing \$configuration. Please set db configuration thru `\$this->configuration(\$configuration).`");
        }

        if (!array_key_exists($key, $this->entityManagers)) {
            $this->entityManagers[$key] = EntityManager::create(
                $this->driver->connection($key),
                $this->configuration->create($key)
            );
        }

        return $this->entityManagers[$key];
    }

    /**
     * @return DriverManagerProvider
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return ConfigurationProvider
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

}
