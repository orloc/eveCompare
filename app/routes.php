<?php

/**
 * Routing Definitions
 */


$controller = new \EveCompare\Controller\MainController($app);


$factory = $controller->connect($app);

$app->mount('', $factory);

