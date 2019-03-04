<?php

namespace Teamleader\Actions\Attributes;

use JsonSerializable;

class Sort implements JsonSerializable
{
    public const ORDER_ASC = 'asc';
    public const ORDER_DESC = 'desc';
    private const POSSIBLE_ORDERS = [
        self::ORDER_ASC,
        self::ORDER_DESC,
    ];

    /**
     * These can be overwitten in sorts specific for a certain entity to aid with validation
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    private $sorts;

    public function __construct(array $sorts = [])
    {
        $this->fill($sorts);
    }

    public function addSort(string $field, string $order): self
    {
        if ($this->isFillable($field) && $this->isValidOrder($order)) {
            $this->sorts[$field] = $order;
        }

        return $this;
    }

    public function getSorts(): array
    {
        return $this->sorts;
    }

    protected function fill(array $sorts): void
    {
        foreach ($sorts as $field => $order) {
            $this->addSort($field, $order);
        }
    }

    protected function isFillable(string $field): bool
    {
        if (count($this->fillable) > 0) {
            return in_array($field, $this->fillable);
        }

        return true;
    }

    protected function isValidOrder(string $order): bool
    {
        return in_array($order, self::POSSIBLE_ORDERS);
    }

    public function jsonSerialize(): array
    {
        return $this->getSorts();
    }
}
