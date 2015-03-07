<?php namespace Paolooo\Test;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\DetectEnvironment;
use Paolooo\Stub\ConfigStub as Config;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = $this->createApplication();
    }

    public function tearDown()
    {
        $this->app = null;
    }

    public function createApplication()
    {
        $app = new Application(__DIR__ . '/../stub');

        $app->instance('path', '');
        $app->instance('path.base', '');
        $app->instance('path.storage', '');
        $app->instance('config', new Config);

        $app->loadEnvironmentFrom(__DIR__ . '/../examples/' . '.env');
        (new DetectEnvironment)->bootstrap($app);

        return $app;
    }

}
