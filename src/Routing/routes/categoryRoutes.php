<?php 

namespace App\Routing\Routes;

use App\Db\DB;
use App\Services\CategoryServices;

class CategoryRoutes {
  public static function set($router) {
    $db = new DB();

    $router->addRoute("GET", "/categories", function () use ($db) {
      CategoryServices::getAllCategories($db);
    });

    $router->addRoute("GET", "/categories/:id", function () use ($db) {
      CategoryServices::getCategory($db);
    });
  }
}
