<?php

namespace Router;

use Router\Exception\ParamException;
use Router\Message\ParamExceptionMessage;

class Param
{

  private static $_params = array();

  public function __construct() {
  }

  public function resetParams() {
    Param::$_params = array();
  }

  public function setParam($key, $value)
  {
    Param::$_params[ $key ] = $value;
  }

  public function getParams() 
  {
    return Param::$_params;
  }

  public function getParam($key) 
  {
    if( !empty(Param::$_params[$key]) ) {
      return Param::$_params[$key];
    }
    throw new ParamException(ParamExceptionMessage::get('NO_FOUND_PARAM'));
  }

}