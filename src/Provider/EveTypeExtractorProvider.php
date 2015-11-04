<?php

namespace EveCompare\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use EveCompare\Service\EveTypeExtractor;

class EveTypeExtractorProvider implements ServiceProviderInterface {

    public function register(Application $app){
        $app['evecompare.eve_extractor'] = $app->share(function() use ($app){
            $extractor = new EveTypeExtractor($app['evecompare.types.data_path']);

            return $extractor;
        });
    }

    public function boot(Application $app){

    }
}