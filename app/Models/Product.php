<?php

namespace App\Models;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    protected $indexConfig = [
        'index' => 'products',
        'type' => '_doc',
    ];

    public function getElasticsearchClient()
    {
        return ClientBuilder::create()
            ->setHosts(config('database.connections.elasticsearch.hosts'))
            ->build();
    }

    public function addToElasticsearch()
    {
        $client = $this->getElasticsearchClient();
        $params = [
            'index' => $this->indexConfig['index'],
            'type' => $this->indexConfig['type'],
            'id' => $this->id,
            'body' => [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
            ],
        ];
        $client->index($params);
    }

    public function deleteIndex()
    {
        $client = $this->getElasticsearchClient();
        $params = [
            'index' => $this->indexConfig['index'],
            'type' => $this->indexConfig['type'],
            'id' => $this->id,
        ];
        $client->delete($params);
    }

    public function updateIndex()
    {
        $client = $this->getElasticsearchClient();
        $params = [
            'index' => $this->indexConfig['index'],
            'type' => $this->indexConfig['type'],
            'id' => $this->id,
            'body' => [
                'doc' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'price' => $this->price,
                ]
            ],
        ];
        $client->update($params);
    }

    public function cleared()
    {
        $client = $this->getElasticsearchClient();
        $params = [
            'index' => $this->indexConfig['index'],
            'body' => [
                'query' => [
                    'match_all' => (object)[],
                ],
            ],
        ];
        $client->deleteByQuery($params);
    }
}
