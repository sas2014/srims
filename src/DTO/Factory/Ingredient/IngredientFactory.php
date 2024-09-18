<?php

namespace App\DTO\Factory\Ingredient;

use App\DTO\Response\Ingredient\IngredientDTO;

class IngredientFactory
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

    /**
     * @param array $data
     *
     * @return IngredientDTO[]
     */
    public function createList(array $data): array
    {
        $list = [];

        foreach($data as $row) {
            $list[] = $this->create($row);
        }

        return $list;
    }
}
