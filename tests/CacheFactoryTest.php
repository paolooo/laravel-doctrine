<?php namespace Paolooo\Test;

use Paolooo\LaravelDoctrine\CacheFactory;

class CacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_get_an_array_instance()
    {
        $this->assertInstanceOf(
            'Doctrine\Common\Cache\ArrayCache',
            CacheFactory::make('array')
        );
    }

    /** @test */
    public function should_get_an_apc_instance()
    {
        $this->assertInstanceOf(
            'Doctrine\Common\Cache\ApcCache',
            CacheFactory::make('apc')
        );
    }

    /** @test */
    public function should_get_an_filesystem_instance()
    {
        $this->assertInstanceOf(
            'Doctrine\Common\Cache\FilesystemCache',
            CacheFactory::make('file', 'app/storage/framework/cache')
        );
    }

    /** @test */
    public function should_throw_invalid_cache()
    {
        $this->setExpectedException('\InvalidArgumentException', 'unknown');
        CacheFactory::make('unknown');
    }

    /** @test */
    public function should_add_new_cache()
    {
        CacheFactory::addNewCache('chain', '\Doctrine\Common\Cache\ChainCache');

        $this->assertInstanceOf(
            'Doctrine\Common\Cache\ChainCache',
            CacheFactory::make('chain')
        );
    }

    /** @test */
    public function should_throw_invalid_cache_when_adding_existing_cache()
    {
        $this->setExpectedException(
            '\InvalidArgumentException', 'Cannot add new cache'
        );
        CacheFactory::addNewCache('file', '\Unknown\Namespace');
    }

}
