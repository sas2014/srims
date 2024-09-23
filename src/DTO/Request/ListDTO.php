<?php

namespace App\DTO\Request;

class ListDTO
{
    /**
     * @var string|null
     */
    protected $query;

    /**
     * @var int|null
     */
    protected $limit = 20;

    /**
     * @var int|null
     */
    protected $offset = 0;

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|null $query
     * @return static
     */
    public function setQuery(?string $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return static
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return static
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

}
