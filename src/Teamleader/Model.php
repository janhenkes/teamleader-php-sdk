<?php

namespace Teamleader;

use Teamleader\Entities\CRM\Company;
use Teamleader\Entities\CRM\Contact;
use Teamleader\Entities\Deals\Deal;
use Teamleader\Entities\Deals\Quotation;
use Teamleader\Entities\General\User;
use Teamleader\Entities\General\WorkType;
use Teamleader\Entities\Invoicing\Invoice;
use JsonSerializable;
use Teamleader\Entities\Products\ProductCategory;
use Teamleader\Entities\Products\Product;
use Teamleader\Entities\Projects\Milestone;
use Teamleader\Entities\Projects\Project;
use Teamleader\Entities\Tasks\Task;
use Teamleader\Entities\TimeTracking\TimeTracking;
use Teamleader\Exceptions\ApiException;

/**
 * Class Model
 *
 * @package Teamleader
 */
abstract class Model implements JsonSerializable
{
    const NESTING_TYPE_ARRAY_OF_OBJECTS = 0;
    const NESTING_TYPE_NESTED_OBJECTS = 1;

    protected $references = [
        Contact::TYPE         => Contact::class,
        Company::TYPE         => Company::class,
        Deal::TYPE            => Deal::class,
        Invoice::TYPE         => Invoice::class,
        User::TYPE            => User::class,
        Task::TYPE            => Task::class,
        Project::TYPE         => Project::class,
        Milestone::TYPE       => Milestone::class,
        Quotation::TYPE       => Quotation::class,
        TimeTracking::TYPE    => TimeTracking::class,
        WorkType::TYPE        => WorkType::class,
        ProductCategory::TYPE => ProductCategory::class,
        Product::TYPE         => Product::class,
    ];

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var array The model's attributes
     */
    protected $attributes = [];

    /**
     * @var array The model's fillable attributes
     */
    protected $fillable = [];

    /**
     * @var string The URL endpoint of this model
     */
    protected $endpoint = '';

    /**
     * @var string Name of the primary key for this model
     */
    protected $primaryKey = 'id';

    /**
     * @var string Namespace of the model (for POST and PATCH requests)
     */
    protected $namespace = '';

    /**
     * @var array
     */
    protected $singleNestedEntities = [];

    /**
     * Array containing the name of the attribute that contains nested objects as key and an array with the entity name
     * and json representation type
     *
     * JSON representation of an array of objects (NESTING_TYPE_ARRAY_OF_OBJECTS) : [ {}, {} ]
     * JSON representation of nested objects (NESTING_TYPE_NESTED_OBJECTS): { "0": {}, "1": {} }
     *
     * @var array
     */
    protected $multipleNestedEntities = [];

    /**
     * @var bool
     */
    protected $isLoaded;

    /**
     * @var array
     */
    protected static $referencesCache = [];

    /**
     * Model constructor.
     *
     * @param Connection $connection
     * @param array      $attributes
     */
    public function __construct( Connection $connection, array $attributes = [] )
    {
        $this->connection = $connection;
        $this->isLoaded   = ! method_exists( $this, 'findById' );
        $this->fill( $attributes );
    }

    /**
     * Get the connection instance
     *
     * @return Connection
     */
    public function connection(): Connection
    {
        return $this->connection;
    }

    /**
     * Get the model's attributes
     *
     * @return array
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * Fill the entity from an array
     *
     * @param array $attributes
     */
    protected function fill( array $attributes ): void
    {
        $attributes = $this->fillableFromArray( $attributes );

        foreach ( $attributes as $key => $attribute ) {
            $this->setAttribute( $key, $attribute );
        }

        if ( ! empty( $attributes ) ) {
            $loadedAttributes = $attributes;
            unset( $loadedAttributes[ $this->primaryKey ] );
            $this->isLoaded = ! empty( $loadedAttributes );
        }
    }

    /**
     * Get the fillable attributes of an array
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function fillableFromArray( array $attributes ): array
    {
        if ( count( $this->fillable ) > 0 ) {
            return array_intersect_key( $attributes, array_flip( $this->fillable ) );
        }

        return $attributes;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isFillable( string $key ): bool
    {
        return in_array( $key, $this->fillable );
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    protected function setAttribute( string $key, $value ): void
    {
        if ( $this->isFillable( $key ) ) {
            $this->attributes[ $key ] = $this->addReferences( $value );
        }
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private function addReferences( $data )
    {
        if ( ! is_array( $data ) ) {
            return $data;
        }

        if ( count( $data ) === 2 && array_key_exists( 'type', $data ) && array_key_exists( 'id', $data ) ) {
            if ( ! array_key_exists( $data['type'], $this->references ) ) {
                return $data;
            }

            if ( isset( static::$referencesCache[ $data['type'] ][ $data['id'] ] ) ) {
                return static::$referencesCache[ $data['type'] ][ $data['id'] ];
            }
            $class                                                   = $this->references[ $data['type'] ];
            static::$referencesCache[ $data['type'] ][ $data['id'] ] = new $class( $this->connection, [ 'id' => $data['id'] ] );

            return static::$referencesCache[ $data['type'] ][ $data['id'] ];
        }

        foreach ( $data as $key => $value ) {
            $data[ $key ] = $this->addReferences( $value );
        }

        return $data;
    }

    public function load()
    {
        if ( ! $this->isLoaded && method_exists( $this, 'findById' ) ) {
            try {
                $this->findById();
            } catch ( ApiException $apiException ) {
                $this->isLoaded = true;
            }
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get( string $key )
    {
        if ( ! $this->isLoaded && method_exists( $this, 'findById' ) ) {
            if ( $key === $this->primaryKey && isset( $this->attributes[ $key ] ) ) {
                return $this->attributes[ $key ];
            }

            try {
                $this->findById();
            } catch ( ApiException $apiException ) {
                $this->isLoaded = true;
            }
        }

        if ( isset( $this->attributes[ $key ] ) ) {
            return $this->attributes[ $key ];
        }
    }

    /**
     * @param string $key
     * @param        $value
     */
    public function __set( string $key, $value ): void
    {
        $this->setAttribute( $key, $value );
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        if ( ! array_key_exists( $this->primaryKey, $this->attributes ) ) {
            return false;
        }

        return ! empty( $this->attributes[ $this->primaryKey ] );
    }

    /**
     * @return string
     */
    public function json(): string
    {
        $array = $this->getArrayWithNestedObjects();

        return json_encode( $array, JSON_FORCE_OBJECT );
    }

    /**
     * @return string
     */
    public function jsonWithNamespace(): string
    {
        if ( $this->namespace !== '' ) {
            return json_encode( [ $this->namespace => $this->getArrayWithNestedObjects() ], JSON_FORCE_OBJECT );
        }

        return $this->json();
    }

    private function getArrayWithNestedObjects( bool $useAttributesAppend = true ): array
    {
        $result                 = [];
        $multipleNestedEntities = $this->getMultipleNestedEntities();

        foreach ( $this->attributes as $attributeName => $attributeValue ) {
            if ( ! is_object( $attributeValue ) ) {
                $result[ $attributeName ] = $attributeValue;
            }

            if ( array_key_exists( $attributeName, $this->getSingleNestedEntities() ) ) {
                $result[ $attributeName ] = $attributeValue->attributes;
            }

            if ( array_key_exists( $attributeName, $multipleNestedEntities ) ) {
                $attributeNameToUse = $attributeName;
                if ( $useAttributesAppend ) {
                    $attributeNameToUse .= '_attributes';
                }

                $result[ $attributeNameToUse ] = [];
                foreach ( $attributeValue as $attributeEntity ) {
                    $result[ $attributeNameToUse ][] = $attributeEntity->attributes;

                    if ( $multipleNestedEntities[ $attributeName ]['type'] === self::NESTING_TYPE_NESTED_OBJECTS ) {
                        $result[ $attributeNameToUse ] = (object) $result[ $attributeNameToUse ];
                    }
                }

                if (
                    $multipleNestedEntities[ $attributeName ]['type'] === self::NESTING_TYPE_NESTED_OBJECTS
                    && empty( $result[ $attributeNameToUse ] )
                ) {
                    $result[ $attributeNameToUse ] = new \StdClass();
                }
            }
        }

        return $result;
    }

    /**
     * Create a new object with the response from the API
     *
     * @param array $response
     *
     * @return static
     */
    public function makeFromResponse( array $response ): self
    {
        $entity = new static( $this->connection );
        $entity->selfFromResponse( $response );

        return $entity;
    }

    /**
     * Recreate this object with the response from the API
     *
     * @param array $response
     *
     * @return static
     */
    public function selfFromResponse( array $response ): self
    {
        if ( isset( $response['data'] ) ) {
            $response = $response['data'];
        }

        $this->fill( $response );

        foreach ( $this->getSingleNestedEntities() as $key => $value ) {
            if ( isset( $response[ $key ] ) ) {
                $entityName = $value;
                $this->$key = new $entityName( $this->connection, $response[ $key ] );
            }
        }

        foreach ( $this->getMultipleNestedEntities() as $key => $value ) {
            if ( isset( $response[ $key ] ) ) {
                $entityName        = $value['entity'];
                $instaniatedEntity = new $entityName( $this->connection );
                $this->$key        = $instaniatedEntity->collectionFromResult( $response[ $key ] );
            }
        }

        return $this;
    }

    /**
     * @param bool|array $result
     *
     * @return array
     */
    public function collectionFromResult( $result ): array
    {
        if ( ! $result ) {
            return [];
        }

        if ( isset( $result['data'] ) ) {
            $result = $result['data'];
        }

        // If we have one result which is not an assoc array, make it the first element of an array for the
        // collectionFromResult function so we always return a collection from filter
        if ( (bool) count( array_filter( array_keys( $result ), 'is_string' ) ) ) {
            $result = [ $result ];
        }

        $collection = [];
        foreach ( $result as $r ) {
            $collection[] = static::makeFromResponse( $r );
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function getSingleNestedEntities(): array
    {
        return $this->singleNestedEntities;
    }

    /**
     * @return array
     */
    public function getMultipleNestedEntities(): array
    {
        return $this->multipleNestedEntities;
    }

    /**
     * Make var_dump and print_r look pretty
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        $result = [];
        foreach ( $this->fillable as $attribute ) {
            $result[ $attribute ] = $this->$attribute;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Determine if an attribute exists on the model
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset( string $name ): bool
    {
        return ( isset( $this->attributes[ $name ] ) && ! is_null( $this->attributes[ $name ] ) );
    }

    public function jsonSerialize()
    {
        if ( ! defined( 'static::TYPE' ) ) {
            return $this->getArrayWithNestedObjects();
        }

        $primaryKey = $this->primaryKey;

        return (object) [
            'type'      => static::TYPE,
            $primaryKey => $this->$primaryKey,
        ];
    }
}
