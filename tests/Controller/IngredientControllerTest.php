<?php
namespace App\Tests\Controller;

use App\DataFixtures\IngredientFixtures;
use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

class IngredientControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser|null
     */
    protected $client = null;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if(!$this->client) {
            $this->client = static::createClient();
        }
        $container = $this->client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $loader = new Loader();
        $loader->addFixture(new UserFixtures());
        $loader->addFixture(new IngredientFixtures());

        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
    * @testdox Ingredient list
    */
    public function testIngredientlist()
    {
        $crawler = $this->client->request(
            'POST',
            '/api/v1/ingredient/list',
            [],
            [],
            [
                'HTTP_Authorization' => 'dadd29a55c5220f6d8dc5c25d581c8',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'limit' => 10,
                'offset' => 0,
                'query' => 'hile',
            ])
        );

        $content = $this->client->getResponse()->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(4, count($content['data']));
    }
}