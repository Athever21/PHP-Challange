<?php

namespace App\Services;

use App\Models\Product;
use PDO;
use App\Db\DB;
use App\Errors\Errors;

class ProductServices {
  public static function getAllProdcuts($db) {
    $qry = "SELECT products.id, products.product_name,categories.category_name, products.SKU, products.price,products.quantity FROM products INNER JOIN categories ON categories.id = products.category_id";
    $data = $db->query($qry)->fetchAll(PDO::FETCH_ASSOC);
    $arr = [];
    foreach ($data as $row) {
      array_push($arr, $row);
    }
      
    header("Content-type: application/json");
    echo json_encode($arr);
  }

  public static function getProduct(DB $db) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_REQUEST['PATH_VARS']['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      Errors::notFound("Product not found");
      return;
    }

    header("Content-type: application/json");
    echo json_encode($row);
  }
}