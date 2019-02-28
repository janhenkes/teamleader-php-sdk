<?php

namespace Teamleader\Actions\Attributes;

class Sort
{
    public const DIRECTION_ASC = 'asc';
    public const DIRECTION_DESC = 'desc';
    private const POSSIBLE_DIRECTIONS = [
        self::DIRECTION_ASC,
        self::DIRECTION_DESC,
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
    protected $sorts;

    public function __construct(array $sorts = [])
    {
        $this->fill($sorts);
    }

    public function addSort(string $field, string $direction): self
    {
        if ($this->isFillable($field) && $this->isValidDirection($direction)) {
            $this->sorts[$field] = $direction;
        }

        return $this;
    }

    public function getSorts(): array
    {
        return $this->sorts;
    }

    protected function fill(array $sorts): void
    {
        foreach ($sorts as $field => $direction) {
            $this->addSort($field, $direction);
        }
    }

    protected function isFillable(string $field): bool
    {
        return in_array($field, $this->fillable);
    }

    protected function isValidDirection(string $direction): bool
    {
        return in_array($direction, self::POSSIBLE_DIRECTIONS);
    }
}
