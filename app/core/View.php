<?php

declare(strict_types=1);

class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data);
        $viewPath = dirname(__DIR__) . '/views/' . $template . '.php';
        include dirname(__DIR__) . '/views/layouts/header.php';
        include $viewPath;
        include dirname(__DIR__) . '/views/layouts/footer.php';
    }
}
