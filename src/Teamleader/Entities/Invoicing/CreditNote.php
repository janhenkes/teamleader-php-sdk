<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Model;

class CreditNote extends Model
{
    use FindAll;
    use FindById;
    use Storable;

    const TYPE = 'credit_note';

    protected $fillable = [
        'id',
        'department',
        'credit_note_number',
        'credit_note_date',
        'status',
        'invoice',
        'paid',
        'paid_at',
        'invoicee',
        'discounts',
        'total',
        'grouped_lines',
        'currency_exchange_rate',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'creditNotes';

    public function download($format = "pdf")
    {
        $arguments = [
            'id'     => $this->attributes['id'],
            'format' => $format,
        ];

        $result = $this->connection()->post($this->getEndpoint() . '.download', json_encode($arguments, JSON_FORCE_OBJECT));

        return $result;
    }

    public function file($format = "pdf")
    {
        $result = $this->download($format);

        if (isset($result['data']) && isset($result['data']['location'])) {
            return file_get_contents($result['data']['location']);
        }

        return false;
    }
}
