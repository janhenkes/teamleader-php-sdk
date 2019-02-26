<?php
/**
 * Created by PhpStorm.
 * User: janhenkes
 * Date: 21/08/2018
 * Time: 15:15
 */

namespace Teamleader\Handlers;

interface CacheHandlerInterface
{
    public function set($key, $value, $expireInMinutes);

    public function get($key);

    public function forget($key);
}
