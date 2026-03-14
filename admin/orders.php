<?php
require_once __DIR__ . '/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($orderId && $status) {
        $updated = admin_update_order_status($orderId, $status);
        $message = $updated ? 'Order updated.' : 'Could not update order.';
    }
}

$orders = admin_get_orders();
$statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'failed', 'cancelled', 'successful','delivering'];
?>

<section class="page-header"
    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; margin-top: 2rem;">
    <h1 style="font-size: 2.5rem; margin: 0; font-weight: 800;">Orders</h1>
</section>

<?php if ($message): ?>
    <div id="statusAlert"
        class="alert dismissible <?php echo strpos($message, 'Could not') === false ? 'alert-success' : 'alert-error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="full-width-container">
    <div class="table-card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tracking</th>
                    <th>Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">#<?php echo $order['id']; ?></td>
                        <td>
                            <span style="font-weight: 700; color: #1e293b; font-size: 1.35rem;">
                                <?php echo htmlspecialchars($order['customer_name']); ?>
                            </span>
                        </td>
                        <td style="font-weight: 700;">Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td>
                            <span class="status-pill <?php echo htmlspecialchars($order['status']); ?>">
                                <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <code
                                style="background: #f1f5f9; padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 1.1rem; color: #475569;">
                                    <?php echo htmlspecialchars($order['tracking_code'] ?: 'N/A'); ?>
                                </code>
                        </td>
                        <td style="color: #64748b; font-size: 1.15rem;">
                            <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                        </td>
                        <td style="text-align: right;">
                            <form method="post"
                                style="display:flex; gap:1rem; justify-content: flex-end; align-items:center;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="modern-select">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?php echo $status; ?>" <?php echo $order['status'] === $status ? 'selected' : ''; ?>>
                                            <?php echo ucfirst($status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn-primary-modern" type="submit"
                                    style="padding: 0.6rem 1.25rem; font-size: 1.1rem;">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .full-width-container {
        width: 100%;
        margin-top: 1rem;
    }

    .table-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #edf2f7;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table th {
        background: #f8fafc;
        padding: 1.5rem;
        text-align: left;
        font-size: 1.15rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 2px solid #edf2f7;
    }

    .modern-table td {
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 1.25rem;
    }

    .btn-primary-modern {
        background: #4f46e5;
        color: white;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        background: #4338ca;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
    }

    .modern-select {
        padding: 0.6rem;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 1.1rem;
        background: white;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
    }

    .status-pill {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 800;
        display: inline-block;
        text-transform: capitalize;
    }

    .status-pill.pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-pill.paid {
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.processing {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-pill.shipped {
        background: #f0f9ff;
        color: #075985;
    }

    .status-pill.completed {
        background: #dcfce7;
        color: #15803d;
    }

    .status-pill.failed,
    .status-pill.cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .alert {
        padding: 1.25rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        font-weight: 700;
        font-size: 1.25rem;
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border-left: 5px solid #166534;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-left: 5px solid #991b1b;
    }

    .fade-out {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alert = document.getElementById('statusAlert');
        if (alert) {
            setTimeout(function () {
                alert.classList.add('fade-out');
                setTimeout(function () {
                    alert.remove();
                }, 500);
            }, 3000);
        }
    });
</script>
