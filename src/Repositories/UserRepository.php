<?php

namespace App\Repositories;

use App\Models\User;
use App\Db\DB;
use PDOStatement;

class UserRepository {
  public static function getAllUsers($db) : array {
    $data = $db->query("SELECT * FROM users")->fetchAll();
    $arr = [];
    foreach ($data as $row) {
      array_push($arr,new User($row[0],$row[1], $row[2], $row[3]));
    }
    return $arr;
  }

  public static function usernameInUse(DB $db, string $username): bool {
    $qry = 'SELECT COUNT(*) FROM users WHERE username = ?';
    $result = $db->prepare($qry);
    $result->execute([$username]);
    return $result->fetchColumn();
  }

  public static function findByUsername(DB $db, string $username): User | bool {
    $qry = 'SELECT * FROM users WHERE username = ?';
    $result = $db->prepare($qry);
    $result->execute([$username]);
    return UserRepository::returnUser($result);
  }

  public static function findById(DB $db, string $username): User | bool {
    $qry = 'SELECT * FROM users WHERE id = ?';
    $result = $db->prepare($qry);
    $result->execute([$username]);
    return UserRepository::returnUser($result);
  }

  private static function returnUser(PDOStatement $result): User | bool {
    $row = $result->fetch();
    if (!$row) return false;
    return new User($row[0], $row[1], $row[2], $row[3]);
  }

  public static function deleteUser(DB $db, string $id) {
    $qry = 'DELETE FROM users WHERE id = ?';
    $result = $db->prepare($qry);
    $result->execute([$id]);
  }

  public static function saveUser(DB $db, User $user) {
    if ($user->getId() == 0) {
      $qry = "INSERT INTO users(username, pass, email) VALUES (?,?,?)";
      $stmt = $db->prepare($qry);
      $stmt->execute([$user->getUsername(), $user->getPassword(), $user->getEmail()]);
      $user->setId($db->lastInsertId());
    } else {
      $qry = "UPDATE users SET username = ?, pass = ?, email = ? WHERE id = ?";
      $stmt = $db->prepare($qry);
      $stmt->execute([$user->getUsername(), $user->getPassword(), $user->getEmail(), $user->getId()]);
    }
  }
}