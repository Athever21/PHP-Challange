<?php

namespace App\Errors;

class Errors {
  public static function notFound(string $message) {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(["error" => $message]);
  }

  public static function methodNotAllowed() {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
  }

  public static function badRequest(string $message) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(["error" => $message]);
  }

  public static function unauthorized(string $message) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(["error" => $message]);
  }
}