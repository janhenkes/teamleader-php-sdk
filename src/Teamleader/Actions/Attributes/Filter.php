<?php

namespace Teamleader\Actions\Attributes;

use JsonSerializable;

class Filter implements JsonSerializable
{
    /**
     * These can be overwitten in filters specific for a certain entity to aid with validation
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected $filters = [];

    public function __construct(array $filters = [])
    {
        $this->fill($filters);
    }

    public function addFilter(string $key, $value): self
    {
        if ($this->isFillable($key)) {
            $this->filters[$key] = $value;
        }

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    protected function fill(array $filters): void
    {
        foreach ($filters as $key => $value) {
            $this->addFilter($key, $value);
        }
    }

    protected function isFillable(string $key): bool
    {
        if (count($this->fillable) > 0) {
            return in_array($key, $this->fillable);
        }

        return true;
    }

    final public function jsonSerialize(): array
    {
        return $this->getFilters();
    }
}
