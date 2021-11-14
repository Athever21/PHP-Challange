<?php

namespace App\Routing;

use App\Db\DB;
use App\Services\UserServices;

class UserRoutes {
  public static function set($router) {
    $db = new DB();

    $router->addRoute("GET", "/users", function () use ($db) {
      UserServices::getAllUsers($db);
    });

    $router->addRoute("GET", "/users/:id", function () use ($db) {
      UserServices::getUser($db);
    });

    $router->addRoute("POST", "/users", function () use ($db) {
      UserServices::createUser($db);
    });
  }
}
