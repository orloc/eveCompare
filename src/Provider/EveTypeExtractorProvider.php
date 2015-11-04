<?php

namespace EveCompare\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use EveCompare\Service\EveTypeExtractor;

class EveTypeExtractorProvider implements ServiceProviderInterface {

    public function register(Application $app){
        $app['evecompare.eve_extractor'] = $app->share(function() use ($app){
            if (!isset($app['evecompare.types.data_path'])){
                throw new \InvalidArgumentException('evecompare.types.data_path must be defined');
            }

            $extractor = new EveTypeExtractor(
                $app['evecompare.types.data_path'],
                isset($app['evecompare.types.white_list'])
                    ? $app['evecompare.types.white_list']
                    : []
            );

            return $extractor;
        });
    }

    public function boot(Application $app){

    }
}