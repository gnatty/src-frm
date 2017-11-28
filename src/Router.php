<?php

namespace Router;

use Router\Route;
use Router\Exception\RouterException;
use Router\Message\RouterExceptionMessage;
use Router\Utils\DebugUtils;
use Router\Param;

class Router
{

  /**
  *
  */
  const ALLOWED_METHOD = 'GET|POST';
  const DIR_NAME_RETURN = ['\\', '/'];

  /**
  *
  */
  private $baseDir;
  private $httpMethod;
  private $httpRoute;
  private $httpQuery;
  private $routes = array();
  private static $currentRoute;

  /**
  *
  */
  public function __construct() {
    $this->init();
  }

  public function getRoutes() {
    return $this->routes;
  }

  public function getHttpRoute() {
    return $this->httpRoute;
  }

  public function getHttpMethod() {
    return $this->httpMethod;
  }
  
  public static function getCurrentRoute() {
    return Router::$currentRoute;
  }

  /**
  *
  */
  private function init() {
    // --- DEFAULT PARAMS
    $this->baseDir        = dirname($_SERVER['SCRIPT_NAME']);
    $this->httpMethod     = $_SERVER['REQUEST_METHOD'];
    $this->parseHttpQuery();
    $this->parseHttpRoute();
  }

  /**
  *
  */
  public function parseHttpQuery() {
    // -- TODO.
    $httpUrl          = parse_url($_SERVER['REQUEST_URI']);
    $this->httpQuery  = !empty($httpUrl['query'])   ? $httpUrl['query']   : '';
  }

  /**
  *
  */
  public function parseHttpRoute() {
    if( empty($_SERVER['REDIRECT_URL']) ) {
      $httpRoute = '/';
    } else {
      $httpRoute = $_SERVER['REDIRECT_URL'];
      if( !in_array($this->baseDir, self::DIR_NAME_RETURN) ) {
        $httpRoute = substr($httpRoute, strlen($this->baseDir));
      }
      $httpRoute = rtrim($httpRoute, '/');
    }
    $this->httpRoute = $httpRoute;
  }

  /**
  * @param string $method
  * @param string $route
  * @param string $action
  */
  public function addRoute(string $method, string $route, string $action) {
    $method = strtoupper($method);
    return $this->checkRouteBeforeAdd(new Route($method, $route, $action));
  }

  /**
  * @param Route $route
  */
  private function checkRouteBeforeAdd(Route $route) {

    // --- CHECK IF ROUTE METHOD EXIST.
    $result = preg_match('/^(' . self::ALLOWED_METHOD . ')$/', $route->getMethod());
    if(!$result) {
      throw new RouterException(RouterExceptionMessage::get('NOT_ALLOWED_METHOD'));
    }

    // --- CHECK IF ROUTE ALREADY EXIST.
    $result = array_filter($this->routes, function($searchRoute) use ($route) {
      return ($searchRoute->getMethod() === $route->getMethod() 
        && $searchRoute->getRoute() === $route->getRoute());
    });
    if(!empty($result)) {
      throw new RouterException(RouterExceptionMessage::get('NOT_DUPLICATE_ROUTE'));
    }

    // --- CHECK IF ACTION EXIST.
    $checkAction = explode('::', $route->getAction());
    if( empty($checkAction[0]) || empty($checkAction[1]) ) {
      throw new RouterException(RouterExceptionMessage::get('WRONG_ACTION'));
    } elseif( !method_exists($checkAction[0], $checkAction[1]) ) {
      throw new RouterException(RouterExceptionMessage::get('NO_MATCH_ACTION'));
    }

    array_push($this->routes, $route);
    return end($this->routes);
  }

  /**
  *
  */
  private function serveRoute() {
    // -- Init Param.
    $param = new Param;

    foreach ($this->routes as $key => $route) {
      // -- Reset param
      $param->resetParams();

      // -- Check HTTP METHOD.
      if($route->getMethod() !== $this->httpMethod) {
        continue;
      }

      // -- Check Routes val
      $arrRoute    = explode('/', $route->getRoute());
      $httpRoute   = explode('/', $this->httpRoute);
      unset($arrRoute[0]);
      unset($httpRoute[0]);

      // -- Compare route legnth.
      if( count($arrRoute) !== count($httpRoute) ) {
        continue;
      }

      for($i=1 ; $i <= count($arrRoute); $i++) {
        if( $arrRoute[$i] != $httpRoute[$i] ) {
          // Check if he's param.
          $findParam = preg_replace('/^{(.*)}$/', "$1", $arrRoute[$i]);
          if( $findParam == $arrRoute[$i] ) {
            break;
          }
          // -- Else push param.
          $param->setParam($findParam, $httpRoute[$i]);

          // -- If last param show it.
          if( $i === count($arrRoute) ) {
            $result = $route;
            break;
          }
        } elseif( $i === count($arrRoute) ) {
          $result = $route;
          break;
        }
      }

      if( !empty($result) ) {
        break;
      }
    }

    // --- Return route.
    if( empty($result) ) {
      return null;
      throw new RouterException(RouterExceptionMessage::get('NOT_FOUND_ROUTE'));
    }
    // --- Return found route.
    return $result;
  }

  /**
  *
  */
  private function serveRouteAction(Route $route) {
    $args = array();
    $ref = new \ReflectionMethod($route->getAction()); 

    foreach($ref->getParameters() as $key => $param) {
      // -- Add param to the $args output.
      $paramName = $param->getName();
      $args[$paramName] = '';

      // -- Check if class type hiting.
      if($param->hasType()) {
        $callClass = $param->getClass()->name;
        $args[$paramName] = new $callClass();

      // -- Check for default value.
      } elseif($param->isDefaultValueAvailable()) {
        $args[$paramName] = $param->getDefaultValue();
      }
    }

    // --- Call the method with arguments.
    call_user_func_array($route->getAction(), $args);
    return true;
  }

  /**
  *
  */
  public function run() {
    $ee = $this->serveRoute();
    if($ee) {
      $this->serveRouteAction($ee);
    }
  }

}