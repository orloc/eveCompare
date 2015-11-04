<?php

/*
 * Registers providers needed including our own
 */

$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__.'/../src/Resources/views'
]);

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/log/dev.log',
));

$app->register(new \EveCompare\Provider\EveTypeExtractorProvider(), [
    'evecompare.types.data_path' => __DIR__.'/data/typeids.csv',
    'evecompare.types.white_list' => $app['evecompare.config']['mineral_ids']
]);

