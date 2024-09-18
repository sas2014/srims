<?php

namespace App\DTO\Response\Ingredient;

use App\Lib\JsonSerializableTrait;

class IngredientDTO extends \App\DTO\Request\Ingredient\IngredientDTO implements \JsonSerializable
{
    use JsonSerializableTrait;

    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return static
     */
    public function setId($id): static
    {
        $this->id = $id;
        return $this;
    }


}