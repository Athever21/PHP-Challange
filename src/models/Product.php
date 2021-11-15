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

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id) {
    $this->id = $id;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name) {
    $this->name = $name;
  }

  public function getCategory() {
    return $this->category;
  }

  public function setCategory($category) {
    $this->category = $category;
  }

  public function getSku(): string {
    return $this->sku;
  }

  public function setSku(string $sku) {
    $this->sku = $sku;
  }

  public function getPrice(): int {
    return $this->price;
  }

  public function setPrice(int $price) {
    $this->price = $price;
  }

  public function getQuantity(): int {
    return $this->quantity;
  }

  public function setQuantity(int $quantity) {
    $this->quantity = $quantity;
  }

  public function jsonSerialize() {
    $vars = get_object_vars($this);
    return $vars;
  }
}