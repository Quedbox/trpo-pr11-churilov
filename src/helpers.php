<?php
function e($value): string { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function render(string $view, array $data = []): void {
    $data['cartAvailable'] = isset($_SESSION['cart']);
    $data['cartCount'] = array_sum($_SESSION['cart'] ?? []);
    $data['csrf'] = $_SESSION['csrf'] ?? '';
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../views/layout.php';
}

function formatPrice(float $price): string { return number_format($price, 2, ',', ' '); }
