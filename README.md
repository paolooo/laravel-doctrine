# Doctrine 2 Service for Laravel 5+

[![Build Status](https://travis-ci.org/paolooo/coffee-espresso-two-shots.svg?branch=master)](https://travis-ci.org/paolooo/coffee-espresso-two-shots)

Run all doctrine commands easily using `$ php artisan doctrine <command> <options>`

This service will grab your laravel's configuration (db and cache) and automatically apply it to doctrine2 configurate, no more hassle configuration needed. :)

## Installation

```bash
$ composer require "paolooo/laravel-doctrine":"0.1.*@dev"
```

Open and edit `config/app.php` configuration file, and add the following service provider code to the `$providers` array.

```
        'Paolooo\LaravelDoctrine\LaravelDoctrineServiceProvider',
```

Edit .env file. Add the following doctrine config. For more information,
of doctrine configuration, see, http://doctrine-orm.readthedocs.org/en/latest/reference/advanced-configuration.html.

```
# .env
...

DOCTRINE_PROXY_AUTOGENERATED=false
DOCTRINE_PROXY_NAMESPACE=Acme\Domain\Model\Proxy
DOCTRINE_PROXY_DIR=app/Domain/Model/Proxy
DOCTRINE_MAPPING_DIR=app/Domain/Model

```

This configuration is for testing environment. Edit phpunit.xml file.

```
# phpunit.xml

<?xml version="1.0" encoding="UTF-8"?>
<phpunit ...>
    ....
    <php>
        ...
        <env name="DB_DRIVER" value="pdo_sqlite"/>
        <env name="DB_DATABASE" value="storage/tests/db.sqlite"/>
    </php>
</phpunit>
```


## Running Doctrine Commands

Sample artisan for doctrine.

```bash
$ php artisan doctrine
$ php artisan doctrine help orm:schema-tool:create
$ php artisan doctrine orm:schema-tool:create
$ php artisan doctrine orm:schema-tool:create --dump-sql
$ php artisan doctrine orm:schema-tool:update
$ php artisan doctrine orm:schema-tool:drop
```

## Example

See `examples/` directory.

* Sample config file, see `.env` and `phpunit.xml` files.
* Sample `User` entity class, see `Acme/Domain/Model/Entity/User.php` file.
* Sample `ModelTestCase`. This will get the setup doctrine for you. Sets `$this->entityManager`. Create and drop schema as well.

Learn doctrine here http://doctrine-orm.readthedocs.org/en/latest/tutorials/getting-started.html


## TODO

- Migration
