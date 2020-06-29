<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Invoice extends Model
{
    use FindAll;
    use Storable;

    const TYPE = 'invoice';

    private $createAction = 'draft';

    protected $fillable = [
        'id',
        'created_at',
        'custom_fields',
        'department', // { "type": "department", "id": "" }
        'department_id',
        'discounts',
        'due_on',
        'for_attention_of', // { "name": "", "contact": { "type": "contact", "id": "" }
        'grouped_lines',
        'invoicee', // { "customer": { "type": "contact", "id" : "" }, "for_attention_of" : { "name": "" OR "contact_id" : "" } }
        'invoice_date',
        'invoice_number',
        'note',
        'paid',
        'paid_at',
        'payment_reference',
        'payment_term', // { "type": "cash", "days" : "" }
        'purchase_order_number',
        'sent',
        'status',
        'total', // {}
        'updated_at',
        'web_url',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'invoices';

    public function book( $date = null ) {
        $arguments = [
            'id' => $this->attributes['id'],
            'on' => $date ?? date( 'Y-m-d' ),
        ];

        $result = $this->connection()->post( $this->getEndpoint() . '.book', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }

    public function registerPayment( $amount, $currency = "EUR", $paidAt = null ) {
        $arguments = [
            'id'      => $this->attributes['id'],
            'payment' => [
                'amount'   => $amount,
                'currency' => $currency,
            ],
            'paid_at' => $paidAt ?? date( 'c' ),
        ];

        $result = $this->connection()->post( $this->getEndpoint() . '.registerPayment', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }

    public function download( $format = "pdf" ) {
        $arguments = [
            'id'     => $this->attributes['id'],
            'format' => $format,
        ];

        $result = $this->connection()->post( $this->getEndpoint() . '.download', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }

    public function file( $format = "pdf" ) {

        $result = $this->download( $format );

        if ( isset( $result['data'] ) && isset( $result['data']['location'] ) ) {
            return file_get_contents( $result['data']['location'] );
        }

        return false;
    }
}
