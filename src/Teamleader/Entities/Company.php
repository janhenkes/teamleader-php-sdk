<?php

namespace Teamleader\Entities;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Company extends Model
{
    use FindAll;
    use Storable;

    protected $fillable = [
        'id',
        'name',
        'business_type_id',
        'vat_number',
        'national_identification_number',
        'emails',
        'telephones',
        'website',
        'addresses',
        'iban',
        'bic',
        'language',
        'responsible_user_id',
        'remarks',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'companies';
}
