<?php

namespace Teamleader\Entities\Products;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Actions\Storable;
use Teamleader\Exceptions\ApiException;
use Teamleader\Model;

class Product extends Model
{
    use FindAll;
    use Storable;
    use FindById;

    const TYPE = 'product';

    protected $fillable = [
        'id',
        'name',
        'description',
        'code',
        'purchase_price', // { "amount": "", "currency": "" }
        'selling_price',  // { "amount": "", "currency": "" }
    ];

    /**
     * @var string
     */
    protected $endpoint = 'products';

    /**
     * @throws ApiException
     */
    public function update()
    {
        throw new ApiException('Method not implemented in Teamleader API');
    }

    /**
     * @throws ApiException
     */
    public function remove()
    {
        throw new ApiException('Method not implemented in Teamleader API');
    }
}
