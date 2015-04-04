<?php namespace Paolooo\LaravelDoctrine;

class CacheFactory
{
    /** @var array */
    protected static $caches = [
        'array' => '\Doctrine\Common\Cache\ArrayCache',
        'apc' => '\Doctrine\Common\Cache\ApcCache',
        'file' => '\Doctrine\Common\Cache\FilesystemCache',
    ];

    /**
     * Creates new cache object
     *
     * @param string     $type Cache name
     * @param array|null $args
     *
     * @return Doctrine\Common\Cache\Cache
     */
    public static function make($type, $args = null)
    {
        try {
            return new self::$caches[$type]($args);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Cache[{$type}] not supported.");
        }
    }

    /**
     * Add new cache
     *
     * @param string $type      Cache name
     * @param string $namespace Cache namespace
     * @return void
     */
    public static function addNewCache($type, $namespace)
    {
        if (empty($type) || empty($namespace)) {
            throw new \InvalidArgumentException("Invalid param [{$type}, {$namespace}]");
        }

        if (isset(self::$caches[$type])) {
            throw new \InvalidArgumentException("Cannot add new cache[$type]. It already exists.");
        }

        self::$caches[$type] = $namespace;
    }

}
