<?php
session_start();
$_SESSION['cart'] ??= [];
$_SESSION['csrf'] ??= bin2hex(random_bytes(16));
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (str_starts_with($class, $prefix)) {
        $file = __DIR__ . '/../src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (is_file($file)) require $file;
    }
});
require __DIR__ . '/../src/helpers.php';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (PHP_SAPI === 'cli-server' && $path !== '/' && is_file(__DIR__ . $path)) return false;
try {
    $router = new App\Core\Router();
    require __DIR__ . '/../src/routes.php';
    $router->dispatch($_SERVER['REQUEST_METHOD'], $path);
} catch (Throwable $error) {
    error_log($error->getMessage());
    http_response_code(500);
    render('error', ['message' => 'Ошибка приложения. Проверьте исходные данные.']);
}
