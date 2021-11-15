<?php

namespace App\Repositories;

use App\Models\Product;
use PDO;

class ProductRepository {
  public static function getAllProdcuts($db) {
    $qry = "SELECT products.id, products.product_name,categories.category_name, products.SKU, products.price,products.quantity FROM products INNER JOIN categories ON categories.id = products.category_id";
    $data = $db->query($qry)->fetchAll(PDO::FETCH_ASSOC);
    $arr = [];
    foreach ($data as $row) {
      array_push($arr, $row);
    }
    
    return $arr;
  }

  public static function findById($db, int $id) {
    $stmt = $db->prepare("SELECT products.id, products.product_name,categories.category_name, products.SKU, products.price,products.quantity FROM products INNER JOIN categories ON products.category_id = categories.id WHERE products.id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return false;

    return new Product($row['id'], $row['product_name'], $row['category_name'], $row['SKU'], $row['price'], $row['quantity']);
  }
}