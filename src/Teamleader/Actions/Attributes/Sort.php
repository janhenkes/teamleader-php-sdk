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

    /**
     * @var bool
     * Some sorts only allow a single field to sort on (like calendar events)
     * This is used for the different format that is needed in that case.
     */
    private $singleFieldSort;

    /**
     * @var $sorts An array containing the sorting parameters
     * @var $singleFieldSort Some sorts only allow a single field to sort on (like calendar events)
     */
    public function __construct(array $sorts = [], bool $singleFieldSort = false)
    {
        $this->fill($sorts);
        $this->singleFieldSort = $singleFieldSort;
    }

    public function addSort(string $field, string $order): self
    {
        if ($this->singleFieldSort) {
            throw new \LogicException('You can only sort on one item when singleFieldSort is active');
        }

        if ($this->isFillable($field) && $this->isValidOrder($order)) {
            $this->sorts[] = [
                'field' => $field,
                'order' => $order,
            ];
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
        if ($this->singleFieldSort) {
            return reset($this->sorts);
        }

        return $this->getSorts();
    }
}
