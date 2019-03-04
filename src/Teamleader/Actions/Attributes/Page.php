<?php

namespace Teamleader\Actions\Attributes;

use JsonSerializable;

final class Page implements JsonSerializable
{
    private const DEFAULT_SIZE = 20;
    private const DEFAULT_NUMBER = 1;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $number;

    public function __construct(int $size = self::DEFAULT_SIZE, int $number = self::DEFAULT_NUMBER)
    {
        $this->size = $this->isValidNumber($size) ? $size : self::DEFAULT_SIZE;
        $this->number = $this->isValidNumber($number) ? $number : self::DEFAULT_NUMBER;
    }

    private function isValidNumber(int $number): bool
    {
        return $number >= 1;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getPage(): array
    {
        return [
            'size' => $this->size,
            'number' => $this->number,
        ];
    }

    final public function jsonSerialize(): array
    {
        return $this->getPage();
    }
}
