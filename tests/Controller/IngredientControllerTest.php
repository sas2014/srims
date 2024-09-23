<?php
namespace App\Tests\Controller;

use App\DataFixtures\IngredientFixtures;
use App\DataFixtures\UserFixtures;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Lib\MyWebTestCase;
use App\Tests\Traits\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class IngredientControllerTest extends MyWebTestCase
{
    use FixturesTrait;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        self::load(
            [
                new UserFixtures(),
                new IngredientFixtures(),
            ]
        );
    }

    /**
    * @testdox Ingredient list
    */
    public function testIngredientlist()
    {
        $content = $this
            ->authorizeDefaultUser()
            ->setExpectedStatusCode(Response::HTTP_OK)
            ->sendRequest(
                Request::METHOD_POST,
                '/api/v1/ingredient/list',
                [
                    'limit' => 10,
                    'offset' => 0,
                    'query' => 'hile',
                ]
            )
            ->getContent();

        $this->assertCount(4, $content['data']);
    }
}