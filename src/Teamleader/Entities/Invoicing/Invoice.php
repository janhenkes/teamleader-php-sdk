<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;
use JsonSerializable;

class Invoice extends Model implements JsonSerializable
{
    use FindAll;
    use Storable;

    const TYPE = 'invoice';

    private $createAction = 'draft';

    protected $fillable = [
        'id',
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

    public function jsonSerialize(): object
    {
        return (object) [
            'type' => self::TYPE,
            'id' => $this->id,
        ];
    }
}
