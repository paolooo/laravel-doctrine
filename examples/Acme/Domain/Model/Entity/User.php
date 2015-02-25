<?php namespace Acme\Domain\Model;

use Acme\Domain\Entity;

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
     * @Column(type="string", length="32")
     */
    private $first_name;

    /**
     * @Column(type="string", length="32")
     */
    private $last_name;
}
