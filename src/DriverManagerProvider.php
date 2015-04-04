<?php namespace Paolooo\LaravelDoctrine;

use Doctrine\DBAL\DriverManager;

class DriverManagerProvider implements DriverManagerInterface
{
    /** @var array */
    protected $dbParams;

    /**
     * @param array $dbParams Database config parameter
     */
    public function __construct($dbParams)
    {
        $this->dbParams = $dbParams;
    }

    /**
     * Gets database connection
     *
     * @return Doctrine\DBAL\Connection
     */
    public function connection($key=null)
    {
        return DriverManager::getConnection($this->config($key));
    }

    /**
     * @param string $key
     *
     * @return array
     */
    private function config($key)
    {
        $key = (empty($key)) ? 'default' : $key;

        $dbConfig = array_get($this->dbParams, $key) ?: [];
        $params = array_merge($this->dbParams, $dbConfig);

        return [
            'dbname'    => $params['database'],
            'user'      => $params['username'],
            'password'  => $params['password'],
            'host'      => $params['host'],
            'driver'    => $params['driver']
        ];
    }
}
