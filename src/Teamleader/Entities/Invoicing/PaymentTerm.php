<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class PaymentTerm extends Model
{
    use FindAll;

    protected $fillable = [
        'id',
        'type',
        'days',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'paymentTerms';
}
