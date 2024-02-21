<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Quotation extends Model {
    use Storable;
    use FindAll;
    use FindById;

    const TYPE = 'quotation';

    protected $fillable = [
        'id',
        'deal',
        'grouped_lines',
        'currency_exchange_rate', // {"from": "", "to": "", "rate": n},
        'total', // { "tax_exclusive":{"amount":n, "currency": ""}, "tax_inclusive":{"amount":n, "currency": ""}, "taxes": [{ "rate": n, "taxable" : {"amount":n, "currency": ""}, "tax":{"amount": n, "currency": "" }}}]}
        'discounts',
        'created_at',
        'updated_at',
        'status',
        'currency',

        // Fields for creating a deal
        'deal_id',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'quotations';

    protected $createAction = 'create';

    /**
     * @return mixed
     */

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

    public function accept() {
        return $this->connection()->post( $this->getEndpoint() . '.accept', json_encode( [ 'id' => $this->attributes['id'] ], JSON_FORCE_OBJECT ) );
    }
}
