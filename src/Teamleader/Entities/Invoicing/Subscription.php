<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Subscription extends Model
{
    use FindAll;
    use FindById;

    protected $fillable = [
        'id',
        'department', // { "type": "department", "id": "" }
        'title',
        'status',
        'next_renewal_date',
        'invoicee', // { "customer": { "type": "contact", "id" : "" }, "for_attention_of" : { "name": "" OR "contact_id" : "" } }
        'starts_on',
        'ends_on',
        'periodicity',
        'payment_term',
        'grouped_lines',
        'project', // {}
        'total', // {}
        'web_url',
        'remarks',
        'custom_fields',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'subscriptions';
}
