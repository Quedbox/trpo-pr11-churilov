<?php
namespace App\Service;
class OrderValidator {
    public function validate(array $data, array $methods): array {
        $errors = [];
        if ($data['name'] === '') $errors['name'] = 'Введите имя';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Введите правильный email';
        if (!isset($methods[$data['delivery']])) $errors['delivery'] = 'Выберите способ доставки';
        if ($data['delivery'] === 'courier' && $data['address'] === '') $errors['address'] = 'Введите адрес';
        return $errors;
    }
}
