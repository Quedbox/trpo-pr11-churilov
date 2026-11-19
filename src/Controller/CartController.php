<?php
namespace App\Controller;
use App\Repository\ProductRepository;
use App\Service\CartService;
class CartController {
    public function __construct(private ProductRepository $repository, private CartService $cart) {}
    public function index(): void {
        $data = $this->cart->details($_SESSION['cart'] ?? []);
        $data['flash'] = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);
        \render('cart', $data);
    }
    public function update(): void {
        $id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
        $action = is_string($_POST['action'] ?? null) ? $_POST['action'] : 'add';
        if (!$id || !$this->repository->find($id)) { http_response_code(404); \render('error', ['message'=>'Товар не найден']); return; }
        if ($action !== 'remove' && ($qty === false || $qty === null || $qty < 1)) { http_response_code(422); \render('error', ['message'=>'Количество должно быть положительным целым числом']); return; }
        if ($action === 'remove') unset($_SESSION['cart'][$id]);
        elseif ($action === 'set') $_SESSION['cart'][$id] = $qty;
        elseif ($action === 'add') $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
        else { http_response_code(400); \render('error', ['message'=>'Неизвестное действие']); return; }
        $_SESSION['flash'] = 'Корзина обновлена'; header('Location: /cart'); exit;
    }
}
