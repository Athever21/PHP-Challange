<?php

namespace App\Services;

use App\Models\Product;
use App\Db\DB;
use App\Errors\Errors;
use App\Repositories\ProductRepository;

class ProductServices {
  public static function getAllProdcuts($db) {
    echo json_encode(ProductRepository::getAllProdcuts($db));
  }

  public static function getProduct(DB $db) {
    echo json_encode(ProductRepository::findById($db, $_REQUEST['PATH_VARS']['id']));
  }
}