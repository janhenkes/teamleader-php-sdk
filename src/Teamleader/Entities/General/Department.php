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
        'address',
        'emails',
        'telephones',
        'website',
        'currency',
        'iban',
        'bic',
        'fiscal_regime',
        'status',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'departments';
}
