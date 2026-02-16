<?php

declare(strict_types=1);

class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /index.php?r=login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::check() || (int)$_SESSION['user']['is_admin'] !== 1) {
            http_response_code(403);
            exit('Forbidden');
        }
    }
}
