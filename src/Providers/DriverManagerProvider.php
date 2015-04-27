<?php namespace Paolooo\LaravelDoctrine\Providers;

use Doctrine\DBAL\DriverManager;
use Paolooo\LaravelDoctrine\Contracts\DriverManagerInterface;

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

        $sqlite_params = array('path' => $params['database']);

        return array_merge(
            $sqlite_params,
            $params,
            [
                'dbname'    => $params['database'],
                'user'      => $params['username']
            ]
        );
    }
}
