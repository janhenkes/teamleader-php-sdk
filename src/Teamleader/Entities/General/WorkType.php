<?php

namespace Teamleader\Entities\General;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class WorkType extends Model
{
    use FindAll;

    const TYPE = 'worktype';

    protected $fillable = [
        'id',
        'name'
    ];

    /**
     * @var string
     */
    protected $endpoint = 'workTypes';
}
