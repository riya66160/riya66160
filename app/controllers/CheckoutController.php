<?php

declare(strict_types=1);

class CheckoutController
{
    public function index(PDO $pdo): void
    {
        Auth::requireLogin();
        $cart = $_SESSION['cart'] ?? [];
        if (!$cart) {
            header('Location: /index.php?r=products');
            exit;
        }

        View::render('checkout/index', ['stripePublicKey' => env('STRIPE_PUBLIC_KEY', '')]);
    }

    public function placeOrder(PDO $pdo): void
    {
        Auth::requireLogin();

        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        $address = Security::clean($_POST['address'] ?? '');
        $city = Security::clean($_POST['city'] ?? '');
        $zip = Security::clean($_POST['zip'] ?? '');
        $payment = Security::clean($_POST['payment_method'] ?? 'stripe_simulated');

        $pdo->beginTransaction();
        try {
            $subtotal = 0;
            foreach ($_SESSION['cart'] as $pid => $row) {
                $stmt = $pdo->prepare('SELECT price, stock FROM products WHERE id = :id FOR UPDATE');
                $stmt->execute([':id' => (int)$pid]);
                $p = $stmt->fetch();
                if (!$p || $row['qty'] > $p['stock']) {
                    throw new RuntimeException('Stock not available for one or more items.');
                }
                $subtotal += $p['price'] * $row['qty'];
            }

            $tax = $subtotal * 0.1;
            $shipping = $subtotal >= 120 ? 0 : 12;
            $total = $subtotal + $tax + $shipping;

            $order = $pdo->prepare('INSERT INTO orders (user_id, address, city, zip, payment_method, subtotal, tax, shipping, total, status) VALUES (:user_id, :address, :city, :zip, :payment_method, :subtotal, :tax, :shipping, :total, :status)');
            $order->execute([
                ':user_id' => $_SESSION['user']['id'],
                ':address' => $address,
                ':city' => $city,
                ':zip' => $zip,
                ':payment_method' => $payment,
                ':subtotal' => $subtotal,
                ':tax' => $tax,
                ':shipping' => $shipping,
                ':total' => $total,
                ':status' => 'paid',
            ]);
            $orderId = (int)$pdo->lastInsertId();

            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price, size) VALUES (:order_id, :product_id, :quantity, :unit_price, :size)');
            $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - :qty WHERE id = :id');

            foreach ($_SESSION['cart'] as $pid => $row) {
                $productStmt = $pdo->prepare('SELECT price FROM products WHERE id = :id');
                $productStmt->execute([':id' => (int)$pid]);
                $price = (float)$productStmt->fetchColumn();

                $itemStmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => (int)$pid,
                    ':quantity' => (int)$row['qty'],
                    ':unit_price' => $price,
                    ':size' => $row['size'],
                ]);

                $stockStmt->execute([':qty' => (int)$row['qty'], ':id' => (int)$pid]);
            }

            $_SESSION['cart'] = [];
            $pdo->commit();
            $_SESSION['flash'] = 'Order placed successfully!';
            header('Location: /index.php?r=dashboard');
            exit;
        } catch (Throwable $e) {
            $pdo->rollBack();
            Logger::error($e->getMessage());
            $_SESSION['flash'] = 'Order failed. Please try again.';
            header('Location: /index.php?r=checkout');
            exit;
        }
    }
}
