<?php
namespace App\Service;
use App\Repository\ProductRepository;
class CartService {
    public function __construct(private ProductRepository $repository) {}
    public function details(array $cart): array {
        $rows = []; $total = 0;
        foreach ($cart as $id => $qty) {
            $p = $this->repository->find((int)$id);
            if (!$p) continue;
            $sum = $p->price * $qty; $total += $sum;
            $rows[] = ['product'=>$p, 'qty'=>$qty, 'sum'=>$sum];
        }
        return ['rows'=>$rows, 'total'=>$total];
    }
}
