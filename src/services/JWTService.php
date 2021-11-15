<?php

namespace App\Services;

use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;

class JWTService {
  // private string $jwtSecretRefresh = $_ENV['jwt_secret'];

  public static function genrateToken(int $id, bool $refresh) : string {
    $issiuedAt = new DateTimeImmutable();
    $data = [
      "iat" => $issiuedAt->getTimestamp(),
      "nbf" => $issiuedAt->getTimestamp(),
      "exp" => $issiuedAt->modify($refresh ? "+7 days" : "+5 minutes")->getTimestamp(),
      "userId" => $id
    ];

    return JWT::encode($data, $refresh ? $_ENV['jwt_secret_refersh'] : $_ENV['jwt_secret'], 'HS512');
  }

  public static function getDataFromToken(string $token, bool $refresh) : mixed {
    try {
      $arr = JWT::decode($token, $refresh ? $_ENV['jwt_secret_refersh'] : $_ENV['jwt_secret'], ['HS512']);
      if (time() > $arr->exp) {
        throw new Exception();
      }
      return $arr;
    } catch(Exception $e) {
      return false;
    }
  } 
}