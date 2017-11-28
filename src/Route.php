<?php

namespace Sercan;

class Route
{
  private $method;
  private $route;
  private $action;
  private $name;
  private $params;

  public function __construct(string $method, string $route, string $action, string $name = null) 
  {
    $this->method   = $method;
    $this->route    = $route;
    $this->action   = $action;
    $this->name     = $name;
  }

  public function setParams($params) {
    $this->params = $params;
  }

  public function getParams() {
    return $this->params;
  }

  public function getMethod(): string 
  {
    return $this->method;
  }

  public function getRoute(): string
  {
    return $this->route;
  }

  public function getAction(): string
  {
    return $this->action;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getName(): string
  {
    return $this->name;
  }
}