<?php

namespace App\Routing\Routes;

use App\Db\DB;
use App\Services\ProductServices;
use App\Services\LoginServices;
use App\Errors\Errors;

class ProductRoutes {
  public static function set($router) {
    $db = new DB();

    $router->addMiddleware("/products/:id", function () use ($db) {
      ProductServices::getProductFromPath($db);
    });

    $router->addMiddleware("/products/*", function () use ($db) {
      if ($_SERVER['REQUEST_METHOD'] != "GET") {
        LoginServices::getAuthUser($db);
        if (!isset($_REQUEST['userAuth'])) Errors::forbidden("Invalid token");
      }
    });

    $router->addRoute("GET", "/products", function () use ($db) {
      ProductServices::getAllProdcuts($db);
    });

    $router->addRoute("POST", "/products", function () use ($db) {
      ProductServices::createProduct($db);
    });

    $router->addRoute("GET", "/products/:id", function () use ($db) {
      ProductServices::getProduct();
    });

    $router->addRoute("PUT", "/products/:id", function () use ($db) {
      ProductServices::changeProduct($db);
    });

    $router->addRoute("DELETE", "/products/:id", function () use ($db) {
      ProductServices::deleteProduct($db);
    });
  }
}
