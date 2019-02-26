<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Invoice extends Model
{
    use FindAll;
    use Storable;

    private $createAction = 'draft';

    protected $fillable = [
        'invoicee', // { "customer": { "type": "contact", "id" : "" }, "for_attention_of" : { "name": "" OR "contact_id" : "" } }
        'department_id',
        'payment_term', // { "type": "cash", "days" : "" }
        'grouped_lines',
        'invoice_date',
        'discounts',
        'note',
        'custom_fields',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'invoices';
}
