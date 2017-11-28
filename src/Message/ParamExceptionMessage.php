<?php

namespace Router\Message;

class ParamExceptionMessage
{

  public static function values() {
    return array(
      'NO_FOUND_PARAM'                 => 'The param doesn\'t exist',
    );
  }

  public static function get(string $value) {
    if( empty(self::values()[$value]) ) {
      throw new \Exception('UNDEFINED VALUE "'.$value.'"');
    }
    return self::values()[$value];
  }

}