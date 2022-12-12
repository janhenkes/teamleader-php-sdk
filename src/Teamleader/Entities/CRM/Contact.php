<?php

namespace Teamleader\Entities\CRM;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Entities\General\CustomFieldDefinition;
use Teamleader\Model;

/**
 * @property string id
 * @property string first_name
 * @property string last_name
 * @property Company[] companies
 */
class Contact extends Model
{
    use Storable;
    use FindAll;
    use FindById;

    const TYPE = 'contact';

    /**
     * @var string
     */
    protected $endpoint = 'contacts';

    /**
     * @var array
     */
    protected $multipleNestedEntities = [
        'custom_fields' => [
            'entity' => CustomFieldDefinition::class,
            'type' => self::NESTING_TYPE_ARRAY_OF_OBJECTS,
        ],
    ];

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'salutation',
        'vat_number',
        'emails', // { "type": "", "email": "" }
        'telephones', // { "type": "", "number": "" }
        'website',
        'addresses', // { "type": "", "address": "" } used in contacts.info
        'gender',
        'birthdate',
        'iban',
        'bic',
        'national_identification_number',
        'companies', // { "position": "", "decision_maker": "", "company": { "type": "", "id" : "" } } used in contacts.info
        'language',
        'payment_term',
        'invoicing_preferences',
        'remarks',
        'tags',
        'custom_fields', // used in contacts.info
        'marketing_mails_consent', // used in contacts.info
        'added_at',
        'updated_at',
        'web_url',
    ];

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
}
