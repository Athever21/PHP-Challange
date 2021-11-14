<?php

namespace App\Routing;

$router = new Router();
$router->addRoute("GET", "/hello", function() {
  echo 'hello_world';
});
$router->addRoute("GET", "/hello/:test", function() {
  echo 'hello_world_test';
  var_dump($_REQUEST['PATH_VARS']);
});
$router->addRoute("GET", "/hello/:test/:b", function() {
  echo 'hello_world_test2';
  var_dump($_REQUEST['PATH_VARS']);
  var_dump($_REQUEST['QUERIES']);
});
$router->dispatch();