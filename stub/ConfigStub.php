<?php namespace Paolooo\Stub;

class ConfigStub
{
    public function get($path)
    {
        return __DIR__ . '/../stub/storage';
    }
}
