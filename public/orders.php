<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
require_once __DIR__ . '/../includes/header.php';

$user = current_user();
$orders = get_orders_by_user((int) $user['id']);
?>

<div class="orders-container">
    <div class="orders-header">
        <h1>Purchase History</h1>
        <p>Manage and track your recent orders</p>
    </div>

    <?php if (!$orders): ?>
        <div class="empty-orders">
            <div class="empty-icon">📦</div>
            <h2>No orders yet</h2>
            <p>Looks like you haven't made any purchases yet.</p>
            <a href="products.php" class="shop-now-btn">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="order-id">#<?php echo htmlspecialchars($order['id']); ?></td>
                            <td class="order-date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td class="order-total">Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span class="status-pill status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="view-action-btn">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .orders-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; font-family: 'Inter', sans-serif; }
    .orders-header { margin-bottom: 30px; }
    .orders-header h1 { font-size: 28px; font-weight: 800; color: #111827; margin: 0; }
    .orders-header p { color: #6b7280; margin-top: 5px; }

    /* Table Styles */
    .table-responsive { background: white; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .modern-table { width: 100%; border-collapse: collapse; text-align: left; }
    .modern-table th { background: #f9fafb; padding: 16px; font-size: 13px; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
    .modern-table td { padding: 16px; border-top: 1px solid #e5e7eb; vertical-align: middle; }

    .order-id { font-weight: 700; color: #111827; }
    .order-total { font-weight: 600; color: #059669; }

    /* Status Pills */
    .status-pill { padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 700; }
    .status-successful { background: #ecfdf5; color: #059669; }
    .status-pending { background: #fffbeb; color: #d97706; }
    .status-failed { background: #fef2f2; color: #ef4444; }

    /* Button */
    .view-action-btn { text-decoration: none; color: #374151; font-size: 14px; font-weight: 600; padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 8px; transition: all 0.2s; }
    .view-action-btn:hover { background: #f9fafb; border-color: #111827; color: #111827; }

    /* Empty State */
    .empty-orders { text-align: center; padding: 60px 20px; background: white; border-radius: 16px; border: 2px dashed #e5e7eb; }
    .empty-icon { font-size: 50px; margin-bottom: 20px; }
    .shop-now-btn { display: inline-block; margin-top: 20px; background: #111827; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>