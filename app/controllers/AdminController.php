<?php

declare(strict_types=1);

class AdminController
{
    public function dashboard(PDO $pdo): void
    {
        Auth::requireAdmin();
        $stats = [
            'products' => (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
            'orders' => (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
            'users' => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'revenue' => (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status = 'paid'")->fetchColumn(),
        ];
        $orders = $pdo->query('SELECT o.*, u.name FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC LIMIT 20')->fetchAll();
        $products = $pdo->query('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.id DESC')->fetchAll();
        $categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
        View::render('admin/dashboard', compact('stats', 'orders', 'products', 'categories'));
    }

    public function productSave(PDO $pdo): void
    {
        Auth::requireAdmin();
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = Security::clean($_POST['name'] ?? '');
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));
        $description = Security::clean($_POST['description'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $imageName = $_POST['existing_image'] ?? 'default-shoe.jpg';

        if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $imageName = uniqid('shoe_', true) . '.' . $ext;
                $uploadPath = dirname(__DIR__, 2) . '/public/assets/uploads/' . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
            }
        }

        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE products SET name=:name, slug=:slug, description=:description, category_id=:category_id, price=:price, stock=:stock, is_featured=:is_featured, image=:image WHERE id=:id');
            $stmt->execute([
                ':name' => $name,
                ':slug' => $slug,
                ':description' => $description,
                ':category_id' => $categoryId,
                ':price' => $price,
                ':stock' => $stock,
                ':is_featured' => $isFeatured,
                ':image' => $imageName,
                ':id' => $id,
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (name, slug, description, category_id, price, stock, is_featured, image) VALUES (:name, :slug, :description, :category_id, :price, :stock, :is_featured, :image)');
            $stmt->execute([
                ':name' => $name,
                ':slug' => $slug,
                ':description' => $description,
                ':category_id' => $categoryId,
                ':price' => $price,
                ':stock' => $stock,
                ':is_featured' => $isFeatured,
                ':image' => $imageName,
            ]);
        }

        header('Location: /index.php?r=admin');
        exit;
    }

    public function productDelete(PDO $pdo): void
    {
        Auth::requireAdmin();
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $id]);
        header('Location: /index.php?r=admin');
        exit;
    }

    public function categorySave(PDO $pdo): void
    {
        Auth::requireAdmin();
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }
        $name = Security::clean($_POST['name'] ?? '');
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));
        $stmt = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (:name, :slug)');
        $stmt->execute([':name' => $name, ':slug' => $slug]);
        header('Location: /index.php?r=admin');
        exit;
    }

    public function updateOrderStatus(PDO $pdo): void
    {
        Auth::requireAdmin();
        if (!Security::verifyCsrf($_POST['csrf'] ?? '')) {
            exit('Invalid CSRF token');
        }
        $id = (int)($_POST['id'] ?? 0);
        $status = Security::clean($_POST['status'] ?? 'pending');
        $stmt = $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute([':status' => $status, ':id' => $id]);
        header('Location: /index.php?r=admin');
        exit;
    }
}
