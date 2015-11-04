<?php

use \Symfony\Component\HttpKernel\Log\NullLogger;

class EveTypeManagerTest extends PHPUnit_Framework_TestCase{

    protected $path;
    protected $mock_log;

    public function setUp(){
        $this->mock_log = new NullLogger();
        $this->path = __DIR__.'/testData/typeids.csv';
    }

    /**
     * @expectedException \Exception
     */
    public function testBadCallOrder(){
        $manager = $this->getManager($this->path);

        $manager->asJson();

    }

    /**
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testBadPath(){
        $manager = $this->getManager('');

        $manager->readFile()->asJson();

    }

    public function testAsJson(){
        $manager = $this->getManager($this->path);

        $response = $manager->readFile()->asJson();

        $this->isJson($response, 'Response is not properly formatted json');
    }

    public function testArrayResponse(){
        $manager = $this->getManager($this->path);

        $response = $manager->readFile()->asArray();

        $this->assertTrue(is_array($response), 'Response is not an array');

    }

    protected function getManager($path, array $whiteList = []){
        $crest = new \EveCompare\Service\EveTypeExtractor(
            $path,
            $whiteList
        );

        return $crest;
    }

}
