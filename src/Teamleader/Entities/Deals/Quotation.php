<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Quotation extends Model
{
    use Storable;
    use FindAll;
    use FindById;

    const TYPE = 'deal';

    protected $fillable = [
        'id',
        'deal',
        'grouped_lines',
        'currency_exchange_rate',
        'total'
    ];

    /**
     * @var string
     */
    protected $endpoint = 'quotations';

    /**
     * @return mixed
     */
    public function insert()
    {
        $result = $this->connection()->post($this->getEndpoint() . '.create', $this->jsonWithNamespace());

        return $this->selfFromResponse($result);
    }

    public function download( $format = "pdf" ) {
        $arguments = [
            'id'     => $this->attributes['id'],
            'format' => $format,
        ];

        $result = $this->connection()->post( $this->getEndpoint() . '.download', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }
}
