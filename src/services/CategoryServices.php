<?php 

namespace App\Services;

use App\Repositories\CategoryRepository;

use App\Errors\Errors;
use App\Models\Category;

class CategoryServices {
  public static function getAllCategories($db) {
    echo json_encode(CategoryRepository::getAllCategories($db));
  }

  public static function getCategory($db) {
    echo json_encode($_REQUEST['category']);
  }

  public static function createCategory($db) {
    $data = $_REQUEST['body'];

    CategoryServices::chceckBody($db, $data);
    
    $category = new Category(0, $data->name);

    CategoryRepository::saveCategory($db, $category);

    echo json_encode($category);
  }

  public static function changeCategory($db) {
    $data = $_REQUEST['body'];
    $category = $_REQUEST['category'];

    CategoryServices::chceckBody($db, $data);
    $category->setName($data->name);

    CategoryRepository::saveCategory($db, $category);

    echo json_encode($category);
  }

  public static function deleteCategory($db) {
    CategoryRepository::deleteCategory($db, $_REQUEST['category']->getId());
    echo json_encode(["status" => "deleted"]);
  }

  public static function chceckBody($db, $data) {
    $data = $_REQUEST['body'];

    if ($data == null) Errors::badRequest("missing fields");
    if (!isset($data->name)) Errors::badRequest("missing name field");

    if (CategoryRepository::categoryExists($db, $data->name)) Errors::badRequest("category already exists");
  }

  public static function getCategoryFromPath($db) {
    $category = CategoryRepository::getById($db, $_REQUEST['PATH_VARS']['id']);

    if (!$category) Errors::notFound("category not found");

    $_REQUEST['category'] = $category;
  }
}