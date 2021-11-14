<?php

namespace App\Db;

use PDO;

class DB extends PDO {
  public function __construct() {    
    parent::__construct("mysql:host={$_ENV['host']};dbname={$_ENV['dbname']}", $_ENV['user'], $_ENV['password']);
    $this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }
}