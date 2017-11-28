<?php

namespace Router\Message;

class Message
{

  public static function values() {
    return array();
  }

  public static function get(string $value) {
    var_dump((new self));
    if( empty(self::values()[$value]) ) {
      throw new \Exception('UNDEFINED VALUE "'.$value.'"');
    }
    return self::values()[$value];
  }

}