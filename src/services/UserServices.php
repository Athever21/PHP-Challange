<?php 

namespace App\Services;

use App\Errors\Errors;
use PDO;
use App\Models\User;

class UserServices {
  public static function getAllUsers($db) {
    $data = $db->query("SELECT * FROM users")->fetchAll();
    $arr = [];
    foreach ($data as $row) {
      array_push($arr,new User($row[0],$row[1], $row[2], $row[3]));
    }
      
    header("Content-type: application/json");
    echo json_encode($arr);
  }

  public static function getUser($db) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_REQUEST['PATH_VARS']['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      Errors::notFound("User not found");
      return;
    }

    unset($row['pass']);
    header("Content-type: application/json");
    echo json_encode($row);
  }

  public static function createUser($db) {
    header("Content-type: application/json");
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->username) || !isset($data->password) || !isset($data->email)) {
      Errors::badRequest("Missing fields");
      return;
    }

    if (User::usernameInUse($db, $data->username)) {
      Errors::badRequest("Username already in use");
      return;
    }

    $user = new User(0, $data->username, User::hashPassword($data->password), $data->email);
    $user->save($db);
    echo json_encode($user);
  }

}