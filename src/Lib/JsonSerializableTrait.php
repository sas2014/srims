<?php

namespace App\Lib;

/**
 * JsonSerializableTrait
 */
trait JsonSerializableTrait
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = [];
        foreach ($this as $key => $value) {
            $result[$key] = $value instanceof \DateTimeInterface
                ? $value->format(\DateTime::W3C)
                : $value;
        }

        return $result;
    }
}