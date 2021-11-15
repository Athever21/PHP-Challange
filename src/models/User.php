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

  public function getId() : int {
    return $this->id;
  }

  public function getUsername() : string {
    return $this->username;
  }

  public function getPassword() : string {
    return $this->pass;
  }

  public function getEmail() : string {
    return $this->email;
  }

  public function setId(int $id) {
    $this->id = $id;
  }

  public function setUsername(string $username) {
    $this->username = $username;
  }

  public function setPassword(string $password) {
    $this->password = User::hashPassword($password);
  }

  public function setEmail(string $email) {
    $this->email = $email;
  }

  public static function hashPassword(string $pass) : string {
    return password_hash($pass, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
  }
}