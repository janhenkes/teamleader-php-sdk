<?php

namespace Teamleader\Exceptions\Api;

use Teamleader\Exceptions\ApiException;

class TooManyRequestsException extends ApiException
{
    /**
     * @link https://developer.moneybird.com/#throttling
     *
     * @var int
     */
    public $retryAfterNumberOfSeconds;

}