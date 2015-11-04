<?php

namespace EveCompare\Controller;


use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package EveCompare\Controller
 */
class MainController implements ControllerProviderInterface {

    protected $app;

    public function __construct(Application $app){
        $this->app = $app;
    }

    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app){
        $controllers = $app['controllers_factory'];

        $controllers->get('/', [$this, 'defaultAction']);
        $controllers->get('/mineral_transaction', [$this, 'getMineralTypesAction']);
        $controllers->get('/regions', [$this, 'getRegionsAction']);

        return $controllers;
    }

    /**
     * Default page action
     * @return mixed
     */
    public function defaultAction(){
        return $this->app['twig']->render('page.html.twig');
    }

    /**
     * Returns a json response with generic mineral types
     * @return Response
     */
    public function getMineralTypesAction(){
        $extractor = $this->app['evecompare.eve_extractor'];

        $jsonCsv = $extractor->readFile()->asJson();

        return new Response($jsonCsv, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    public function getRegionsAction(){

        $crest = $this->app['evecompare.crest'];

        $regionResponse = $crest->fetchRegions();

        return new Response(json_encode($regionResponse), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

}