<?php

namespace App\Services;

use App\Models\Product;
use App\Errors\Errors;
use App\Repositories\ProductRepository;

class ProductServices {
  public static function getAllProdcuts($db) {
    echo json_encode(ProductRepository::getAllProdcuts($db));
  }

  public static function getProduct() {
    echo json_encode($_REQUEST['product']);
  }

  public static function createProduct($db) {
    $data = $_REQUEST['body'];

    if ($data == null) Errors::badRequest("missing fields");

    foreach (['name','category','sku','price','quantity'] as $key) {
      if (!isset($data->$key)) Errors::badRequest("missing $key field");
    }

    $product = new Product(0, $data->name, $data->category, $data->sku, $data->price, $data->quantity);

    ProductRepository::saveProduct($db, $product);

    echo json_encode($product);
  }

  public static function changeProduct($db) {
    $product = $_REQUEST['product'];
    $data = $_REQUEST['body'];

    foreach (['name','category','sku','price','quantity'] as $key) {
      $name = "set". ucfirst($key);
      if (isset($data->$key)) $product->$name($data->$key);
    }

    ProductRepository::saveProduct($db, $product);

    echo json_encode($product);
  }

  public static function deleteProduct($db) {
    ProductRepository::deleteProduct($db, $_REQUEST['product']->getId());
    echo json_encode(["status" => "deleted"]);
  }

  public static function getProductFromPath($db) {
    $product = ProductRepository::findById($db, $_REQUEST['PATH_VARS']['id']);

    if (!$product) Errors::notFound("Product not found");

    $_REQUEST['product'] = $product;
  }

}