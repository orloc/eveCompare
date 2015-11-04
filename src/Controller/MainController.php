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

        return $controllers;
    }

    /**
     * Default page action
     * @return mixed
     */
    public function defaultAction(){
        return $this->app['twig']->render('page.html.twig');
    }

    public function getMineralTypesAction(){
        $extractor = $this->app['evecompare.eve_extractor'];

        $jsonCsv = $extractor->readFile()->asJson();

        return new Response($jsonCsv, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

}