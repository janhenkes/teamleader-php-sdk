<?php

namespace Teamleader\Entities\Other;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class Webhook extends Model
{
    use FindAll;

    protected $fillable = [
        'url',
        'types', // { "customer": { "type": "contact", "id" : "" }, "contact_person_id" : "" }
    ];

    /**
     * @var string
     */
    protected $endpoint = 'webhooks';

    public function register()
    {
        $result = $this->connection()->post($this->getEndpoint() . '.register', json_encode($this->attributes, JSON_FORCE_OBJECT));

        return $result;
    }

    public function unregister()
    {
        $result = $this->connection()->post($this->getEndpoint() . '.unregister', json_encode($this->attributes, JSON_FORCE_OBJECT));

        return $result;
    }
}
