<?php

namespace App\Services;

use App\Models\Product;
use PDO;
use App\Db\DB;

class ProductServices {
  public static function getAllProdcuts($db) {
    $data = $db->query("SELECT * FROM products")->fetchAll();
    $arr = [];
    foreach ($data as $row) {
      array_push($arr,new Product($row[0],$row[1],$row[2],$row[3],$row[4],$row[5]));
    }
      
    header("Content-type: application/json");
    echo json_encode($arr);
  }

  public static function getProduct(DB $db) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_REQUEST['PATH_VARS']['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    header("Content-type: application/json");
    echo json_encode($row);
  }
}