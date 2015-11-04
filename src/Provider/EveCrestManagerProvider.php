<?php

namespace EveCompare\Provider;

use EveCompare\Service\EveCrestManager;
use GuzzleHttp\Client;
use Silex\Application;
use Silex\ServiceProviderInterface;

class EveCrestManagerProvider implements ServiceProviderInterface {

    public function register(Application $app){
        $app['evecompare.eve_crest_manager'] = $app->share(function() use ($app){
            if (!isset($app['evecompare.crest_base_uri'])){
                throw new \InvalidArgumentException('evecompare.crest_base_uri must be defined');
            }

            /**
             * @TODO validate guzzle options
             */
            $crest = new EveCrestManager(
                new Client(
                    isset($app['evecompare.guzzle_overrides'])
                        ? $app['evecompare.guzzle_overrides'] : []
                ),
                $app['evecompare.crest_base_uri']
            );

            return $crest;
        });
    }

    public function boot(Application $app){

    }
}