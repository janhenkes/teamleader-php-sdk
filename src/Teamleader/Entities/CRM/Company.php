<?php

namespace Teamleader\Entities\CRM;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

/**
 * @property string id
 * @property string name
 */
class Company extends Model
{
    use FindAll;
    use Storable;
    use FindById;

    const TYPE = 'company';

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
        'remarks',
        'added_at',
        'updated_at',
        'web_url',
        'tags',
        'custom_fields',
        'marketing_mails_consent',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'companies';
}
