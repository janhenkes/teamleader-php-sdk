<?php

namespace Teamleader\Entities\Tasks;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Task extends Model
{
    use FindAll;
    use FindById;
    use Storable;

    const TYPE = 'task';

    protected $fillable = [
        'id',
        'title',
        'description',
        'completed',
        'completed_at',
        'due_on',
        'estimated_duration', // { "unit": "", "value" : "" }
        'work_type_id',
        'work_type', // { "type": "", "id" : "" }
        'assignee', // { "type": "", "id" : "" }
        'customer', // { "type": "", "id" : "" }
        'milestone_id',
        'milestone', // { "type": "", "id" : "" }
        'deal_id',
        'deal', // { "type": "", "id" : "" }
        'project_id',
        'project', // { "type": "", "id" : "" }
        'custom_fields',
        'ticket_id',
        'ticket', // { "type": "", "id" : "" }
    ];

    /**
     * @var string
     */
    protected $endpoint = 'tasks';

    protected $createAction = 'create';
}
