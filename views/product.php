<h2><?= e($product->name) ?></h2><p>Цена: <?= number_format($product->price, 2, ",", " ") ?> ₽</p><p>Остаток: <?= $product->stock ?></p>
