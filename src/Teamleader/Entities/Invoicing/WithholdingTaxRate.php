<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class WithholdingTaxRate extends Model
{
    use FindAll;

    protected $fillable = [
        'id',
        'department',
        'description',
        'rate',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'withholdingTaxRates';
}
