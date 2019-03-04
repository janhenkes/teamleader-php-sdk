<?php

namespace Teamleader\Actions;

use Teamleader\Actions\Attributes\Filter;
use Teamleader\Actions\Attributes\Page;
use Teamleader\Actions\Attributes\Sort;

/**
 * Class FindAll
 *
 * @package Teamleader\Actions
 */
trait FindAll
{

    /**
     * @return mixed
     */
    public function get(Filter $filter = null, Page $page = null, Sort $sort = null)
    {
        $attributes = [
            'filter' => $filter,
            'page' => $page,
            'sort' => $sort,
        ];

        $result = $this->connection()->get($this->getEndpoint() . '.list', array_filter($attributes));

        return $this->collectionFromResult($result);
    }

    /**
     * @return mixed
     */
    public function getAll(Filter $filter = null, Sort $sort = null)
    {
        $attributes = [
            'filter' => $filter,
            'sort' => $sort,
        ];

        $result = $this->connection()->get($this->getEndpoint() . '.list', array_filter($attributes), true);

        return $this->collectionFromResult($result);
    }
}
