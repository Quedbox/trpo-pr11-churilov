<?php
namespace App\Controller;
use App\Service\CartService;
use App\Service\OrderValidator;
use App\Model\Order;
class CheckoutController {
    public function __construct(private CartService $cart, private OrderValidator $validator, private string $directory) {}
    private function methods(): array {
        $methods = json_decode(file_get_contents($this->directory . '/delivery_methods.json'), true, 512, JSON_THROW_ON_ERROR);
        return array_column($methods, null, 'id');
    }
    public function index(): void { $this->show([], []); }
    private function show(array $values, array $errors): void {
        \render('checkout', ['values'=>$values, 'errors'=>$errors, 'methods'=>$this->methods(), 'cart'=>$this->cart->details($_SESSION['cart'])]);
    }
    public function submit(): void {
        $values=[];
        foreach (['name','email','delivery','address'] as $key) $values[$key] = is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
        $methods = $this->methods(); $errors = $this->validator->validate($values, $methods);
        $details = $this->cart->details($_SESSION['cart']);
        if (!$details['rows']) $errors['cart'] = 'Корзина пуста';
        if (!is_string($_POST['csrf'] ?? null) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) $errors['csrf'] = 'Обновите форму и повторите отправку';
        if ($errors) { http_response_code(422); $this->show($values, $errors); return; }
        $path = $this->directory . '/orders.json';
        if (!is_file($path)) {
            if (!copy($this->directory . '/orders_seed.json', $path)) throw new \RuntimeException('Не удалось создать хранилище заказов');
        }
        $file = fopen($path, 'c+');
        if (!$file || !flock($file, LOCK_EX)) throw new \RuntimeException('Не удалось открыть заказы');
        try {
            $contents = stream_get_contents($file);
            $orders = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
            $id = 1; foreach ($orders as $old) $id = max($id, $old['id'] + 1);
            $items=[]; foreach ($details['rows'] as $row) $items[] = ['id'=>$row['product']->id,'name'=>$row['product']->name,'price'=>$row['product']->price,'qty'=>$row['qty']];
            $order = new Order($id, $values['name'], $values['email'], $values['address'], $values['delivery'], $items, $details['total'] + $methods[$values['delivery']]['price']);
            $orders[] = (array)$order;
            $json = json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            rewind($file);
            if (fwrite($file, $json) !== strlen($json) || !ftruncate($file, strlen($json))) throw new \RuntimeException('Не удалось сохранить заказ');
            fflush($file);
        } finally { flock($file, LOCK_UN); fclose($file); }
        $_SESSION['last_order'] = ['id'=>$order->id,'total'=>$order->total,'delivery'=>$methods[$order->delivery]['title']];
        $_SESSION['cart'] = []; header('Location: /checkout/success'); exit;
    }
    public function success(): void {
        if (!isset($_SESSION['last_order'])) { http_response_code(404); \render('error',['message'=>'Заказ не найден']); return; }
        \render('success', ['order'=>$_SESSION['last_order']]);
    }
}
