<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_login();

$order_id = $_GET['id'] ?? null;
$user = current_user();

if (!$order_id) {
    header("Location: orders.php");
    exit;
}

$pdo = get_db_connection();

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1");
$stmt->execute([$order_id, $user['id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// If order doesn't exist, redirect
if (!$order) {
    header("Location: orders.php");
    exit;
}

// Fetch product details based on product_id from the order
$stmtt = $pdo->prepare("SELECT * FROM posts WHERE id = ? LIMIT 1");
$stmtt->execute([$order['product_id'] ?? 0]);
$product = $stmtt->fetch(PDO::FETCH_ASSOC);

// Image Path Logic: Use product image, or a placeholder if empty
$imagePath = (!empty($product['image_path'])) ? $product['image_path'] : 'assets/images/placeholder-luxury.jpg';
?>

<div class="premium-scope">
    <div class="luxury-container">

        <header class="luxury-nav">
            <a href="orders.php" class="btn-glass-back">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                <span>Back to History</span>
            </a>
            <div class="premium-badge <?php echo strtolower($order['status']); ?>">
                <span class="dot"></span>
                <?php echo strtoupper($order['status'] ?? 'PENDING'); ?>
            </div>
        </header>

        <main class="luxury-card">
            <section class="card-hero">
                <div class="hero-content">
                    <h1 class="invoice-title">Invoice Details</h1>
                    <p class="order-id-sub">TransID: <span class="mono">#<?php echo $order['id']; ?></span> •
                        <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                    </p>
                </div>
                <div class="hero-action">
                    <button onclick="window.print()" class="btn-premium-action">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6v-8z" />
                        </svg>
                        <span>Print Invoice</span>
                    </button>
                </div>
            </section>

            <div class="card-layout">
                <div class="view-main">
                    <div class="premium-product-box">
                        <div class="product-visual">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>"
                                alt="<?php echo htmlspecialchars($product['title'] ?? 'Product'); ?>">
                        </div>
                        <div class="product-details">
                            <span class="category-tag">Premium Collection</span>
                            <h2 class="product-name"><?php echo htmlspecialchars($product['title'] ?? 'Luxury Item'); ?>
                            </h2>
                            <p class="product-meta">Ref Code: <?php echo htmlspecialchars($order['product_id']); ?> •
                                Item x 1</p>
                        </div>
                        <div class="product-valuation">
                            <span class="currency">Rs.</span>
                            <span class="amount"><?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                    </div>

                    <div class="shipping-grid">
                        <div class="ship-card">
                            <h4 class="mini-label">Recipient Details</h4>
                            <p class="client-name"><?php echo htmlspecialchars($order['b_name'] ?? 'N/A'); ?></p>
                            <p class="client-address">
                                <?php echo nl2br(htmlspecialchars($order['address'] ?? 'No address provided')); ?>
                            </p>
                        </div>
                        <?php if (!empty($order['message'])): ?>
                            <div class="ship-card message">
                                <h4 class="mini-label">Client Note</h4>
                                <p class="note-text">"<?php echo htmlspecialchars($order['message']); ?>"</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <aside class="view-sidebar">
                    <div class="summary-card">
                        <h4 class="summary-title">Statement</h4>
                        <div class="sum-row">
                            <span>Base Price</span>
                            <span>Rs. <?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                        <div class="sum-row">
                            <span>Logistics</span>
                            <span class="free-text">Complimentary</span>
                        </div>
                        <div class="sum-divider"></div>
                        <div class="sum-row total">
                            <span>Amount Paid</span>
                            <span>Rs. <?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                        <div class="secured-tag">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                            </svg>
                            Secure Payment Verified
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<style>
    /* PREMIUM DESIGN SYSTEM 
Palette: Slate 900, Indigo 600, Rose 500, White 
*/

    .premium-scope {
        background: #fdfdfd;
        background-image: radial-gradient(#e5e7eb 0.5px, transparent 0.5px);
        background-size: 24px 24px;
        min-height: 100vh;
        padding: 60px 20px;
        font-family: 'Inter', -apple-system, sans-serif;
        color: #1e293b;
        -webkit-font-smoothing: antialiased;
    }

    .luxury-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Nav Styles */
    .luxury-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .btn-glass-back {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.3s;
    }

    .btn-glass-back:hover {
        color: #1e293b;
    }

    /* Status Badges */
    .premium-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
        background: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .premium-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #94a3b8;
    }

    .premium-badge.successful {
        border-color: #bbf7d0;
        color: #15803d;
    }

    .premium-badge.successful .dot {
        background: #22c55e;
    }

    .premium-badge.pending {
        border-color: #fef3c7;
        color: #b45309;
    }

    .premium-badge.pending .dot {
        background: #f59e0b;
    }

    /* The Card */
    .luxury-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.03), 0 0 1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-hero {
        padding: 50px 50px 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .invoice-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin: 0;
        color: #0f172a;
    }

    .order-id-sub {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 15px;
    }

    .mono {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #1e293b;
    }

    .btn-premium-action {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #1e293b;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
    }

    .btn-premium-action:hover {
        background: #334155;
        transform: translateY(-2px);
    }

    /* Layout Content */
    .card-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 2px;
        background: #f1f5f9;
        /* Creates subtle border between grid items */
    }

    .view-main {
        background: #fff;
        padding: 20px 50px 50px;
    }

    .view-sidebar {
        background: #fafafa;
        padding: 50px;
    }

    /* Product Box */
    .premium-product-box {
        display: flex;
        align-items: center;
        gap: 25px;
        background: #fff;
        padding: 25px 0;
    }

    .product-visual {
        width: 110px;
        height: 110px;
        border-radius: 16px;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
    }

    .product-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .category-tag {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: #6366f1;
        letter-spacing: 0.1em;
        display: block;
        margin-bottom: 4px;
    }

    .product-name {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
    }

    .product-meta {
        margin: 6px 0 0;
        font-size: 13px;
        color: #94a3b8;
    }

    .product-valuation {
        margin-left: auto;
        text-align: right;
    }

    .product-valuation .currency {
        font-size: 14px;
        font-weight: 500;
        color: #94a3b8;
    }

    .product-valuation .amount {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        display: block;
    }

    /* Details Grid */
    .shipping-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 40px;
    }

    .mini-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.05em;
        margin-bottom: 12px;
    }

    .client-name {
        font-weight: 700;
        margin-bottom: 5px;
        font-size: 16px;
    }

    .client-address {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
    }

    .note-text {
        font-size: 14px;
        font-style: italic;
        color: #64748b;
        background: #f8fafc;
        padding: 15px;
        border-radius: 12px;
        border-left: 3px solid #e2e8f0;
    }

    /* Sidebar Summary */
    .summary-title {
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        color: #1e293b;
        margin-bottom: 25px;
        letter-spacing: 0.05em;
    }

    .sum-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 15px;
        color: #64748b;
    }

    .free-text {
        color: #10b981;
        font-weight: 600;
    }

    .sum-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 20px 0;
    }

    .sum-row.total {
        color: #0f172a;
        font-weight: 800;
        font-size: 20px;
    }

    .secured-tag {
        margin-top: 30px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #94a3b8;
        background: #fff;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #f1f5f9;
    }

    .luxury-footer {
        text-align: center;
        margin-top: 40px;
        color: #94a3b8;
        font-size: 13px;
    }

    /* Print & Responsive */
    @media (max-width: 850px) {
        .card-layout {
            grid-template-columns: 1fr;
        }

        .view-sidebar {
            padding: 40px 50px;
        }

        .card-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }
    }

    @media print {
        .premium-scope {
            padding: 0;
            background: #fff;
        }

        .luxury-nav,
        .hero-action,
        .luxury-footer {
            display: none;
        }

        .luxury-card {
            box-shadow: none;
            border: 1px solid #eee;
        }

        .view-sidebar {
            background: #fff;
            border-top: 1px solid #eee;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>