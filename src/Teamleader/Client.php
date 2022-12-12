<?php

namespace Teamleader;

use Teamleader\Entities\Calendar\ActivityType;
use Teamleader\Entities\Calendar\Event;
use Teamleader\Entities\CRM\Company;
use Teamleader\Entities\CRM\Contact;
use Teamleader\Entities\CRM\BusinessType;
use Teamleader\Entities\CRM\Tag;
use Teamleader\Entities\Deals\Deal;
use Teamleader\Entities\Deals\DealPhase;
use Teamleader\Entities\Deals\DealSource;
use Teamleader\Entities\Deals\LostReason;
use Teamleader\Entities\Deals\Quotation;
use Teamleader\Entities\General\CustomFieldDefinition;
use Teamleader\Entities\General\Department;
use Teamleader\Entities\General\User;
use Teamleader\Entities\General\WorkType;
use Teamleader\Entities\Invoicing\CreditNote;
use Teamleader\Entities\Invoicing\Invoice;
use Teamleader\Entities\Invoicing\PaymentTerm;
use Teamleader\Entities\Invoicing\TaxRate;
use Teamleader\Entities\Invoicing\WithholdingTaxRate;
use Teamleader\Entities\Other\Webhook;
use Teamleader\Entities\Other\Migrate;
use Teamleader\Entities\Tasks\Task;
use Teamleader\Entities\Projects\Project;
use Teamleader\Entities\Projects\Milestone;
use Teamleader\Entities\TimeTracking\TimeTracking;
use Teamleader\Entities\Products\ProductCategory;
use Teamleader\Entities\Products\Product;

class Client
{
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
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function company(array $attributes = [])
    {
        return new Company($this->connection, $attributes);
    }

    public function contact(array $attributes = [])
    {
        return new Contact($this->connection, $attributes);
    }

    public function deal(array $attributes = [])
    {
        return new Deal($this->connection, $attributes);
    }

    public function dealPhase(array $attributes = [])
    {
        return new DealPhase($this->connection, $attributes);
    }

    public function dealSource(array $attributes = [])
    {
        return new DealSource($this->connection, $attributes);
    }

    public function user(array $attributes = [])
    {
        return new User($this->connection, $attributes);
    }

    public function webhook(array $attributes = [])
    {
        return new Webhook($this->connection, $attributes);
    }

    public function migrate(array $attributes = [])
    {
        return new Migrate($this->connection, $attributes);
    }

    public function activityType(array $attributes = [])
    {
        return new ActivityType($this->connection, $attributes);
    }

    public function businessType(array $attributes = [])
    {
        return new BusinessType($this->connection, $attributes);
    }

    public function tag(array $attributes = [])
    {
        return new Tag($this->connection, $attributes);
    }

    public function event(array $attributes = [])
    {
        return new Event($this->connection, $attributes);
    }

    public function invoice(array $attributes = [])
    {
        return new Invoice($this->connection, $attributes);
    }

    public function creditNote(array $attributes = [])
    {
        return new CreditNote($this->connection, $attributes);
    }

    public function taxRate(array $attributes = [])
    {
        return new TaxRate($this->connection, $attributes);
    }

    public function paymentTerm(array $attributes = [])
    {
        return new PaymentTerm($this->connection, $attributes);
    }

    public function withholdingTaxRate(array $attributes = [])
    {
        return new WithholdingTaxRate($this->connection, $attributes);
    }

    public function department(array $attributes = [])
    {
        return new Department($this->connection, $attributes);
    }

    public function task(array $attributes = [])
    {
        return new Task($this->connection, $attributes);
    }

    public function project(array $attributes = [])
    {
        return new Project($this->connection, $attributes);
    }

    public function milestone(array $attributes = [])
    {
        return new Milestone($this->connection, $attributes);
    }

    public function timeTracking(array $attributes = [])
    {
        return new TimeTracking($this->connection, $attributes);
    }

    public function workType(array $attributes = [])
    {
        return new WorkType($this->connection, $attributes);
    }

    public function productCategory(array $attributes = [])
    {
        return new ProductCategory($this->connection, $attributes);
    }

    public function product(array $attributes = [])
    {
        return new Product($this->connection, $attributes);
    }

    public function customFields(array $attributes = [])
    {
        return new CustomFieldDefinition($this->connection, $attributes);
    }

    public function quotation(array $attributes = [])
    {
        return new Quotation($this->connection, $attributes);
    }

    public function lostReason(array $attributes = [])
    {
        return new LostReason($this->connection, $attributes);
    }
}
