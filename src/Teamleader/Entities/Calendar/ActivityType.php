<?php

namespace Teamleader\Entities\Calendar;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class ActivityType extends Model
{
    use FindAll;

    protected $fillable = [
        'id',
        'name'
    ];

    /**
     * @var string
     */
    protected $endpoint = 'activityTypes';
}
