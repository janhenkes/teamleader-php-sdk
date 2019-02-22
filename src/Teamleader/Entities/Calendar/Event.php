<?php

namespace Teamleader\Entities\Calendar;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Event extends Model
{
    use Storable;
    use FindAll;

    // define the action because some endpoints use add and some use create
    private $createAction = 'create';
    private $deleteAction = 'cancel';

    protected $fillable = [
        'id',
        'title',
        'description',
        'activity_type_id',
        'starts_at',
        'ends_at',
        'location',
        'work_type_id',
        'attendees', // { "type":"contact", "id":"" }
        'links', // { "type":"contact", "id":"" }
    ];

    /**
     * @var string
     */
    protected $endpoint = 'events';
}
