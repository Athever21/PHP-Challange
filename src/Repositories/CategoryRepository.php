<?php 

namespace App\Repositories;

use PDO;
use App\Models\Category;

class CategoryRepository {
  public static function getAllCategories($db) {
    $data = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
    $arr = [];
    foreach ($data as $row) {
      array_push($arr, new Category($row['id'], $row['category_name']));
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

  public static function categoryExists($db, string $name) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE category_name = ?");
    $stmt->execute([$name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      return false;
    }
    
    return true;
  }

  public static function saveCategory($db, Category $category) {
    if ($category->getId() == 0) {
      $stmt = $db->prepare("INSERT INTO categories(category_name) VALUES (?)");
      $stmt->execute([$category->getName()]);
      $category->setId($db->lastInsertId());
    } else {
      $stmt = $db->prepare("UPDATE categories SET category_name = ? WHERE id = ? ");
      $stmt->execute([$category->getName(), $category->getId()]);
    }
  }

  public static function deleteCategory($db, int $id) {
    $stmt = 'DELETE FROM categories WHERE id = ?';
    $result = $db->prepare($stmt);
    $result->execute([$id]);
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