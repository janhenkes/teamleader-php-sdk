<?php

namespace Teamleader\Entities\Files;

use Teamleader\Actions\FindAll;
use Teamleader\Actions\FindById;
use Teamleader\Model;

class File extends Model {

    use FindAll;
    use FindById;

    const TYPE = 'file';

    protected $fillable = [
        'id',
        'subject',
        'name',
        'mime_type',
        'size',
        'updated_at',
        'uploaded_by',
        'folder',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'files';

    public function upload( array $attributes, string $fileContent ) {
        $uploadLink = $this->connection()->post( $this->getEndpoint() . '.upload', json_encode( $attributes ) );

        if ( empty( $uploadLink['data']['location'] ) ) {
            return false;
        }

        $result = $this->connection()->post( $uploadLink['data']['location'], $fileContent, contentType: null );

        return $this->selfFromResponse( $result );
    }
}
