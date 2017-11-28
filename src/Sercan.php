<?php

namespace Sercan;

use Sercan\Router;

class Sercan
{

  private $router;

  public function __construct() {
    $this->init();
  }

  private function init() {
    $this->router = new Router();
  }

  public function getRouter() {
    return $this->router;
  }

}