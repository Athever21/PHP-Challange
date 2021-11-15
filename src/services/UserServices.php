<?php 

namespace App\Services;

use App\Errors\Errors;;
use App\Models\User;
use App\Repositories\UserRepository;

class UserServices {
  public static function getAllUsers($db) {
    echo json_encode(UserRepository::getAllUsers($db));
  }

  public static function getUser($db) {
    echo isset($_REQUEST['userPath']) ? json_encode($_REQUEST['userPath']) : "";
  }

  public static function createUser($db) {
    $data = $_REQUEST['body'];

    if (!isset($data->username) || !isset($data->password) || !isset($data->email)) {
      Errors::badRequest("Missing fields");
      return;
    }

    if (UserRepository::usernameInUse($db, $data->username)) {
      Errors::badRequest("Username already in use");
      return;
    }

    $user = new User(0, $data->username, User::hashPassword($data->password), $data->email);
    UserRepository::saveUser($db, $user);
    echo json_encode($user);
  }

  public static function getUserFromPath($db) {
    $user = UserRepository::findById($db, $_REQUEST['PATH_VARS']['id']);

    if (!$user) {
      Errors::notFound("User not found");
      exit();
    }

    $_REQUEST['userPath'] = $user;
  }

  public static function confirmUser() {
    if (!isset($_REQUEST['userAuth'])) {
      Errors::unauthorized("Invalid token");
      exit();
    }

    if ($_REQUEST['userAuth']->getId() != $_REQUEST['userPath']->getId()) {
      Errors::forbidden("forbidden");
      exit();
    }
  }

  public static function changeUser($db) {
    UserServices::confirmUser();

    $pathU = $_REQUEST['userPath'];
    $data = $_REQUEST['body'];

    if ($data == null) {
      Errors::badRequest("request body empty");
      exit();
    }

    if (isset($data->username)) {
      if (UserRepository::usernameInUse($db, $data->username)) {
        Errors::badRequest("Username already in use");
        exit();
      }
      $pathU->setUsername($data->username);
    }
    if (isset($data->pasword)) {
      $pathU->setPasword($data->pasword);
    }
    if (isset($data->email)) {
      $pathU->setEmail($data->email);
    }

    UserRepository::saveUser($db, $pathU);

    echo json_encode($pathU);
  }

  public static function deleteUser($db) {
    UserServices::confirmUser();
    UserRepository::deleteUser($db, $_REQUEST['userPath']->getId());

    echo json_encode(["status" => "deleted"]);
  }
}