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
        'title',
        'remarks',
        'status',
        'department', // { "type": "department", "id": "" }
        'invoicee', // { "customer": { "type": "contact", "id" : "" }, "for_attention_of" : { "name": "" OR "contact_id" : "" } }
        'project', // {}
        'next_renewal_date',
        'periodicity',
        'total', // {}
        'web_url',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'subscriptions';
}
