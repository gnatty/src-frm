<?php

namespace Router\Message;

class RouterExceptionMessage
{

  public static function values() {
    return array(
      'NOT_ALLOWED_METHOD'              => 'Not allowed method',
      'NOT_DUPLICATE_ROUTE'             => 'Cannot duplicate route',
      'WRONG_ACTION'                    => 'Wrong route action',
      'NO_MATCH_ACTION'                 => 'Route action doesn\'t exist',
      'NOT_FOUND_ROUTE'                 => 'No route found'
    );
  }

  public static function get(string $value) {
    if( empty(self::values()[$value]) ) {
      throw new \Exception('UNDEFINED VALUE "'.$value.'"');
    }
    return self::values()[$value];
  }

}