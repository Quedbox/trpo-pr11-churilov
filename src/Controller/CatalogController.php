<?php
namespace App\Controller;
use App\Repository\ProductRepository;
class CatalogController {
    public function __construct(private ProductRepository $repository) {}
    public function index(): void {
        $category = is_string($_GET['category'] ?? null) ? $_GET['category'] : '';
        $sort = ($_GET['sort'] ?? '') === 'price' ? 'price' : 'name';
        $products = $this->repository->all();
        if ($category !== '') $products = array_values(array_filter($products, fn($p) => (string)$p->categoryId === $category));
        usort($products, fn($a, $b) => $sort === 'price' ? $a->price <=> $b->price : strnatcasecmp($a->name, $b->name));
        \render('catalog', ['products'=>$products, 'categories'=>$this->repository->categories(), 'category'=>$category, 'sort'=>$sort]);
    }
    public function show(): void {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $product = $id ? $this->repository->find($id) : null;
        if (!$product) { http_response_code(404); \render('error', ['message'=>'Товар не найден']); return; }
        \render('product', ['product'=>$product]);
    }
}
