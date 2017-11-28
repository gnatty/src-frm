<?php

namespace Tests\Controller;

use Router\Param;
use Router\Utils\DebugUtils;

class DefaultController
{

  public static function home($ok, $pasok)
  {
    echo "home";
    return "ok";
  }

  public static function about()
  {
    echo "about";
    return "ok";
  }

  public static function aboutName(Param $param)
  {
    DebugUtils::pre( $param->getParams() );
    echo 'prénom : ' . $param->getParam('name');
    echo "<br/>about";
    return "ok";
  }


  public static function contact()
  {
    echo "contact";
    return "ok";
  }

}