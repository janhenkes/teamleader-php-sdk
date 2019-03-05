<?php

namespace Teamleader\Entities\General;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class User extends Model
{
    use FindAll;
    use FindById;

    protected $fillable = [
        'id',
        'account', // { "type":"account", "id":"" }
        'first_name',
        'last_name',
        'email',
        'telephones',
        'language',
        'function',
        'time_zone',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'users';
}
