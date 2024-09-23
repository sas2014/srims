<?php

namespace App\DTO\Factory\Recipe;

use App\DTO\Factory\AbstractDTOFactory;
use App\DTO\Response\Recipe\RecipeDTO;

class RecipeFactory extends AbstractDTOFactory
{

    /**
     * @param array $data
     *
     * @return RecipeDTO
     */
    public function create(array $data): RecipeDTO
    {
        $ingredients = empty($data['ingredients']) ? [] : json_decode('[' . $data['ingredients'] . ']', true);
        return (new RecipeDTO())
            ->setId((int) $data['id'])
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setIngredients($ingredients);
    }

}