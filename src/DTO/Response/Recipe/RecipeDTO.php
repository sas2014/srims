<?php

namespace App\DTO\Response\Recipe;

use App\Lib\JsonSerializableTrait;

class RecipeDTO extends \App\DTO\Request\Recipe\RecipeDTO implements \JsonSerializable
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
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}
