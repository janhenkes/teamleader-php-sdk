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
        'name',
        'due_on',
        'status',
        'project', // { "type": "", "id" : "" }
        'responsible_user', // { "type": "", "id" : "" }
        'invoicing_method'
    ];

    /**
     * @var string
     */
    protected $endpoint = 'milestones';
}
