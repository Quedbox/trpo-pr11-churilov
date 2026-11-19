<?php
$home = new App\Controller\HomeController(__DIR__ . '/../data/pages.json');
$repository = new App\Repository\ProductRepository(__DIR__ . '/../data');
$catalog = new App\Controller\CatalogController($repository);
$router->add('GET', '/', [$home, 'index']);
$router->add('GET', '/about', [$home, 'about']);
$router->add('GET', '/catalog', [$catalog, 'index']);
$router->add('GET', '/product', [$catalog, 'show']);
$router->add('GET', '/missing', function () { http_response_code(404); render('error', ['message' => 'Страница не найдена']); });

$cart = new App\Controller\CartController($repository, new App\Service\CartService($repository));
$router->add('GET', '/cart', [$cart, 'index']);
$router->add('POST', '/cart/add', [$cart, 'update']);

$checkout = new App\Controller\CheckoutController(new App\Service\CartService($repository), new App\Service\OrderValidator(), __DIR__ . '/../data');
$router->add('GET', '/checkout', [$checkout, 'index']);
$router->add('POST', '/checkout', [$checkout, 'submit']);
$router->add('GET', '/checkout/success', [$checkout, 'success']);
