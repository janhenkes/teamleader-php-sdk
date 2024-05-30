<?php

namespace Teamleader\Entities\CRM;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Company extends Model {
    use FindAll;
    use Storable;
    use FindById;

    const TYPE = 'company';

    protected $multipleNestedEntities = [
        'related_contacts'  => [
            'entity' => 'CRM\Contact',
        ],
        'related_companies' => [
            'entity' => 'CRM\Company',
        ],
    ];

    protected $fillable = [
        'id',
        'name',
        'business_type', // { "type": "", "id" : "" }
        'vat_number',
        'national_identification_number',
        'emails',
        'telephones',
        'website',
        'addresses', // { "type": "", "address": "" } used in contacts.info
        'primary_address', // used in contacts.list
        'iban',
        'bic',
        'language',
        'preferred_currency',
        'payment_term', // { "type": "" }
        'invoicing_preferences', // { "electronic_invoicing_address": "" }
        'responsible_user', // { "type": "", "id" : "" }
        'responsible_user_id', // { "type": "", "id" : "" }
        'remarks',
        'added_at',
        'updated_at',
        'web_url',
        'tags',
        'custom_fields',
        'marketing_mails_consent',
        'related_contacts',
        'related_companies',

        //
        'includes', // when used, the response will include related_companies and/or related_contacts
    ];

    /**
     * @var string
     */
    protected $endpoint = 'companies';
}
