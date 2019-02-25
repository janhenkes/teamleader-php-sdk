<?php

namespace Teamleader\Entities\CRM;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class BusinessType extends Model
{
    use FindAll;

    protected $fillable = [
        'id',
        'name',
        'country',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'businessTypes';
}
