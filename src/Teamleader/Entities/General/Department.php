<?php

namespace Teamleader\Entities\General;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class Department extends Model
{
    use FindAll;

    protected $fillable = [
        'id',
        'name',
        'vat_number',
        'currency',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'departments';
}
