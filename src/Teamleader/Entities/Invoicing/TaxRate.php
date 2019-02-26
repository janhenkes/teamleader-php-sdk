<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class TaxRate extends Model
{
    use FindAll;

    protected $fillable = [
        'id',
        'description',
        'rate',
        'department',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'taxRates';
}
