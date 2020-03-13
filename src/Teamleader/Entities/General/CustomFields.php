<?php

namespace Teamleader\Entities\General;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class CustomFields extends Model
{
    use FindAll;
    use Storable;
    use FindById;

    protected $fillable = [
        'id',
        'context',
        'type',
        'label',
        'group',
        'required',
        'configuration'
    ];

    /**
     * @var string
     */
    protected $endpoint = 'customFieldDefinitions';

}
