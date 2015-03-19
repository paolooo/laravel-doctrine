<?php namespace Paolooo\LaravelDoctrine;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\FilesystemCache;

class CacheFactory
{
    public static function make($type)
    {
        switch ($type) {
            case 'array':
                $cache = new ArrayCache;
                break;

            case 'apc':
                $cache = new ApcCache;
                break;

            default:
                $cache  = new FilesystemCache(
                    config('cache.stores.file.path')
                );
        }

        return $cache;
    }
}
