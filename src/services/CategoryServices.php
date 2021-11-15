<?php 

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryServices {
  public static function getAllCategories($db) {
    echo json_encode(CategoryRepository::getAllCategories($db));
  }

  public static function getCategory($db) {
    echo json_encode(CategoryRepository::getById($db, $_REQUEST['PATH_VARS']['id']));
  }
}