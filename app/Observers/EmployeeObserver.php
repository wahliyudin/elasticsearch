<?php

namespace App\Observers;

use App\Elastics\ElasticSearchBuilder;
use App\Models\Employee;

class EmployeeObserver
{
    public function __construct(
        protected ElasticSearchBuilder $elasticSearchBuilder
    ) {
    }

    /**
     * Handle the Employee "created" event.
     */
    public function created(Employee $employee): void
    {
        $this->elasticSearchBuilder->setModel($employee)->created();
    }

    /**
     * Handle the Employee "updated" event.
     */
    public function updated(Employee $employee): void
    {
        $this->elasticSearchBuilder->setModel($employee)->updated();
    }

    /**
     * Handle the Employee "deleted" event.
     */
    public function deleted(Employee $employee): void
    {
        $this->elasticSearchBuilder->setModel($employee)->deleted();
    }

    /**
     * Handle the Employee "restored" event.
     */
    public function restored(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "force deleted" event.
     */
    public function forceDeleted(Employee $employee): void
    {
        //
    }
}