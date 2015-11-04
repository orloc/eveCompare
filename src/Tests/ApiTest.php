<?php

class ApiTests extends \Silex\WebTestCase {

    public function createApplication(){
        $app = require __DIR__.'/app/app.php';
        $app['debug'] = true;

        unset($app['exception_handler']);

        return $app;
    }

    public function testPageResult(){
        $client = $this->createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Eve Compare")'));
        $this->assertCount(1, $crawler->filter('form'));
    }


}