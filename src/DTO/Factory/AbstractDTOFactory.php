<?php

namespace App\DTO\Factory;

use phpDocumentor\Reflection\Types\Mixed_;

abstract class AbstractDTOFactory
{
    abstract public function create(array $data);

    /**
     * @param array $data
     * @return array
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