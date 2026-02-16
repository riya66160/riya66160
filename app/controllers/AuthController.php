<?php

declare(strict_types=1);

class AuthController
{
    public function loginForm(): void
    {
        View::render('auth/login');
    }

    public function registerForm(): void
    {
        View::render('auth/register');
    }

    public function login(PDO $pdo): void
    {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'is_admin' => $user['is_admin'],
            ];
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $_SESSION['flash'] = 'Invalid credentials';
        header('Location: /index.php?r=login');
        exit;
    }

    public function register(PDO $pdo): void
    {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        $name = Security::clean($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || strlen($password) < 8) {
            $_SESSION['flash'] = 'Please use a valid email and 8+ char password';
            header('Location: /index.php?r=register');
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        header('Location: /index.php?r=login');
        exit;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /index.php');
        exit;
    }
}
