<?php

namespace App\Observers;

use App\Elastics\ElasticSearchBuilder;
use App\Models\Product;

class ProductObserver
{
    public function __construct(
        protected ElasticSearchBuilder $elasticSearchBuilder
    ) {
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->elasticSearchBuilder->setModel($product)->created();
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->elasticSearchBuilder->setModel($product)->updated();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->elasticSearchBuilder->setModel($product)->deleted();
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
