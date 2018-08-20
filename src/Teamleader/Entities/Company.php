<?php
/**
 * Created by PhpStorm.
 * User: janhenkes
 * Date: 20/08/2018
 * Time: 14:27
 */

namespace Teamleader\Entities;

use Teamleader\Actions\Storable;
use Teamleader\Model;

class Company extends Model {
    use Storable;

    protected $fillable = [
        'id',
        'name',
        'business_type_id',
        'vat_number',
        'local_business_number',
        'emails',
        'telephones',
        'website',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'companies';
}