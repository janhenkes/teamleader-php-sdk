<?php

namespace Teamleader\Entities\CRM;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;
use JsonSerializable;

class Company extends Model implements JsonSerializable
{
    use FindAll;
    use Storable;

    const TYPE = 'company';

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

    public function jsonSerialize()
    {
        return (object) [
            'type' => self::TYPE,
            'id' => $this->id,
        ];
    }
}
