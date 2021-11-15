<?php

namespace App\Routing;

use App\Errors\Errors;

class Router {
  protected array $routes = ["GET" => [], "POST" => [], "PUT" => [], "DELETE" => []];
  protected array $middlewares = [];

  public function addRoute(string $method, string $path, $handler) {
    $this->routes[$method] += [$path => $handler];
  }

  public function addMiddleware(string $path, $handler) {
    $this->middlewares += [$path => $handler];
  }

  public function dispatch() {
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? "GET";
    $requestPath = $_SERVER['REQUEST_URI'] ?? "/";

    $path = $this->getQueries($requestPath);

    if (array_key_exists($requestMethod, $this->routes)) {
      if (array_key_exists($path, $this->routes[$requestMethod])) {
        $this->dispatchMiddleware($path);
        $this->routes[$requestMethod][$path]();
      } else if ($handler = $this->matchPathVar($path, $this->routes[$requestMethod])) {
        $this->dispatchMiddleware($path);
        $handler();
      } else {
        Errors::notFound("Path not found");
      }
    } else {
      Errors::methodNotAllowed();
    }
  }

  private function getQueries(string $requestPath): string {
    $arr = explode("?", $requestPath);
    $queries = [];

    if (count($arr) > 1) {
      $queryArr = explode("&", $arr[1]);

      foreach ($queryArr as $q) {
        $query = explode("=",$q);
        $queries += count($query) > 1 ? [$query[0] => $query[1]] : [$query[0] = ""];
      }
    }
    
    $_REQUEST['QUERIES'] = $queries;
    return $arr[0];
  }

  private function matchPathVar(string $path, array $paths): mixed {
    $pathArr = explode("/", $path);
    $pathVars = [];

    foreach (array_keys($paths) as $p) {
      $arr = explode("/", $p);
      if (count($arr) == count($pathArr)) {
        $flag = true;

        for ($i = 0; $i < count($arr); $i++) {
          $a = $pathArr[$i];
          $b = $arr[$i];

          if ($a != $b) {
            if ($b[0] != ":") {
              $flag = false;
              break;
            } else {
              $pathVars += [substr($b, 1) => $a];
            }
          }
        }
        if ($flag) {
          $_REQUEST['PATH_VARS'] = $pathVars;
          return $paths[$p];
        }
      }
    }

    return null;
  }

  private function dispatchMiddleware(string $path) {
    $pArr = explode("/", $path);
    foreach (array_keys($this->middlewares) as $m) {
      $mArr = explode("/", $m);
      $flag = true;
      for ($i = 0; $i < count($mArr); $i++) {
        if ($mArr[$i] == "*") break;
        if (!isset($pArr[$i])) {
          $flag = false;
          break;
        };
        if ($pArr[$i] != $mArr[$i] && $mArr[$i][0] != ":") {
          $flag = false;
          break;
        };
      }
      if ($flag) $this->middlewares[$m]();
    }
  }
}