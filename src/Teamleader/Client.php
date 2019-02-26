<?php

namespace Teamleader;

use Teamleader\Entities\Calendar\ActivityType;
use Teamleader\Entities\Calendar\Event;
use Teamleader\Entities\Company;
use Teamleader\Entities\Contact;
use Teamleader\Entities\CRM\BusinessType;
use Teamleader\Entities\CRM\Tag;
use Teamleader\Entities\Deal;
use Teamleader\Entities\DealPhase;
use Teamleader\Entities\DealSource;
use Teamleader\Entities\Invoicing\CreditNote;
use Teamleader\Entities\Invoicing\Invoice;
use Teamleader\Entities\Invoicing\PaymentTerm;
use Teamleader\Entities\Invoicing\TaxRate;
use Teamleader\Entities\Webhook;
use Teamleader\Entities\Migrate;

class Client {
    /**
     * The HTTP connection
     *
     * @var Connection
     */
    protected $connection;

    /**
     * Client constructor.
     *
     * @param Connection $connection
     */
    public function __construct( Connection $connection ) {
        $this->connection = $connection;
    }

    public function company( $attributes = [] ) {
        return new Company( $this->connection, $attributes );
    }

    public function contact( $attributes = [] ) {
        return new Contact( $this->connection, $attributes );
    }

    public function deal( $attributes = [] ) {
        return new Deal( $this->connection, $attributes );
    }

    public function dealPhase( $attributes = [] ) {
        return new DealPhase( $this->connection, $attributes );
    }

    public function dealSource( $attributes = [] ) {
        return new DealSource( $this->connection, $attributes );
    }

    public function webhook( $attributes = [] ) {
        return new Webhook( $this->connection, $attributes );
    }

    public function migrate( $attributes = [] ) {
        return new Migrate( $this->connection, $attributes );
    }

    public function activityType( $attributes = [] ) {
        return new ActivityType( $this->connection, $attributes );
    }

    public function businessType( $attributes = [] ) {
        return new BusinessType( $this->connection, $attributes );
    }
    
    public function tag( $attributes = [] ) {
        return new Tag( $this->connection, $attributes );
    }
  
    public function event( $attributes = [] ) {
        return new Event($this->connection, $attributes);
    }

    public function invoice( $attributes = [] ) {
        return new Invoice( $this->connection, $attributes );
    }

    public function creditNote( $attributes = [] ) {
        return new CreditNote( $this->connection, $attributes );
    }

    public function taxRate( $attributes = [] ) {
        return new TaxRate( $this->connection, $attributes );
    }

    public function paymentTerm( $attributes = [] ) {
        return new PaymentTerm( $this->connection, $attributes );
    }
}
