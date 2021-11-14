<?php

namespace App\Routing;

$router = new Router();

ProductRoutes::set($router);
CategoryRoutes::set($router);
UserRoutes::set($router);

$router->dispatch();