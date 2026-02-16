<?php

declare(strict_types=1);

class ProductController
{
    public function listing(PDO $pdo): void
    {
        $category = $_GET['category'] ?? '';
        $min = max(0, (float)($_GET['min'] ?? 0));
        $max = max(0, (float)($_GET['max'] ?? 99999));
        $sort = $_GET['sort'] ?? 'asc';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 8;
        $offset = ($page - 1) * $perPage;

        $order = $sort === 'desc' ? 'DESC' : 'ASC';
        $sql = 'FROM products p JOIN categories c ON c.id = p.category_id WHERE p.price BETWEEN :min AND :max';
        $params = [':min' => $min, ':max' => $max];

        if ($category !== '') {
            $sql .= ' AND c.slug = :category';
            $params[':category'] = $category;
        }

        $countStmt = $pdo->prepare('SELECT COUNT(*) ' . $sql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $pages = (int)ceil($total / $perPage);

        $stmt = $pdo->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug ' . $sql . " ORDER BY p.price $order LIMIT :limit OFFSET :offset");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll();

        $categories = $pdo->query('SELECT * FROM categories')->fetchAll();
        View::render('products/list', compact('products', 'categories', 'page', 'pages'));
    }

    public function detail(PDO $pdo): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = :id');
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();

        if (!$product) {
            http_response_code(404);
            exit('Product not found');
        }

        $reviewsStmt = $pdo->prepare('SELECT r.*, u.name FROM reviews r JOIN users u ON u.id = r.user_id WHERE r.product_id = :id ORDER BY r.created_at DESC');
        $reviewsStmt->execute([':id' => $id]);
        $reviews = $reviewsStmt->fetchAll();

        View::render('products/detail', compact('product', 'reviews'));
    }
}
