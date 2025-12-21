<?php
require_once __DIR__ . '/header.php';

$pdo = get_db_connection();
$totals = [
    'products' => (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'orders' => (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
    'customers' => (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
    'revenue' => (float) $pdo->query("SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE status IN ('paid','shipped','completed')")->fetchColumn(),
];
?>
<section class="page-header">
    <h1>Dashboard</h1>
</section>
<div class="grid categories-grid">
    <div class="card">
        <h3>Total products</h3>
        <p><?php echo $totals['products']; ?></p>
    </div>
    <div class="card">
        <h3>Total orders</h3>
        <p><?php echo $totals['orders']; ?></p>
    </div>
    <div class="card">
        <h3>Total customers</h3>
        <p><?php echo $totals['customers']; ?></p>
    </div>
    <div class="card">
        <h3>Revenue</h3>
        <p>Rs. <?php echo number_format($totals['revenue'], 2); ?></p>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php';
