<?php

namespace App\Models;

use App\Elastics\Contracts\ElasticSearchInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model implements ElasticSearchInterface
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function indexConfig(): array
    {
        return [
            'index' => 'employees',
            'type' => '_doc',
        ];
    }
}
