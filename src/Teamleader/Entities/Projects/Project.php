<?php

namespace Teamleader\Entities\Projects;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Project extends Model
{
    use FindAll;
    use FindById;

    const TYPE = 'project';

    protected $fillable = [
        'id',
        'reference',
        'title',
        'description',
        'status',
        'starts_on',
        'due_on',
        'customer', // { "type": "", "id" : "" }
        'source'
    ];

    /**
     * @var string
     */
    protected $endpoint = 'projects';
}
