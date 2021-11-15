<?php

namespace App\Routing\Routes;

use App\Db\DB;
use App\Services\ProductServices;

class ProductRoutes {
  public static function set($router) {
    $db = new DB();

    $router->addRoute("GET", "/products", function () use ($db) {
      ProductServices::getAllProdcuts($db);
    });

    $router->addRoute("GET", "/products/:id", function () use ($db) {
      ProductServices::getProduct($db);
    });
  }
}
