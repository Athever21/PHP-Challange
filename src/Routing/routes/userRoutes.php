<?php

namespace App\Routing\Routes;

use App\Db\DB;
use App\Services\LoginServices;
use App\Services\UserServices;

class UserRoutes {
  public static function set($router) {
    $db = new DB();

    $router->addMiddleware("/users/:id", function () use ($db) {
      if ($_SERVER["REQUEST_METHOD"] != "POST") {
        UserServices::getUserFromPath($db);
      }
      LoginServices::getAuthUser($db);
    });

    $router->addRoute("GET", "/users", function () use ($db) {
      UserServices::getAllUsers($db);
    });

    $router->addRoute("GET", "/users/:id", function () use ($db) {
      UserServices::getUser($db);
    });

    $router->addRoute("PUT", "/users/:id", function () use ($db) {
      UserServices::changeUser($db);
    });

    $router->addRoute("DELETE", "/users/:id", function () use ($db) {
      UserServices::deleteUser($db);
    });

    $router->addRoute("POST", "/users", function () use ($db) {
      UserServices::createUser($db);
    });

    $router->addRoute("POST", "/users/login", function () use ($db) {
      LoginServices::loginUser($db);
    });

    $router->addRoute("POST", "/users/refresh", function () use ($db) {
      LoginServices::refreshToken($db);
    });

    $router->addRoute("POST", "/users/logout", function () {
      LoginServices::logoutUser();
    });
  }
}
