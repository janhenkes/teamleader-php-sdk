<?php

namespace Teamleader\Entities\Tasks;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Task extends Model
{
    use FindAll;
    use Storable;
    use FindById;

    const TYPE = 'task';

    protected $fillable = [
        'id',
        'description',
        'completed',
        'completed_at',
        'due_on',
        'estimated_duration', // { "unit": "", "value" : "" }
        'work_type', // { "type": "", "id" : "" }
        'assignee', // { "type": "", "id" : "" }
        'customer', // { "type": "", "id" : "" }
        'milestone', // { "type": "", "id" : "" }
        'deal', // { "type": "", "id" : "" }
        'project', // { "type": "", "id" : "" }
        'custom_fields',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'tasks';
}
