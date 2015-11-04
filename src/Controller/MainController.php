<?php

namespace EveCompare\Controller;


use Silex\Application;
use Silex\ControllerProviderInterface;

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
        $controllers->get('/', [$this, 'defaultAction']);

        return $controllers;
    }

    /**
     * Default page action
     * @return mixed
     */
    public function defaultAction(){
        $extractor = $this->app['evecompare.eve_extractor'];

        $csv = $extractor->readFile()->asJson();

        var_dump($csv);die;

        return $this->app['twig']->render('page.html.twig');
    }
}