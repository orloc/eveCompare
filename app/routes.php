<?php

/**
 * Routing Definitions
 */


$controller = new \EveCompare\Controller\MainController($app);


require_once __DIR__.'/middleware.php';

$factory = $controller->connect($app)
    ->before(checkPostContent());

$app->mount('', $factory);

