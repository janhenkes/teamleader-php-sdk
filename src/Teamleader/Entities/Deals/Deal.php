<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

/**
 * @property string id
 */
class Deal extends Model {
    use Storable;
    use FindAll;
    use FindById;

    protected $multipleNestedEntities = [
        'quotations' => [
            'entity' => 'Deals\Quotation',
        ]
    ];

    const TYPE = 'deal';

    protected $fillable = [
        'id',
        'title',
        'summary',
        'reference',
        'status',
        'lead', // { "customer": { "type": "contact", "id" : "" }, "contact_person_id" : "" }
        'department', // { "type": "", "id" : "" }
        'estimated_value',
        'estimated_closing_date',
        'estimated_probability',
        'weighted_value',
        'purchase_order_number',
        'current_phase', // { "type" : "", "id" : "" }
        'responsible_user', // { "type": "", "id" : "" }
        'closed_at',
        'source', // { "type": "", "id" : "" }
        'lost_reason',
        'created_at',
        'updated_at',
        'web_url',
        'custom_fields',
        'quotations',

        // Fields for creating a deal
        'source_id',
        'phase_id',
        'department_id',
        'responsible_user_id',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'deals';

    /**
     * @return mixed
     */
    public function insert() {
        $result = $this->connection()->post( $this->getEndpoint() . '.create', $this->jsonWithNamespace() );

        return $this->selfFromResponse( $result );
    }

    public function move( $phaseId ) {
        $arguments = [
            'id'       => $this->attributes['id'],
            'phase_id' => $phaseId,
        ];

        $result = $this->connection()->post( $this->getEndpoint() . '.move', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }
}
