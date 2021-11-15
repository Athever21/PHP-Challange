<?php

namespace App\Routing;

use App\Routing\Routes\CategoryRoutes;
use App\Routing\Routes\ProductRoutes;
use App\Routing\Routes\UserRoutes;

$router = new Router();

ProductRoutes::set($router);
CategoryRoutes::set($router);
UserRoutes::set($router);

$router->dispatch();