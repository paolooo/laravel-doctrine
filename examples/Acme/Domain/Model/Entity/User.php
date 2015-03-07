<?php namespace Paolooo\Acme\Domain\Model;

use Paolooo\Acme\Domain\Entity;

/**
 * @Entity
 * @Table(name="users")
 */
class User implements Entity
{
    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $first_name;

    /**
     * @Column(type="string")
     */
    private $last_name;
}
