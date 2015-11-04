<?php

namespace EveCompare\Service;


use GuzzleHttp\Client;

class EveCrestManager {

    protected $client;

    protected $base_uri;

    public function __construct(Client $client, $base_uri){
        $this->client = $client;
        $this->base_uri = $base_uri;
    }

    public function fetchMarketHistory($typeId, $regionId){

    }

    public function fetchRegions(){

        $this->getFullRequestUri('regions');


    }

    protected function getFullRequestUri($path){
        var_dump($this->base_uri, $path);die;
    }
}