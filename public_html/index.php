<?php

// --------------------------------------------------------------------------------------------

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Sercan\Sercan;
use Sercan\Utils\DebugUtils;
try {

$ss = new Sercan();
DebugUtils::pre($ss->getRouter());

} catch(\Exception $e) {
  echo '<pre>' . $e . '</pre>';
}

exit();

// --------------------------------------------------------------------------------------------

?>