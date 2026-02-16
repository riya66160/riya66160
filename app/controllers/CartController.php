<?php

declare(strict_types=1);

class CartController
{
    public function index(PDO $pdo): void
    {
        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $row) {
            $stmt = $pdo->prepare('SELECT id, name, price, image FROM products WHERE id = :id');
            $stmt->execute([':id' => (int)$productId]);
            $product = $stmt->fetch();
            if (!$product) {
                continue;
            }
            $qty = max(1, (int)$row['qty']);
            $line = $product['price'] * $qty;
            $subtotal += $line;
            $items[] = ['product' => $product, 'qty' => $qty, 'size' => $row['size'], 'line' => $line];
        }

        $tax = $subtotal * 0.1;
        $shipping = $subtotal >= 120 ? 0 : 12;
        $total = $subtotal + $tax + $shipping;

        View::render('cart/index', compact('items', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function add(PDO $pdo): void
    {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $size = Security::clean($_POST['size'] ?? '');
        $qty = max(1, (int)($_POST['qty'] ?? 1));

        $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = :id');
        $stmt->execute([':id' => $productId]);
        $stock = (int)$stmt->fetchColumn();

        if ($qty > $stock) {
            $_SESSION['flash'] = 'Requested quantity exceeds stock.';
            header('Location: /index.php?r=product&id=' . $productId);
            exit;
        }

        $_SESSION['cart'][$productId] = ['qty' => $qty, 'size' => $size];
        header('Location: /index.php?r=cart');
        exit;
    }

    public function update(): void
    {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        foreach (($_POST['qty'] ?? []) as $pid => $qty) {
            $q = max(1, (int)$qty);
            if (isset($_SESSION['cart'][$pid])) {
                $_SESSION['cart'][$pid]['qty'] = $q;
            }
        }

        header('Location: /index.php?r=cart');
        exit;
    }

    public function remove(): void
    {
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        $pid = (int)($_POST['product_id'] ?? 0);
        unset($_SESSION['cart'][$pid]);

        header('Location: /index.php?r=cart');
        exit;
    }
}
