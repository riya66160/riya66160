<?php

declare(strict_types=1);

class DashboardController
{
    public function index(PDO $pdo): void
    {
        Auth::requireLogin();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC');
        $stmt->execute([':uid' => $_SESSION['user']['id']]);
        $orders = $stmt->fetchAll();
        View::render('dashboard/index', compact('orders'));
    }

    public function updateProfile(PDO $pdo): void
    {
        Auth::requireLogin();
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        $name = Security::clean($_POST['name'] ?? '');
        $stmt = $pdo->prepare('UPDATE users SET name = :name WHERE id = :id');
        $stmt->execute([':name' => $name, ':id' => $_SESSION['user']['id']]);
        $_SESSION['user']['name'] = $name;

        header('Location: /index.php?r=dashboard');
        exit;
    }
}
