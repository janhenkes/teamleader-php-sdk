<?php
/**
 * Created by PhpStorm.
 * User: janhenkes
 * Date: 14/09/2018
 * Time: 14:41
 */

namespace Teamleader\Entities;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class Webhook extends Model {
    use FindAll;

    /**
     * @var string
     */
    protected $endpoint = 'webhooks';

    // TODO register
    // TODO unregister
}