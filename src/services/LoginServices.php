<?php 

namespace App\Services;

use App\Errors\Errors;
use App\Repositories\UserRepository;

class LoginServices {
  public static function loginUser($db) {
    $data = $_REQUEST['body'];

    if (!isset($data->username) || !isset($data->password)) {
      Errors::badRequest("Missing fields");
      return;
    }

    $user = UserRepository::findByUsername($db, $data->username);

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
    if (!isset($_COOKIE['refresh_token'])) {
      Errors::badRequest("Refersh cookie not present");
      return;
    }

    $token_data = JWTService::getDataFromToken($_COOKIE['refresh_token'], true);
    
    if (!$token_data) {
      Errors::unauthorized("Invalid token");
      return;
    }

    $user = UserRepository::findById($db, $token_data->userId);

    if (!$user) {
      Errors::notFound("User not found");
      return;
    }

    $token = JWTService::genrateToken($user->getId(),false);
    echo json_encode(["token" => $token, "user" => $user]);
  }

  public static function logoutUser() {
    setcookie("refresh_token", "", ["expires" => 0, "httponly" => true, "samesite" => true]);
    echo json_encode(["status" => "logged out"]);
  }

  public static function getAuthUser($db) {
    if (isset(getallheaders()['Authorization'])) {
      $auth = getallheaders()['Authorization'];
      if (str_starts_with($auth, "Bearer ")) {
        $data = JWTService::getDataFromToken(substr($auth, 7), false);
        if ($data) {
          $user = UserRepository::findById($db, $data->userId);
          if ($user) {
            $_REQUEST["userAuth"] = $user;
          }
        } 
      } 
    }
  }
}