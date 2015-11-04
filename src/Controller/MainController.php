<?php

namespace EveCompare\Controller;


use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
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
        $controllers->post('/market_history', [$this, 'getMarketHistoryAction']);

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

        try {
            $jsonCsv = $extractor->readFile()->asJson();
        } catch (\Exception $e){
            return $this->getErrorResponse($e->getMessage());
        }

        return $this->getJsonResponse($jsonCsv);

    }

    /**
     * Returns a json response with available regions to select from
     * @return Response
     */
    public function getRegionsAction(){

        $crest = $this->app['evecompare.crest'];

        try {
            $regionResponse = $crest->fetchRegions();
        } catch(\Exception $e){
            return $this->getErrorResponse($e->getMessage(), $e->getCode());
        }

        return $this->getJsonResponse(json_encode($regionResponse));
    }

    /**
     * Returns By day summary of item data
     * @param Request $request
     * @return Response
     */
    public function getMarketHistoryAction(Request $request)
    {
        $regionId = $request->request->get('region', null);
        $typeId = $request->request->get('type', null);

        if ($regionId === null || $typeId === null) {
            return $this->getErrorResponse('Region and type ID required');
        }

        $crest = $this->app['evecompare.crest'];

        $marketHistory = $crest->fetchMarketHistory(36, 10000029);

        return $this->getJsonResponse(json_encode($marketHistory));

    }

    protected function getJsonResponse($body, $code = 200, array $headers = []){
        return new Response($body, $code, array_merge([
            'Content-Type' => 'application/json'
        ], $headers));

    }

    protected function getErrorResponse($message, $code = 400){
       return $this->getJsonResponse(json_encode([
           'message' => $message,
           'code' => $code
       ]), $code);
    }

}