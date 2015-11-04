<?php

namespace EveCompare\Service;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;
use Monolog\Logger;

class EveCrestManager {

    protected $client;

    protected $base_uri;

    protected $log;

    public function __construct(Client $client, Logger $logger, $base_uri){
        $this->client = $client;
        $this->log = $logger;

        $this->base_uri = $base_uri;
    }

    public function fetchMarketHistory($typeId, $regionId){

    }

    public function fetchRegions(){

        $uri = $this->getFullRequestUri('regions/');

        try {
            $response = $this->client->get($uri);
        } catch (BadResponseException $e){
            $this->log->error(sprintf('ERROR %s - Fetching %s with %s', $e->getCode(), $uri, $e->getMessage()));

            throw $e;
        }

        $formattedResponse = $this->formatResponse($response);

        $tmp = [];
        foreach ($formattedResponse['items'] as $i){
            $tmp[] = [
                'name' => $i['name'],
                'id' => $this->parseRegionUri($i['href'])
            ] ;
        }

        return $tmp;

    }

    protected function parseRegionUri($uri){
        // ids are always 8 digits with a trailing slash
        return substr($uri, strlen($uri) - 9, 8);
    }

    protected function formatResponse(Response $response){
        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }

    protected function getFullRequestUri($path){
        return join('/', [$this->base_uri, $path]);
    }
}