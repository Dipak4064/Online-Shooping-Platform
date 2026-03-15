<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/header.php';

$pdo = get_db_connection();

try {
    $gross_revenue = (float) $pdo->query("SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE status IN ('paid','shipped','completed','successful')")->fetchColumn();

    $website_revenue = $gross_revenue * 0.10;

    $totals = [
        'products' => (int) $pdo->query('SELECT COUNT(*) FROM posts WHERE deleted_at IS NULL')->fetchColumn(),
        'orders' => (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
        'customers' => (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
        'revenue' => $website_revenue,
    ];
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<section class="page-header">
    <h1>Dashboard Overview</h1>
</section>

<div class="grid categories-grid">
    <a href="products.php" class="card-link">
        <div class="card card-products">
            <h3>Total Products</h3>
            <p><?php echo $totals['products']; ?></p>
        </div>
    </a>

    <a href="orders.php" class="card-link">
        <div class="card card-orders">
            <h3>Total Orders</h3>
            <p><?php echo $totals['orders']; ?></p>
        </div>
    </a>

    <a href="customers.php" class="card-link">
        <div class="card card-customers">
            <h3>Total Customers</h3>
            <p><?php echo $totals['customers']; ?></p>
        </div>
    </a>

    <div class="card card-revenue">
        <h3>Website Revenue (10%)</h3>
        <p>Rs. <?php echo number_format($totals['revenue'], 2); ?></p>
    </div>
</div>

<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #3498db;
        --success-color: #27ae60;
        --bg-light: #f4f7f6;
        --text-dark: #333;
        --white: #ffffff;
        --shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-dark);
        margin: 0;
    }

    .page-header {
        padding: 30px 20px;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 40px;
        margin-top: 30px;
    }

    .page-header h1 {
        margin: 0;
        font-weight: 700;
        color: var(--primary-color);
        max-width: 1200px;
        margin: 0 auto;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 25px;
        padding: 0 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .card-link {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.2s ease;
    }

    .card-link:hover {
        transform: translateY(-8px);
    }

    .card {
        background: var(--white);
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border-left: 6px solid #ccc;
        height: 100%;
        box-sizing: border-box;
    }

    .card-products {
        border-left-color: var(--accent-color);
    }

    .card-orders {
        border-left-color: #9b59b6;
    }

    .card-customers {
        border-left-color: #e67e22;
    }

    .card-revenue {
        border-left-color: var(--success-color);
    }

    .card h3 {
        margin: 0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #7f8c8d;
        font-weight: 700;
    }

    .card p {
        margin: 15px 0 0;
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary-color);
    }

    .card-revenue p {
        color: var(--success-color);
    }

    @media (max-width: 768px) {
        .categories-grid {
            grid-template-columns: 1fr;
        }
    }
</style>