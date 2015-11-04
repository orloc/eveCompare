<?php

use \Symfony\Component\HttpKernel\Log\NullLogger;

class EveCrestManagerTest extends PHPUnit_Framework_TestCase{

    const BASE_URI = 'https://public-crest.eveonline.com';

    protected $mock_log;

    public function setUp(){
        $this->mock_log = new NullLogger();
    }

    public function testFetchRegions(){
        $crest = $this->getManager();

        $result = $crest->fetchRegions();

        $this->assertCount(100, $result, 'Expected 100 regions');

    }

    /**
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testBadComboMarketHistory(){

        $crest = $this->getManager();

        $crest->fetchMarketHistory(34, 10000000);
    }

    public function testMarketHistory(){

        $crest = $this->getManager();

        $response = $crest->fetchMarketHistory(34, 10000002);

        $testResult = array_shift($response);

        $this->assertArrayHasKey('lowPrice', $testResult);
        $this->assertArrayHasKey('highPrice', $testResult);
        $this->assertArrayHasKey('avgPrice', $testResult);
    }

    protected function getManager(){
        $crest = new \EveCompare\Service\EveCrestManager(
            new \GuzzleHttp\Client(),
            $this->mock_log,
            self::BASE_URI
        );

        return $crest;
    }

}