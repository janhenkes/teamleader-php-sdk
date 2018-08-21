<?php namespace Teamleader\Actions;

/**
 * Class FindAll
 * @package Teamleader\Actions
 */
trait FindAll
{

    /**
     * @return mixed
     */
    public function get()
    {
        $result = $this->connection()->get($this->getEndpoint() . '.list');

        return $this->collectionFromResult($result);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $result = $this->connection()->get($this->getEndpoint() . '.list', [], true);

        return $this->collectionFromResult($result);
    }

}
