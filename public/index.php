<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/config/config.php';
$config = require dirname(__DIR__) . '/app/config/config.php';

require_once dirname(__DIR__) . '/app/core/Database.php';
require_once dirname(__DIR__) . '/app/core/Security.php';
require_once dirname(__DIR__) . '/app/core/View.php';
require_once dirname(__DIR__) . '/app/core/Auth.php';
require_once dirname(__DIR__) . '/app/core/Logger.php';

foreach (glob(dirname(__DIR__) . '/app/controllers/*.php') as $controller) {
    require_once $controller;
}

Security::startSession($config['session']['name']);
$pdo = Database::connection($config['db']);

set_exception_handler(static function (Throwable $e) use ($config): void {
    Logger::error($e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    echo $config['app']['debug'] ? $e->getMessage() : 'Something went wrong.';
});

$route = $_GET['r'] ?? 'home';

switch ($route) {
    case 'home': (new HomeController())->index($pdo); break;
    case 'products': (new ProductController())->listing($pdo); break;
    case 'product': (new ProductController())->detail($pdo); break;
    case 'cart': (new CartController())->index($pdo); break;
    case 'cart-add': (new CartController())->add($pdo); break;
    case 'cart-update': (new CartController())->update(); break;
    case 'cart-remove': (new CartController())->remove(); break;
    case 'login': (new AuthController())->loginForm(); break;
    case 'login-post': (new AuthController())->login($pdo); break;
    case 'register': (new AuthController())->registerForm(); break;
    case 'register-post': (new AuthController())->register($pdo); break;
    case 'logout': (new AuthController())->logout(); break;
    case 'checkout': (new CheckoutController())->index($pdo); break;
    case 'checkout-post': (new CheckoutController())->placeOrder($pdo); break;
    case 'dashboard': (new DashboardController())->index($pdo); break;
    case 'profile-update': (new DashboardController())->updateProfile($pdo); break;
    case 'admin': (new AdminController())->dashboard($pdo); break;
    case 'admin-product-save': (new AdminController())->productSave($pdo); break;
    case 'admin-product-delete': (new AdminController())->productDelete($pdo); break;
    case 'admin-category-save': (new AdminController())->categorySave($pdo); break;
    case 'admin-order-status': (new AdminController())->updateOrderStatus($pdo); break;
    default: http_response_code(404); echo 'Page not found';
}
