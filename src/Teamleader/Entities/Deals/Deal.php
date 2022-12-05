<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Entities\General\CustomFields;
use Teamleader\Entities\General\Department;
use Teamleader\Model;

/**
 * @property string           id
 * @property string           title
 * @property string           summary
 * @property string           reference
 * @property string           status
 * @property string           purchase_order_number
 * @property string           estimated_closing_date
 * @property array            estimated_value
 * @property float            estimated_probability
 * @property array            lead
 * @property array            current_phase
 *
 * @property-read object      source
 * @property-read object      responsible_user
 * @property-read array       lost_reason
 * @property-read Quotation[] quotations
 *
 * @property-write string     department_id
 * @property-write string     responsible_user_id
 * @property-write string     source_id
 * @property-write string     phase_id
 */
class Deal extends Model
{
    use Storable;
    use FindAll;
    use FindById;

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
        'lost_reason', // {"reason": { "type": "lostReason", "id": "" }}, "remark": "" }
        'created_at',
        'updated_at',
        'web_url',
        'custom_fields',
        'quotations',

        //
        'phase_id',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'deals';

    /**
     * @var array
     */
    protected $singleNestedEntities = [
        'source'     => DealSource::class,
        'department' => Department::class,
    ];

    /**
     * @var array
     */
    protected $multipleNestedEntities = [
        'custom_fields' => [
            'entity' => CustomFields::class,
            'type'   => self::NESTING_TYPE_ARRAY_OF_OBJECTS,
        ],
        'quotations'    => [
            'entity' => Quotation::class,
            'type'   => self::NESTING_TYPE_ARRAY_OF_OBJECTS,
        ],
    ];

    /**
     * @return mixed
     */
    public function insert()
    {
        $result = $this->connection()->post( $this->getEndpoint() . '.create', $this->jsonWithNamespace() );

        return $this->selfFromResponse( $result );
    }

    public function move( $phaseId )
    {
        $arguments = [
            'id'       => $this->attributes['id'],
            'phase_id' => $phaseId,
        ];

        $result = $this->connection()->post( $this->getEndpoint() . '.move', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }

    public function lose( $lostReasonId, $extraInfo )
    {
        $arguments = [
            'id' => $this->attributes['id'],
        ];

        if ( $lostReasonId ) {
            $arguments['reason_id'] = $lostReasonId;
        }

        if ( $extraInfo ) {
            $arguments['extra_info'] = $extraInfo;
        }

        $result = $this->connection()->post( $this->getEndpoint() . '.lose', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $result;
    }
}
