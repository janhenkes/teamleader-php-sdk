<?php

namespace Teamleader\Actions;

/**
 * Class Storable
 * @package Teamleader\Actions
 */
trait Storable {

    /**
     * @return mixed
     */
    public function save() {
        if ( $this->exists() ) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * @return mixed
     */
    public function insert() {
        $result = $this->connection()->post( $this->getEndpoint() . '.add', $this->jsonWithNamespace() );

        return $this->selfFromResponse( $result );
    }

    /**
     * @return mixed
     */
    public function update() {
        $result = $this->connection()->patch( $this->getEndpoint() . '.update' . '/' . urlencode( $this->id ), $this->jsonWithNamespace() );
        if ( $result === 200 ) {
            return true;
        }

        return $this->selfFromResponse( $result );
    }

}
