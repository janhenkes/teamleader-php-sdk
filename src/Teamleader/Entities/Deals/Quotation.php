<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

/**
 * @property string        id
 * @property-write  string deal_id
 * @property array         grouped_lines
 * @property array         discounts
 * @property string        text
 *
 * @property-read Deal     $deal
 * @property-read object   currency_exchange_rate
 * @property-read object   total
 * @property-read string   created_at
 * @property-read string   updated_at
 * @property-read string   status
 */
class Quotation extends Model
{
    use Storable;
    use FindAll;
    use FindById;

    const TYPE = 'quotation';

    protected $fillable = [
        // read & write
        'id',
        'deal_id',
        'grouped_lines',
        'discounts',

        // read
        'deal',
        'currency_exchange_rate', // {"from": "", "to": "", "rate": n},
        'total', // { "tax_exclusive":{"amount":n, "currency": ""}, "tax_inclusive":{"amount":n, "currency": ""}, "taxes": [{ "rate": n, "taxable" : {"amount":n, "currency": ""}, "tax":{"amount": n, "currency": "" }}}]}
        'created_at',
        'updated_at',
        'status',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'quotations';

    protected $createAction = 'create';

    /**
     * @return mixed
     */

    public function download( $format = "pdf" )
    {
        $arguments = [
            'id'     => $this->attributes['id'],
            'format' => $format,
        ];

        $result = $this->connection()->post( $this->getEndpoint() . '.download', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }

    public function file( $format = "pdf" )
    {
        $result = $this->download( $format );

        if ( isset( $result['data'] ) && isset( $result['data']['location'] ) ) {
            return file_get_contents( $result['data']['location'] );
        }

        return false;
    }
}
