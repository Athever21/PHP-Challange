<?php 

namespace App\Models;

class Product implements \JsonSerializable {
  private int $id;
  private string $name;
  private $category;
  private string $sku;
  private float $price;
  private int $quantity;

  public function __construct($id, $name, $category, $sku, $price, $quantity) {
    $this->id = $id;
    $this->name = $name;
    $this->category = $category;
    $this->sku = $sku;
    $this->price = $price;
    $this->quantity = $quantity;
  }

  public function jsonSerialize() {
    $vars = get_object_vars($this);
    return $vars;
  }
}