<?php
require_once __DIR__ . '/db.php';

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function send_welcome_email($user_email, $user_name)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dt1414926@gmail.com';
        $mail->Password = 'ghqh wfbj pvkp bevx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('dt1414926@gmail.com', 'Samplestore');
        $mail->addAddress($user_email, $user_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ' . (defined('APP_NAME') ? APP_NAME : 'Samplestore');

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px;'>
                <h2 style='color: #333;'>Hi $user_name!</h2>
                <p>Welcome to <strong>Samplestore</strong>. We're excited to have you on board!</p>
                <p>Your 30-day free trial has started today. Enjoy exploring our products!</p>
                <hr style='border: 0; border-top: 1px solid #eee;'>
                <p style='font-size: 12px; color: #888;'>Best regards,<br>The Samplestore Team</p>
            </div>";

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
function send_payment_receipt($user_email, $user_name, $amount, $tx_id)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dt1414926@gmail.com';
        $mail->Password = 'ghqh wfbj pvkp bevx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('dt1414926@gmail.com', 'Samplestore');
        $mail->addAddress($user_email, $user_name);

        $mail->isHTML(true);
        $mail->Subject = 'Payment Receipt - ' . $tx_id;

        $mail->Body = "
        <div style='background-color: #f3f4f6; padding: 40px 10px; font-family: sans-serif;'>
            <div style='max-width: 500px; margin: auto; background: white; border-radius: 24px; padding: 30px; border: 1px solid #e5e7eb;'>
                
                <div style='text-align: center; margin-bottom: 20px;'>
                    <div style='background: #ecfdf5; width: 60px; height: 60px; line-height: 60px; border-radius: 50%; display: inline-block; color: #059669; font-size: 30px;'>✔</div>
                </div>

                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #111827; font-size: 24px; margin: 0;'>Payment Successful!</h1>
                    <p style='color: #6b7280; font-size: 14px;'>Your transaction has been completed successfully</p>
                    <div style='display: inline-block; background: #ecfdf5; color: #059669; padding: 5px 15px; border-radius: 50px; font-size: 12px; font-weight: bold; margin-top: 10px; border: 1px solid #059669;'>Verified Payment</div>
                </div>

                <div style='background: #f8fafc; border-radius: 16px; padding: 20px; border: 1px solid #e5e7eb;'>
                    <h3 style='margin: 0 0 15px 0; font-size: 16px; color: #111827;'>Transaction Details</h3>
                    
                    <table style='width: 100%; font-size: 14px; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 8px 0; color: #6b7280;'>Transaction ID</td>
                            <td style='padding: 8px 0; color: #111827; text-align: right; font-weight: bold;'>$tx_id</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; color: #6b7280;'>Amount Paid</td>
                            <td style='padding: 8px 0; color: #059669; text-align: right; font-weight: 800; font-size: 18px;'>Rs. " . number_format($amount, 2) . "</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; color: #6b7280;'>Status</td>
                            <td style='padding: 8px 0; color: white; text-align: right;'>
                                <span style='background: #059669; padding: 2px 10px; border-radius: 10px; font-size: 11px;'>COMPLETED</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style='text-align: center; margin-top: 30px;'>
                    <p style='color: #9ca3af; font-size: 12px;'>Thank you for shopping with Samplestore!<br>If you have any questions, reply to this email.</p>
                </div>

            </div>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (!defined('PLACEHOLDER_PRODUCT_IMAGE')) {
    define('PLACEHOLDER_PRODUCT_IMAGE', 'https://via.placeholder.com/600x600?text=Product');
}

function asset_url(?string $path, ?string $fallback = null): string
{
    if (!$path) {
        if ($fallback && $fallback !== $path) {
            return asset_url($fallback);
        }

        return '';
    }

    if (preg_match('#^(?:https?:)?//#', $path)) {
        return $path;
    }

    $cleanPath = ltrim($path, '/');

    if (str_starts_with($cleanPath, 'public/')) {
        return ROOT_URL . $cleanPath;
    }

    $baseDir = dirname(__DIR__);
    $candidates = [
        '/public/' . $cleanPath,
        '/public/images/' . $cleanPath,
        '/public/image/' . $cleanPath,
    ];

    foreach ($candidates as $candidate) {
        if (file_exists($baseDir . $candidate)) {
            return ROOT_URL . ltrim($candidate, '/');
        }
    }

    if ($fallback && $fallback !== $path) {
        return asset_url($fallback);
    }

    return ROOT_URL . 'public/images/' . $cleanPath;
}

function category_image_url(string $slug): ?string
{
    $extensions = ['png', 'jpg', 'jpeg', 'webp'];
    foreach ($extensions as $ext) {
        $relative = 'public/images/' . $slug . '.' . $ext;
        $fullPath = dirname(__DIR__) . '/' . $relative;
        if (file_exists($fullPath)) {
            return asset_url($relative);
        }
    }

    foreach ($extensions as $ext) {
        $relative = 'public/image/' . $slug . '.' . $ext;
        $fullPath = dirname(__DIR__) . '/' . $relative;
        if (file_exists($fullPath)) {
            return asset_url($relative);
        }
    }

    return null;
}

function product_image_url(array $product): string
{
    $thumbnail = $product['thumbnail'] ?? '';
    $slug = $product['slug'] ?? '';

    $onlineImages = [
        'wireless-earbuds' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=600&q=80',
        'smart-watch' => 'https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?auto=format&fit=crop&w=600&q=80',
        'mens-denim-jacket' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80',
        'ceramic-coffee-mug-set' => 'https://images.unsplash.com/photo-1517686469429-8bdb88b9f907?auto=format&fit=crop&w=600&q=80',
    ];
    $defaultOnline = 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=600&q=80';

    $hasCustomThumbnail = $thumbnail && stripos($thumbnail, 'placeholder') === false;
    if ($hasCustomThumbnail) {
        return asset_url($thumbnail, PLACEHOLDER_PRODUCT_IMAGE);
    }

    if ($slug) {
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $searchRoots = ['public/uploads/products/', 'public/images/', 'public/image/'];
        foreach ($searchRoots as $root) {
            foreach ($extensions as $ext) {
                $relative = $root . $slug . '.' . $ext;
                $fullPath = dirname(__DIR__) . '/' . $relative;
                if (file_exists($fullPath)) {
                    return asset_url($relative, PLACEHOLDER_PRODUCT_IMAGE);
                }
            }
        }

        if (isset($onlineImages[$slug])) {
            return $onlineImages[$slug];
        }
    }

    return $defaultOnline;
}

function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => SESSION_COOKIE_LIFETIME,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function current_user(): ?array
{
    start_session();
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool
{
    $user = current_user();
    return $user && ($user['role'] ?? '') === 'admin';
}

function require_login(): void
{
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin(): void
{
    if (!is_admin()) {
        header('Location: ' . SITE_URL . 'login.php');
        exit;
    }
}

function register_user(array $data): bool
{
    $pdo = get_db_connection();

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        return false;
    }

    $hash = password_hash($data['password'], PASSWORD_ALGO);

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
    return $stmt->execute([
        $data['name'],
        $data['email'],
        $hash,
        $data['role'] ?? 'customer',
    ]);
}

function authenticate_user(string $email, string $password): ?array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT id, name, email, role, password_hash, created_at FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        unset($user['password_hash']);
        return $user;
    }

    return null;
}

function get_categories(): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->query('SELECT id, name, slug, description FROM categories ORDER BY name');
    return $stmt->fetchAll();
}

function get_category_by_slug(string $slug): ?array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT id, name, slug, description FROM categories WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function get_featured_products(int $limit = 8): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT p.id, p.name, p.slug, p.price, p.stock, p.thumbnail, c.name as category_name
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1 AND p.is_featured = 1
        ORDER BY p.updated_at DESC
        LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_products(array $filters = []): array
{
    $pdo = get_db_connection();
    $sql = 'SELECT p.id, p.name, p.slug, p.price, p.stock, p.thumbnail, p.short_description, c.name AS category_name
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1';
    $params = [];

    if (!empty($filters['category_id'])) {
        $sql .= ' AND p.category_id = ?';
        $params[] = $filters['category_id'];
    }

    if (!empty($filters['search'])) {
        $sql .= ' AND (p.name LIKE ? OR p.short_description LIKE ?)';
        $search = '%' . $filters['search'] . '%';
        $params[] = $search;
        $params[] = $search;
    }

    if (!empty($filters['min_price'])) {
        $sql .= ' AND p.price >= ?';
        $params[] = $filters['min_price'];
    }

    if (!empty($filters['max_price'])) {
        $sql .= ' AND p.price <= ?';
        $params[] = $filters['max_price'];
    }

    $sql .= ' ORDER BY p.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_product_by_slug(string $slug): ?array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE p.slug = ? AND p.is_active = 1
        LIMIT 1');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function get_related_products(int $categoryId, int $excludeId, int $limit = 4): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT id, name, slug, price, thumbnail
        FROM products
        WHERE category_id = ? AND id <> ? AND is_active = 1
        ORDER BY RAND()
        LIMIT ?');
    $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(2, $excludeId, PDO::PARAM_INT);
    $stmt->bindValue(3, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function ensure_cart_initialized(): void
{
    start_session();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function add_to_cart(int $productId, int $quantity = 1): void
{
    ensure_cart_initialized();
    if ($quantity < 1) {
        $quantity = 1;
    }

    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    } else {
        $_SESSION['cart'][$productId] += $quantity;
    }
}

function update_cart_quantity(int $productId, int $quantity): void
{
    ensure_cart_initialized();
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function remove_from_cart(int $productId): void
{
    ensure_cart_initialized();
    unset($_SESSION['cart'][$productId]);
}

function clear_cart(): void
{
    ensure_cart_initialized();
    $_SESSION['cart'] = [];
}

function get_cart_items(): array
{
    ensure_cart_initialized();
    if (!$_SESSION['cart']) {
        return [];
    }

    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $pdo = get_db_connection();
    $stmt = $pdo->prepare("SELECT id, name, slug, price, thumbnail FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as &$product) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['price'] * $product['quantity'];
    }

    return $products;
}

function cart_totals(): array
{
    $items = get_cart_items();
    $subtotal = array_sum(array_column($items, 'subtotal'));
    $shipping = $subtotal > 0 ? 100 : 0;
    $tax = round($subtotal * 0.13, 2);
    $total = $subtotal + $shipping + $tax;

    return [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'tax' => $tax,
        'total' => $total,
    ];
}

// Orders
function get_transaction_by_uuid(string $transactionUuid): ?array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT * FROM transaction WHERE uuid_id = ? LIMIT 1');
    $stmt->execute([$transactionUuid]);
    $row = $stmt->fetch();
    return $row ?: null;
}
function create_order_record($u_id, $b_name, $p_id, $address, $message, $amount, $status = 'pending') 
{
    $pdo = get_db_connection();

    try {
        $stmt = $pdo->prepare("INSERT INTO orders 
            (user_id, b_name, product_id, address, message, total_amount, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            (int)$u_id,
            $b_name,
            (int)$p_id,
            $address,
            $message,
            $amount,
            $status
        ]);

        return (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Order Record Creation Error: " . $e->getMessage());
        return false;
    }
}

function get_orders_by_user(int $userId): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function find_product_by_id(int $productId): ?array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
    $stmt->execute([$productId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function get_order_items(int $orderId): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?');
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}

function track_order(string $reference): ?array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE tracking_code = ? LIMIT 1');
    $stmt->execute([$reference]);
    $row = $stmt->fetch();
    return $row ?: null;
}

//wishlist
function save_wishlist_item(int $userId, int $productId): void
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('INSERT IGNORE INTO wishlists (user_id, product_id) VALUES (?, ?)');
    $stmt->execute([$userId, $productId]);
}

function remove_wishlist_item(int $userId, int $productId): void
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('DELETE FROM wishlists WHERE user_id = ? AND product_id = ?');
    $stmt->execute([$userId, $productId]);
}

function get_wishlist(int $userId): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT p.id, p.name, p.slug, p.price, p.thumbnail
        FROM wishlists w
        JOIN products p ON p.id = w.product_id
        WHERE w.user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
function upload_public_image(array $file, string $folder): array
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error code: ' . $file['error']];
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $allowed)) {
        return ['success' => false, 'error' => 'Invalid file type. Use JPG, PNG, or WEBP.'];
    }

    $basePath = dirname(__DIR__) . '/public/uploads/' . $folder;
    if (!is_dir($basePath)) {
        mkdir($basePath, 0755, true);
    }

    $filename = uniqid('img_', true) . '.' . $extension;
    $targetPath = $basePath . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => '/public/uploads/' . $folder . '/' . $filename];
    }

    return ['success' => false, 'error' => 'Failed to move file. Check Linux folder permissions.'];
}

//admin
function admin_get_products(): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->query('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC');
    return $stmt->fetchAll();
}

function admin_create_product(array $data): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('INSERT INTO products (name, slug, short_description, description, price, stock, thumbnail, category_id, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    return $stmt->execute([
        $data['name'],
        $data['slug'],
        $data['short_description'],
        $data['description'],
        $data['price'],
        $data['stock'],
        $data['thumbnail'] ?: PLACEHOLDER_PRODUCT_IMAGE,
        $data['category_id'],
        !empty($data['is_featured']) ? 1 : 0,
        !empty($data['is_active']) ? 1 : 0,
    ]);
}

function admin_update_product(int $id, array $data): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('UPDATE products SET name = ?, slug = ?, short_description = ?, description = ?, price = ?, stock = ?, thumbnail = ?, category_id = ?, is_featured = ?, is_active = ? WHERE id = ?');
    return $stmt->execute([
        $data['name'],
        $data['slug'],
        $data['short_description'],
        $data['description'],
        $data['price'],
        $data['stock'],
        $data['thumbnail'] ?: PLACEHOLDER_PRODUCT_IMAGE,
        $data['category_id'],
        !empty($data['is_featured']) ? 1 : 0,
        !empty($data['is_active']) ? 1 : 0,
        $id,
    ]);
}

function admin_delete_product(int $id): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    return $stmt->execute([$id]);
}

function admin_get_orders(): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->query('SELECT o.*, u.name AS customer_name FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC');
    return $stmt->fetchAll();
}

function admin_update_order_status(int $orderId, string $status): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    return $stmt->execute([$status, $orderId]);
}

function admin_get_customers(): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->query('SELECT id, name, email, created_at FROM users WHERE role = "customer" ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

function admin_get_categories(): array
{
    return get_categories();
}

function admin_create_category(array $data): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)');
    return $stmt->execute([
        $data['name'],
        $data['slug'],
        $data['description'],
    ]);
}

function admin_update_category(int $id, array $data): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?');
    return $stmt->execute([
        $data['name'],
        $data['slug'],
        $data['description'],
        $id,
    ]);
}

function admin_delete_category(int $id): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
    return $stmt->execute([$id]);
}

function generate_tracking_code(int $orderId): string
{
    return 'TRK' . str_pad((string) $orderId, 8, '0', STR_PAD_LEFT);
}

function ensure_tracking_code(int $orderId): string
{
    $pdo = get_db_connection();
    $order = get_order_by_id($orderId);
    if (!$order) {
        throw new InvalidArgumentException('Order not found');
    }

    if (!empty($order['tracking_code'])) {
        return $order['tracking_code'];
    }

    $code = generate_tracking_code($orderId);
    $stmt = $pdo->prepare('UPDATE orders SET tracking_code = ? WHERE id = ?');
    $stmt->execute([$code, $orderId]);
    return $code;
}

function update_user_profile(int $userId, array $data): bool
{
    $pdo = get_db_connection();
    $sql = 'UPDATE users SET name = ?, email = ?';
    $params = [$data['name'], $data['email']];

    if (!empty($data['password'])) {
        $sql .= ', password_hash = ?';
        $params[] = password_hash($data['password'], PASSWORD_ALGO);
    }

    $sql .= ' WHERE id = ?';
    $params[] = $userId;

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);

    if ($result) {
        $stmt = $pdo->prepare('SELECT id, name, email, role FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if ($user) {
            start_session();
            $_SESSION['user'] = $user;
        }
    }

    return $result;
}

//post functions
function get_posts(): array
{
    $pdo = get_db_connection();
    $stmt = $pdo->query('SELECT p.*, u.name AS author_name FROM posts p JOIN users u ON u.id = p.user_id ORDER BY p.created_at DESC');
    return $stmt->fetchAll();
}

function get_post_by_id(int $postId): ?array
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT p.*, u.name AS author_name FROM posts p JOIN users u ON u.id = p.user_id WHERE p.id = ? LIMIT 1');
    $stmt->execute([$postId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function create_post(int $userId, array $data): ?int
{
    $db = get_db_connection();
    if (!$db) {
        echo "Database connection failed.";
        return null;
    }
    $sql = "INSERT INTO posts (user_id, title, price, product_type, body, image_path) 
            VALUES (:user_id, :title, :price, :product_type, :body, :image_path)";

    $stmt = $db->prepare($sql);
    $success = $stmt->execute([
        'user_id' => $userId,
        'title' => $data['title'],
        'price' => $data['price'],
        'product_type' => $data['product_type'],
        'body' => $data['body'],
        'image_path' => $data['image_path']
    ]);

    return $success ? (int) $db->lastInsertId() : null;
}

function delete_post(int $postId, int $userId): bool
{
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
    return $stmt->execute([$postId, $userId]);
}

// Store functions
function get_full_store_profile(int $userId): ?array
{
    $db = get_db_connection();

    $stmtStore = $db->prepare('SELECT * FROM stores WHERE user_id = ? LIMIT 1');
    $stmtStore->execute([$userId]);
    $store = $stmtStore->fetch(PDO::FETCH_ASSOC);

    if (!$store) {
        return null;
    }

    $stmtProducts = $db->prepare('SELECT * FROM posts WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC');
    $stmtProducts->execute([$userId]);
    $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

    return [
        'details' => $store,
        'products' => $products,
        'count' => count($products)
    ];
}

function create_store(int $userId, array $data): int
{
    $pdo = get_db_connection();

    try {
        // Generate slug from store name
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $slug = trim($slug, '-');

        if (empty($slug)) {
            $slug = 'store-' . time();
        }

        // Check if slug already exists
        $stmt = $pdo->prepare('SELECT id FROM stores WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug = $slug . '-' . time();
        }

        // Check if user already has a store
        $stmt = $pdo->prepare('SELECT id FROM stores WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        if ($stmt->fetch()) {
            return 0; // User already has a store
        }

        $stmt = $pdo->prepare('INSERT INTO stores (user_id, name, slug, description, logo, address, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $result = $stmt->execute([
            $userId,
            $data['name'],
            $slug,
            $data['description'] ?? null,
            $data['logo'] ?? null,
            $data['address'] ?? null,
            $data['phone'] ?? null,
            $data['email'] ?? null,
        ]);

        if (!$result) {
            return 0;
        }

        $storeId = (int) $pdo->lastInsertId();

        // Update user role to store_owner
        $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
        $stmt->execute(['store_owner', $userId]);

        return $storeId;
    } catch (Exception $e) {
        error_log('Create store error: ' . $e->getMessage());
        return 0;
    }
}

function get_product_by_id(int $id): ?array
{
    try {
        $db = get_db_connection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    } catch (PDOException $e) {
        // This will log the error so you can see why it failed
        error_log("Database Error: " . $e->getMessage());
        return null;
    }
}

function update_post(int $postId, array $data): bool
{
    $db = get_db_connection();
    if (!$db)
        return false;

    // We build the SQL dynamically to only update the image if a new one is provided
    $sql = "UPDATE posts SET 
                title = :title, 
                price = :price, 
                product_type = :product_type, 
                body = :body";

    // Add image path update only if present in data
    if (!empty($data['image_path'])) {
        $sql .= ", image_path = :image_path";
    }

    $sql .= " WHERE id = :post_id";

    $stmt = $db->prepare($sql);

    $params = [
        'title' => $data['title'],
        'price' => $data['price'],
        'product_type' => $data['product_type'],
        'body' => $data['body'],
        'post_id' => $postId
    ];

    if (!empty($data['image_path'])) {
        $params['image_path'] = $data['image_path'];
    }

    return $stmt->execute($params);
}

function soft_delete_post(int $postId, int $userId): bool
{
    $db = get_db_connection();
    $sql = "UPDATE posts SET deleted_at = NOW() WHERE id = :post_id AND user_id = :user_id";
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        'post_id' => $postId,
        'user_id' => $userId
    ]);
}
function is_store_owner(): bool
{
    $user = current_user();
    return $user && ($user['role'] ?? '') === 'store_owner';
}

function user_has_store(int $userId): bool
{
    return get_full_store_profile($userId) !== null;
}
