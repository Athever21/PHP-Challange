<?php 

namespace App\Routing\Routes;

use App\Db\DB;
use App\Services\CategoryServices;
use App\Services\LoginServices;
use App\Errors\Errors;

class CategoryRoutes {
  public static function set($router) {
    $db = new DB();

    $router->addMiddleware("/categories/:id", function () use ($db) {
      CategoryServices::getCategoryFromPath($db);
    });

    $router->addMiddleware("/categories/*", function () use ($db) {
      if ($_SERVER['REQUEST_METHOD'] != "GET") {
        LoginServices::getAuthUser($db);
        if (!isset($_REQUEST['userAuth'])) Errors::forbidden("Invalid token");
      }
    });

    $router->addRoute("GET", "/categories", function () use ($db) {
      CategoryServices::getAllCategories($db);
    });

    $router->addRoute("POST", "/categories", function () use ($db) {
      CategoryServices::createCategory($db);
    });


    $router->addRoute("GET", "/categories/:id", function () use ($db) {
      CategoryServices::getCategory($db);
    });

    $router->addRoute("PUT", "/categories/:id", function () use ($db) {
      CategoryServices::changeCategory($db);
    });

    $router->addRoute("DELETE", "/categories/:id", function () use ($db) {
      CategoryServices::deleteCategory($db);
    });
  }
}
