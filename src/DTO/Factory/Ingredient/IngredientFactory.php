<?php

namespace App\DTO\Factory\Ingredient;

use App\DTO\Factory\AbstractDTOFactory;
use App\DTO\Response\Ingredient\IngredientDTO;

class IngredientFactory extends AbstractDTOFactory
{
    /**
     * @param array $data
     *
     * @return IngredientDTO
     */
    public function create(array $data): IngredientDTO
    {
        return (new IngredientDTO())
            ->setId((int) $data['id'])
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setQuantity((int) $data['quantity']);
    }

}
