<?php

namespace App\Tests\Traits;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

trait FixturesTrait
{

    /**
     * @param array $fixtures
     * @return void
     */
    protected static function load(array $fixtures): void
    {
        $container = self::$client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $loader = new Loader();
        foreach ($fixtures as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}