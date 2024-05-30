<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\Attributes\Filter;
use Teamleader\Actions\FindAll;
use Teamleader\Model;

class DealPhase extends Model {
    use FindAll;

    const TYPE = 'dealPhase';

    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'dealPhases';

    /**
     * @return mixed
     */
    public function findById() {
        $attributes = [
            'filter' => [
                'ids' => [ $this->id ],
            ],
        ];

        $result = $this->connection()->get( $this->getEndpoint() . '.list', array_filter( $attributes ) );

        return !empty( $result['data'] ) ? $this->selfFromResponse( $result['data'][0] ) : false;
    }
}
