<?php
namespace App\Model;
class Order {
    public function __construct(public int $id, public string $name, public string $email, public string $address, public string $delivery, public array $items, public float $total) {}
}
