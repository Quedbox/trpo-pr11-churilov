<?php
function e($value): string { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function render(string $view, array $data = []): void {
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../views/layout.php';
}
