<?php

namespace App\Routing;

use App\Routing\Routes\CategoryRoutes;
use App\Routing\Routes\ProductRoutes;
use App\Routing\Routes\UserRoutes;

$router = new Router();

$router->addMiddleware("/*", function() {
  header("Content-type: application/json");
  $_REQUEST['body'] = json_decode(file_get_contents("php://input"));
});

ProductRoutes::set($router);
CategoryRoutes::set($router);
UserRoutes::set($router);

$router->dispatch();