<?php
namespace App\DTO\Response;

use App\Lib\JsonSerializableTrait;

class ListDTO implements \JsonSerializable
{
    use JsonSerializableTrait {
        jsonSerialize as traitJsonSerialize;
    }

    /**
     * @var array
     */
    private $data;

    /**
     * @var int
     */
    private $totalRows;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @param iterable $data
     * @param int $totalRows
     * @param int $limit
     * @param int $offset
     */
    public function __construct(iterable $data, int $totalRows, int $limit, int $offset)
    {
        $this->data = $data;
        $this->totalRows = $totalRows;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $items
     *
     * @return static
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalRows(): int
    {
        return $this->totalRows;
    }

    /**
     * @param int $total
     *
     * @return static
     */
    public function setTotalRows(int $totalRows): self
    {
        $this->totalRows = $totalRows;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return static
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return static
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = $this->traitJsonSerialize();
        $result['page'] = ceil($this->offset / $this->limit) + 1;

        return $result;
    }
}
