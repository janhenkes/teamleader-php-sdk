<?php

namespace Teamleader\Entities\Invoicing;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class CreditNote extends Model
{
    use FindAll;

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
}
