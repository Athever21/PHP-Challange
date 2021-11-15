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
      array_push($arr, ProductRepository::rowIntoProduct($row));
    }
    
    return $arr;
  }

  public static function findById($db, int $id) {
    $stmt = $db->prepare("SELECT products.id, products.product_name,categories.category_name, products.SKU, products.price,products.quantity FROM products INNER JOIN categories ON products.category_id = categories.id WHERE products.id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return false;

    return ProductRepository::rowIntoProduct($row);
  }

  public static function saveProduct($db, Product $product) {
    if ($product->getId() == 0) {
      $id = CategoryRepository::getOrCreateCategory($db, $product->getCategory());
      var_dump($id);
      $stmt = $db->prepare("INSERT INTO products(product_name, category_id, SKU, price, quantity) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$product->getName(), $id, $product->getSku(), $product->getPrice(), $product->getQuantity()]);
      $product->setId($db->lastInsertId());
    } else {
      $id = CategoryRepository::getOrCreateCategory($db, $product->getCategory());
      $stmt = $db->prepare("UPDATE products SET product_name = ?, category_id = ?, SKU = ?, price = ?, quantity = ? WHERE id = ? ");
      $stmt->execute([$product->getName(), $id, $product->getSku(), $product->getPrice(), $product->getQuantity(), $product->getId()]);
    }
  }

  public static function deleteProduct($db, int $id) {
    $stmt = 'DELETE FROM products WHERE id = ?';
    $result = $db->prepare($stmt);
    $result->execute([$id]);
  }

  private static function rowIntoProduct($row) : Product {
    return new Product($row['id'], $row['product_name'], $row['category_name'], $row['SKU'], $row['price'], $row['quantity']);
  }
}