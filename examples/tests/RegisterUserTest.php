<?php

use Acme\Domain\Model\User;

class RegisterUserTest extends ModelTestCase
{
    /**
     * Entities
     * @var array
     */
    protected $classes = [
        '\Acme\Domain\Model\User',
    ];


    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function should_instantiate()
    {
        $this->assertInstanceOf('\Acme\Domain\Entity', new User);
    }

}
