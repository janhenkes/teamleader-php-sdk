<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class Deal extends Model
{
    use Storable;
    use FindAll;
    use FindById;

    const TYPE = 'deal';

    protected $fillable = [
        'id',
        'lead', // { "customer": { "type": "contact", "id" : "" }, "contact_person_id" : "" }
        'title',
        'source', // { "type": "", "id" : "" }
        'department', // { "type": "", "id" : "" }
        'responsible_user', // { "type": "", "id" : "" }
        'current_phase', // { "type" : "", "id" : "" }
        'estimated_value',
        'estimated_probability',
        'estimated_closing_date',
        'custom_fields',
        'summary',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'deals';

    /**
     * @return mixed
     */
    public function insert()
    {
        $result = $this->connection()->post($this->getEndpoint() . '.create', $this->jsonWithNamespace());

        return $this->selfFromResponse($result);
    }

    public function move($phaseId)
    {
        $arguments = [
            'id'       => $this->attributes['id'],
            'phase_id' => $phaseId,
        ];

        $result = $this->connection()->post($this->getEndpoint() . '.move', json_encode($arguments, JSON_FORCE_OBJECT));

        return $result;
    }
}
