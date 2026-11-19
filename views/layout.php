<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Учебный магазин</title><link rel="stylesheet" href="/style.css"></head><body>
<header><h1>Учебный магазин</h1><nav><a href="/">Главная</a><a href="/catalog">Каталог</a><?php if ($cartAvailable): ?><a href="/cart">Корзина (<?= $cartCount ?>)</a><?php endif; ?></nav></header>
<main><?php require __DIR__ . '/' . $view . '.php'; ?></main><footer><hr>Учебный проект</footer></body></html>
