<?php

namespace App\Elastics\Contracts;

interface ElasticSearchInterface
{
    public function indexConfig(): array;
}
