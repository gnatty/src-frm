<?php

namespace Sercan;

class Router
{

  /**
  *
  */
  const ALLOWED_METHOD = 'GET|POST';
  const DIR_NAME_RETURN = ['\\', '/'];

  private $scriptPath;
  private $httpMethod;
  private $httpRoute;
  private $httpQuery;

  public function __construct() {
    $this->init();

  }

  public function getScriptPath() {
    return $this->scriptPath;
  }
  public function getHttpMethod() {
    return $this->httpMethod;
  }
  public function getHttpRoute() {
    return $this->httpRoute;
  }
  public function getHttpQuery() {
    return $this->httpQuery;
  }

  /**
  *
  *
  */
  public function init() {
    // ---
    $this->scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    $this->httpMethod = $_SERVER['REQUEST_METHOD'];
    // ---
    $this->parseHttpRoute();

  }

  public function parseHttpRoute() {
    if( empty($_SERVER['REDIRECT_URL']) ) {
      $httpRoute = '/';
    } else {
      $httpRoute = $_SERVER['REDIRECT_URL'];
      if( !in_array($this->scriptPath, self::DIR_NAME_RETURN) ) {
        $httpRoute = substr($httpRoute, strlen($this->scriptPath));
      }
      $httpRoute = rtrim($httpRoute, '/');
    }
    $this->httpRoute = $httpRoute;
  }

}