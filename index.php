<?php

include "helpers.php";
define('DEBUG', 0);
spl_autoload_register('autoloadHandler');

new \Core\ExceptionHandler();

$route = new \Core\Router();
$route->run();
