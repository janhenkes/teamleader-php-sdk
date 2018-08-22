<?php
/**
 * Created by PhpStorm.
 * User: janhenkes
 * Date: 20/08/2018
 * Time: 14:27
 */

namespace Teamleader\Entities;

use Teamleader\Actions\Storable;
use Teamleader\Exceptions\ApiException;
use Teamleader\Model;

class Contact extends Model {
    use Storable;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'emails',
        'salutation',
        'telephones',
        'website',
        'addresses',
        'language',
        'gender',
        'birthdate',
        'iban',
        'bic',
        'remarks',
        'tags',
        'custom_fields',
        'marketing_mails_consent',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'contacts';

    /**
     * @param array $arguments
     *
     * @return mixed
     */
    public function linkToCompany(
        $arguments = [
            'company_id'     => '',
            'position'       => '',
            'decision_maker' => true,
        ]
    ) {
        $arguments['id'] = $this->attributes['id'];

        $result = $this->connection()->post( $this->getEndpoint() . '.linkToCompany', json_encode( $arguments, JSON_FORCE_OBJECT ) );

        return $this->selfFromResponse( $result );
    }
}