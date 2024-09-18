<?php

namespace App\DTO\Request\Ingredient;

class IngredientDTO
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var integer
     */
    protected $quantity;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return static
     */
    public function setName($name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return static
     */
    public function setDescription($description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return static
     */
    public function setQuantity($quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

}