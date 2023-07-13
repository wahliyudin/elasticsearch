<?php

namespace App\Elastics;

use App\Elastics\Contracts\ElasticSearchInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Database\Eloquent\Model;

class ElasticSearchBuilder
{
    protected Client $clientBuilder;

    private Model $model;

    public function __construct()
    {
        $this->clientBuilder = ClientBuilder::create()
            ->setHosts(config('database.connections.elasticsearch.hosts'))
            ->build();
    }

    public function setModel(Model $model)
    {
        if (!$model instanceof ElasticSearchInterface) {
            throw new Exception("The model must implement " . ElasticSearchInterface::class);
        }
        $this->model = $model;
        return $this;
    }

    private function params(array $params = [])
    {
        $defalut = [
            'index' => $this->getIndex(),
            'type' => $this->getType(),
            'id' => $this->getKey(),
        ];
        return array_merge($defalut, $params);
    }

    private function getKey()
    {
        return $this->model->getKey();
    }

    public function getIndex()
    {
        $config = $this->model->indexConfig();
        if (!isset($config['index'])) {
            throw new Exception("IndexConfig must contain index");
        }
        return $config['index'];
    }

    public function getType()
    {
        $config = $this->model->indexConfig();
        if (!isset($config['type'])) {
            throw new Exception("IndexConfig must contain type");
        }
        return $config['type'];
    }

    public function created()
    {
        $params = $this->params([
            'body' => $this->model->getAttributes()
        ]);
        $this->clientBuilder->index($params);
    }

    public function updated()
    {
        $params = $this->params([
            'body' => [
                'doc' => $this->model->getAttributes()
            ],
        ]);
        $this->clientBuilder->update($params);
    }

    public function deleted()
    {
        $this->clientBuilder->delete($this->params());
    }

    public function cleared()
    {
        $params = [
            'index' => $this->getIndex(),
            'body' => [
                'query' => [
                    'match_all' => (object)[],
                ],
            ],
        ];
        $this->clientBuilder->deleteByQuery($params);
    }

    public function search($keyword = null)
    {
        $params = [
            'index' => $this->getIndex(),

        ];
        if ($keyword) {
            $params = array_merge($params, [
                'body' => [
                    'query' => [
                        'query_string' => [
                            'query' => $keyword,
                        ],
                    ],
                ],
            ]);
        }

        $response = $this->clientBuilder->search($params);

        return $response['hits']['hits'];
    }

    // public function createIndex()
    // {
    //     $client = $this->getElasticsearchClient();
    //     if ($client->indices()->exists(['index' => $this->indexConfig['index']])) {
    //         return;
    //     }
    //     $params = [
    //         'index' => $this->indexConfig['index'],
    //         'body' => [
    //             'settings' => [
    //                 'number_of_shards' => 5,
    //                 'number_of_replicas' => 2,
    //             ],
    //         ]
    //     ];
    //     $client->indices()->create($params);
    // }

    // public function updateSettingIndex()
    // {
    //     $client = $this->getElasticsearchClient();
    //     $params = [
    //         'index' => $this->indexConfig['index'],
    //         'body' => [
    //             'settings' => [
    //                 'number_of_shards' => 5,
    //                 'number_of_replicas' => 2,
    //             ],
    //         ]
    //     ];
    //     $client->indices()->putSettings($params);
    // }
}
