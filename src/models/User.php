<?php

namespace App\Models;

use App\Db\DB;
use PDOStatement;

class User implements \JsonSerializable {
  private int $id;
  private string $username;
  private string $pass;
  private string $email;

  public function __construct($id, $username, $pass, $email) {
    $this->id = $id;
    $this->username = $username;
    $this->pass = $pass;
    $this->email = $email;
  }

  public function jsonSerialize() {
    $vars = get_object_vars($this);
    unset($vars["pass"]);
    return $vars;
  }

  public function save(DB $db) {
    if ($this->id == 0) {
      $qry = "INSERT INTO users(username, pass, email) VALUES (?,?,?)";
      $stmt = $db->prepare($qry);
      $stmt->execute([$this->username, $this->pass, $this->email]);
      $this->id = $db->lastInsertId();
    }
  }

  public function getId() : int {
    return $this->id;
  }

  public function getPassword() : string {
    return $this->pass;
  }

  public static function hashPassword(string $pass) : string {
    return password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
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
    return User::returnUser($result);
  }

  public static function findById(DB $db, string $username): User | bool {
    $qry = 'SELECT * FROM users WHERE id = ?';
    $result = $db->prepare($qry);
    $result->execute([$username]);
    return User::returnUser($result);
  }

  private static function returnUser(PDOStatement $result): User | bool {
    $row = $result->fetch();
    if (!$row) return false;
    return new User($row[0], $row[1], $row[2], $row[3]);
  }
}