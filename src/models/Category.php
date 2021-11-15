<?php

namespace App\Models;

class Category implements \JsonSerializable {
  private int $id;
  private string $name;

  public function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
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

  public function jsonSerialize() {
    $vars = get_object_vars($this);
    return $vars;
  }
}