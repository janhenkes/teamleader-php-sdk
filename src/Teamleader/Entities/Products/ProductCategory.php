<?php

namespace Teamleader\Entities\Products;

use Teamleader\Actions\FindAll;
use Teamleader\Model;

class ProductCategory extends Model
{
    use FindAll;

    const TYPE = 'productcategory';

    protected $fillable = [
        'id',
        'name',
        'ledgers', // [{ "department": "", "ledger_account_number": "" }]
    ];

    /**
     * @var string
     */
    protected $endpoint = 'productCategories';
}
