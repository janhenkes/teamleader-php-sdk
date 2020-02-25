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

    const TYPE = 'user';

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
        'custom_fields',
        'status',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'users';

    public function me()
    {
        $result = $this->connection()->post($this->getEndpoint() . '.me', $this->jsonWithNamespace());

        if ($result === 200) {
            return true;
        }

        return $this->selfFromResponse($result);
    }
}
