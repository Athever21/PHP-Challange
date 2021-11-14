<?php

namespace App\Errors;

class Errors {
  public static function notFound() {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(["error" => "Not found"]);
  }

  public static function methodNotAllowed() {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
  }
}