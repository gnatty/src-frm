<?php

namespace Sercan\Utils;

class DebugUtils
{

  public static function pre($arr) {
    echo '<hr><pre>';
    print_r($arr);
    echo '</pre><hr>';
  }

  public static function jum($val) {
    echo $val.'<br />';
  }

  public static function jume($val) {
    echo '[ERREUR] --- ' . $val . '<br />';
  }

}