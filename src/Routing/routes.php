<?php

namespace App\Routing;

$router = new Router();

ProductRoutes::set($router);

$router->dispatch();