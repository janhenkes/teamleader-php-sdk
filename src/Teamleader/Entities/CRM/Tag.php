<?php

namespace Teamleader\Entities\CRM;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class Tag extends Model
{
    use FindAll;

    protected $fillable = [
        'tag',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'tags';
}
