<?php

namespace Teamleader\Entities\CRM;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;
use JsonSerializable;

class Contact extends Model implements JsonSerializable
{
    use Storable;
    use FindAll;

    const TYPE = 'contact';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'salutation',
        'emails', // { "type": "", "email" : "" }
        'telephones', // { "type": "", "number" : "" }
        'website',
        'addresses', // { "type": "", "address" : "" }
        'language',
        'gender',
        'birthdate',
        'iban',
        'bic',
        'remarks',
        'tags',
        'custom_fields',
        'marketing_mails_consent',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'contacts';

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function linkToCompany(
        $arguments = [
            'company_id'     => '',
            'position'       => '',
            'decision_maker' => true,
        ]
    ) {
        $arguments['id'] = $this->attributes['id'];

        $result = $this->connection()->post($this->getEndpoint() . '.linkToCompany', json_encode($arguments, JSON_FORCE_OBJECT));

        return $result;
    }

    public function jsonSerialize()
    {
        return (object) [
            'type' => self::TYPE,
            'id' => $this->id,
        ];
    }
}
