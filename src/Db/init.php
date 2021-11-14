<?php 

namespace App\Db;

use PDO;

if (!file_exists(__DIR__ . '/flag.txt')) {
  $db = new PDO("mysql:host={$_ENV['host']}", $_ENV['user'], $_ENV['password']);
  $sql = file_get_contents(__DIR__ . '/init.sql');
  $qr = $db->exec($sql);
  file_put_contents(__DIR__.'/flag.txt', true);
}

