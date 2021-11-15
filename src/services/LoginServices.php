<?php 

namespace App\Services;

use App\Errors\Errors;
use App\Models\User;

class LoginServices {
  public static function loginUser($db) {
    header("Content-type: application/json");
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->username) || !isset($data->password)) {
      Errors::badRequest("Missing fields");
      return;
    }

    $user = User::findByUsername($db, $data->username);

    if (!$user || !password_verify($data->password, $user->getPassword())) {
      Errors::badRequest("Invalid username or password");
      return;
    }
    
    $token = JWTService::genrateToken($user->getId(),false);
    $tokenRefresh = JWTService::genrateToken($user->getId(),true);
    
    setcookie("refresh_token", $tokenRefresh, ["expires" => strtotime("+7 days"), "httponly" => true, "samesite" => true]);
    echo json_encode(["token" => $token, "user" => $user]);
  }

  public static function refreshToken($db) {
    header("Content-type: application/json");
    if (!isset($_COOKIE['refresh_token'])) {
      Errors::badRequest("Refersh cookie not present");
      return;
    }

    $token_data = JWTService::getDataFromToken($_COOKIE['refresh_token'], true);
    
    if (!$token_data) {
      Errors::unauthorized("Invalid token");
      return;
    }

    $user = User::findById($db, $token_data->userId);

    if (!$user) {
      Errors::notFound("User not found");
      return;
    }

    $token = JWTService::genrateToken($user->getId(),false);
    echo json_encode(["token" => $token, "user" => $user]);
  }

  public static function logoutUser() {
    header("Content-type: application/json");

    setcookie("refresh_token", "", ["expires" => 0, "httponly" => true, "samesite" => true]);
    echo json_encode(["status" => "logged out"]);
  }
}