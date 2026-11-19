<h2>Заказ оформлен</h2><p>Номер: <?= e($order['id']) ?></p><p>Итого: <?= number_format($order['total'],2,',',' ') ?> ₽</p><p>Доставка: <?= e($order['delivery']) ?></p>
