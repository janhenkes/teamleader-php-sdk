<?php

namespace Teamleader\Entities\Projects;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Milestone extends Model
{
    use FindAll;
    use FindById;

    const TYPE = 'milestone';

    protected $fillable = [
        'id',
        'project', // { "type": "", "id" : "" }
        'starts_on',
        'due_on',
        'name',
        'responsible_user', // { "type": "", "id" : "" }
        'status',
        'invoicing_method',
        'depends_on',
        'dependency_for',
        'actuals',
        'budget',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'milestones';
}
