<?php
namespace App\Repository;
use App\Model\Product;
use App\Model\Category;
class ProductRepository {
    public function __construct(private string $directory) {}
    private function read(string $name): array { return json_decode(file_get_contents($this->directory . '/' . $name), true, 512, JSON_THROW_ON_ERROR); }
    public function all(): array {
        return array_map(fn($p) => new Product((int)$p['id'], $p['name'], (float)$p['price'], (int)($p['category_id'] ?? 0), (int)($p['stock'] ?? 999)), $this->read('products.json'));
    }
    public function categories(): array { return array_map(fn($c) => new Category((int)$c['id'], $c['name']), $this->read('categories.json')); }
    public function find(int $id): ?Product { foreach ($this->all() as $p) if ($p->id === $id) return $p; return null; }
}
