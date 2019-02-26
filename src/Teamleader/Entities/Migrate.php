<?php

namespace Teamleader\Entities;

use Teamleader\Model;

class Migrate extends Model {
    protected $fillable = [
        'id',
        'type',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'migrate';

    public function id() {
        $result = $this->connection()->post( $this->getEndpoint() . '.id', json_encode( $this->attributes, JSON_FORCE_OBJECT ) );

        return $result;
    }
}
