<?php
/**
 * Created by PhpStorm.
 * User: janhenkes
 * Date: 14/09/2018
 * Time: 14:41
 */

namespace Teamleader\Entities;

use Teamleader\Actions\FindAll;
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