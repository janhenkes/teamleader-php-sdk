<?php

namespace Teamleader\Entities\TimeTracking;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class TimeTracking extends Model
{
    use FindAll;
    use FindById;

    const TYPE = 'timetracking';

    protected $fillable = [
        'id',
        'user', // { "type": "", "id" : "" }
        'work_type', // { "type": "", "id" : "" }
        'started_on',
        'started_at',
        'ended_at',
        'duration',
        'description',
        'subject', // { "type": "", "id" : "" }
        'invoiceable'
    ];

    /**
     * @var string
     */
    protected $endpoint = 'timetracking';
}
