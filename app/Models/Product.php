<?php

namespace App\Models;

use App\Elastics\Contracts\ElasticSearchInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements ElasticSearchInterface
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function indexConfig(): array
    {
        return [
            'index' => 'products',
            'type' => '_doc',
        ];
    }
}
