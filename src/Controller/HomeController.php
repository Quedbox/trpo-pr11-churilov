<?php
namespace App\Controller;
class HomeController {
    private array $pages;
    public function __construct(string $path) { $this->pages = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR); }
    public function index(): void { \render('home', ['pages' => $this->pages]); }
    public function about(): void { \render('about', ['pages' => $this->pages]); }
}
