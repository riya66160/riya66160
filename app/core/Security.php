<?php

declare(strict_types=1);

class Security
{
    public static function startSession(string $name): void
    {
        session_name($name);
        session_set_cookie_params([
            'httponly' => true,
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'samesite' => 'Lax',
        ]);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
    }

    public static function csrfToken(): string
    {
        return $_SESSION['csrf'] ?? '';
    }

    public static function verifyCsrf(?string $token): bool
    {
        return hash_equals($_SESSION['csrf'] ?? '', (string) $token);
    }

    public static function clean(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
}
