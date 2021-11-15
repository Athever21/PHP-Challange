<?php 

namespace App\Services;

use PDO;
use App\Errors\Errors;
use App\Models\Category;

class CategoryServices {
  public static function getAllCategories($db) {
    $data = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
    $arr = [];
    foreach ($data as $row) {
      array_push($arr, $row);
    }
      
    echo json_encode($arr);
  }

  public static function getCategory($db) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_REQUEST['PATH_VARS']['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      Errors::notFound("Category not found");
      return;
    }

    echo json_encode($row);
  }
}