<?php

// --------------------------------------------------------------------------------------------

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Router\Utils\DebugUtils;
use Router\Router;

DebugUtils::pre(http_response_code());

try {

$router = new Router;
$router
  ->addRoute('GET', '/', 'Tests\Controller\DefaultController::home')
  ->setName('default')
;
$router
  ->addRoute('GET', '/home', 'Tests\Controller\DefaultController::home')
  ->setName('home')
;
$router
  ->addRoute('GET', '/about', 'Tests\Controller\DefaultController::about')
  ->setName('about')
;
$router
  ->addRoute('GET', '/e/e', 'Tests\Controller\DefaultController::about')
  ->setName('aboutE')
;
$router
  ->addRoute('GET', '/about/{name}', 'Tests\Controller\DefaultController::aboutName')
  ->setName('aboutName')
;
$router
  ->addRoute('GET', '/contact', 'Tests\Controller\DefaultController::contact')
  ->setName('contact')
;



DebugUtils::jum($router->getHttpRoute());
DebugUtils::jum($router->getCurrentRoute());


$router->run();

DebugUtils::pre($router);

} catch(\Exception $e) {
  echo '<pre>' . $e . '</pre>';
}

exit();

// --------------------------------------------------------------------------------------------

?>