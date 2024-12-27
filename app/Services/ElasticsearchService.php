<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
                                     ->setHosts([ 'http://localhost:9200'])
                                     ->build();
    }

    public function index($index, $data)
    {
        return $this->client->index([
            'index' => $index,
            'body'  => $data,
        ]);
    }

    public function search($index, $query)
    {
        return $this->client->search([
            'index' => $index,
            'body'  => $query,
        ]);
    }

    public function delete($index, $id)
    {
        return $this->client->delete([
            'index' => $index,
            'id'    => $id,
        ]);
    }
}
