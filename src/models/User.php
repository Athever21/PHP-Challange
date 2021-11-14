<?php

namespace App\Models;

use App\Db\DB;

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

  public static function hashPassword(string $pass) : string {
    return password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
  }

  public static function usernameInUse(DB $db, string $username): bool {
    $qry = 'SELECT COUNT(*) FROM users WHERE username = ?';
    $result = $db->prepare($qry);
    $result->execute([$username]);
    return $result->fetchColumn();
  }
}