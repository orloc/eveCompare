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
        $uri = $this->getFullRequestUri(sprintf("market/%s/types/%s/history/", $regionId, $typeId));

        $response = $this->tryRequest($uri);

        $formattedResponse = $this->formatResponse($response);

        return $formattedResponse['items'];
    }

    /**
     * Fetches Regions from the CREST Api - returns formated array
     * @return array
     */
    public function fetchRegions(){

        $uri = $this->getFullRequestUri('regions/');

        $response = $this->tryRequest($uri);

        $formattedResponse = $this->formatResponse($response);

        /**
         * We need to grab the ID
         */
        $tmp = [];
        foreach ($formattedResponse['items'] as $i){
            $tmp[] = [
                'name' => $i['name'],
                'id' => $this->parseRegionUri($i['href'])
            ] ;
        }

        return $tmp;

    }

    protected function tryRequest($uri){
        try {
            $response = $this->client->get($uri);
        } catch (BadResponseException $e){
            $this->log->error(sprintf('ERROR %s - Fetching %s with %s', $e->getCode(), $uri, $e->getMessage()));

            throw $e;
        }

        return $response;
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