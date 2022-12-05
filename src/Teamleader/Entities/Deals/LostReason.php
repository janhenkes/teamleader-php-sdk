<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

/**
 * @property string id
 * @property string name
 */
class LostReason extends Model {
    use FindAll;

    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'lostReasons';
}
