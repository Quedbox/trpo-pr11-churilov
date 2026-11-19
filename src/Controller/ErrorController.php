<?php
namespace App\Controller;
class ErrorController {
    public function notFound(): void { http_response_code(404); \render('error', ['message' => 'Страница не найдена']); }
}
