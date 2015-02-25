<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class ModelTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * Array of \Doctrine\ORM\Mapping\ClassMetadata
     * @var array
     */
    protected $metadata;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        $this->initializeDoctrine();
    }

    /**
     * Get entity metadata for doctrine and
     * store it to on `$this->metadata` array variable.
     *
     * @return void
     */
    private function assignClassMetadata()
    {
        foreach($this->classes AS $class) {
            $this->metadata[] = $this->entityManager->getClassMetadata($class);
        }
    }

    /**
     * Creates database and run schema to create tables.
     *
     * @return void
     */
    public function initializeDoctrine()
    {
        $this->entityManager = App::make(EntityManager::class);
        $this->assignClassMetadata();

        $tool = new SchemaTool($this->entityManager);
        $this->dropSchema($tool);
        $tool->createSchema($this->metadata);
    }

    /**
     * Drop database tables.
     *
     * @param SchemaTool $tool
     * @return void
     */
    private function dropSchema(SchemaTool $tool)
    {
        $tool->dropSchema($this->metadata);
    }

    /**
     * Destroy
     */
    public function tearDown()
    {
        $tool = new SchemaTool($this->entityManager);
        $this->dropSchema($tool);
        $this->entityManager->close();
    }
}
