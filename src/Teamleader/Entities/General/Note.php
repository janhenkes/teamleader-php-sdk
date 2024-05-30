<?php

namespace Teamleader\Entities\General;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Note extends Model
{
    use FindAll, Storable;

    protected $fillable = [
        'id',
        'content',
        'subject',
        'added_at',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'notes';
}
