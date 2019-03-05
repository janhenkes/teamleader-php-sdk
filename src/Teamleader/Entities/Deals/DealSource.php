<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class DealSource extends Model
{
    use FindAll;

    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'dealSources';
}
