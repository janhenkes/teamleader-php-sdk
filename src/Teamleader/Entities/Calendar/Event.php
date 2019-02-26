<?php

namespace Teamleader\Entities\Calendar;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Event extends Model
{
    use Storable;
    use FindAll;
    use FindById;

    // define the action because endpoints can differ
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
