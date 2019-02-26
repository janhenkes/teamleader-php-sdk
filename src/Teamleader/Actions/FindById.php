<?php

namespace Teamleader\Actions;

/**
 * Class FindById
 * @package Teamleader\Actions
 */
trait FindById
{
    /**
     * @return mixed
     */
    public function findById()
    {
        $result = $this->connection()->post($this->getEndpoint() . '.info', $this->jsonWithNamespace());

        if ($result === 200) {
            return true;
        }

        return $this->selfFromResponse($result);
    }
}
