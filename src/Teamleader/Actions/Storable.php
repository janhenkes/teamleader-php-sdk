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
        $action = 'add';
        if (property_exists($this, 'createAction')) {
            $action = $this->createAction;
        }

        $result = $this->connection()->post( $this->getEndpoint() . '.' . $action, $this->jsonWithNamespace() );

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

    /**
     * @return mixed
     */
    public function remove() {
        $action = 'delete';
        if (property_exists($this, 'deleteAction')) {
            $action = $this->deleteAction;
        }

        $result = $this->connection()->post( $this->getEndpoint() . '.' . $action, $this->jsonWithNamespace() );
        if ( $result === 204 ) {
            return true;
        }

        return $result;
    }
}
