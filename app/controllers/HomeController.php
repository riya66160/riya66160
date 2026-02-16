<?php

declare(strict_types=1);

class HomeController
{
    public function index(PDO $pdo): void
    {
        $featured = $pdo->query('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.is_featured = 1 LIMIT 8')->fetchAll();
        $categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
        View::render('home/index', compact('featured', 'categories'));
    }
}
