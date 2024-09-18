<?php
namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $list = [
            ['name' => 'Bell Pepper', 'description' => 'Bell Pepper', 'qty' => 1],
            ['name' => 'Shishito Pepper', 'description' => 'Shishito Pepper', 'qty' => 3],
            ['name' => 'Cherry Pepper', 'description' => 'Cherry Pepper', 'qty' => 5],
            ['name' => 'Ají Dulce', 'description' => 'Ají Dulce', 'qty' => 7],
            ['name' => 'Long Hot Peppers', 'description' => 'Long Hot Peppers', 'qty' => 9],
            ['name' => 'Anaheim Chiles', 'description' => 'Anaheim Chiles', 'qty' => 11],
            ['name' => 'Poblano Chiles', 'description' => 'Poblano Chiles', 'qty' => 14],
            ['name' => 'Hatch Chiles', 'description' => 'Hatch Chiles', 'qty' => 17],
            ['name' => 'Jalapeño Pepper', 'description' => 'Jalapeño Pepper', 'qty' => 19],
            ['name' => 'Fresno Chile', 'description' => 'Fresno Chile', 'qty' => 19],
        ];

        foreach ($list as $key => $ingredient) {
            $product = (new Ingredient())
                ->setName($ingredient['name'])
                ->setDescription($ingredient['description'])
                ->setQuantity($ingredient['qty']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}