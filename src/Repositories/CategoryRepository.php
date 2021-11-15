<?php 

namespace App\Repositories;

use PDO;
use App\Models\Category;

class CategoryRepository {
  public static function getAllCategories($db) {
    $data = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
    $arr = [];
    foreach ($data as $row) {
      array_push($arr, $row);
    }
    return $arr;
  }

  public static function getById($db, int $id) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      return false;
    }
    
    return new Category($row['id'], $row['category_name']);
  }

  public static function getOrCreateCategory($db, string $category) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE category_name = ?");
    $stmt->execute([$category]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      $stmt = $db->prepare("INSERT INTO categories(category_name) VALUES (?)");
      $stmt->execute([$category]);
      return $db->lastInsertId();
    }
    
    return $row['id'];
  }
}