<?php
require_once __DIR__ . '/../includes/header.php';
?>

<div class="failure-wrapper">
    <div class="failure-container">

        <div class="failure-mark">
            <div class="cross-icon">
                <span class="icon-line line-left"></span>
                <span class="icon-line line-right"></span>
                <div class="icon-circle"></div>
            </div>
        </div>

        <div class="failure-content">
            <h1 class="failure-title">Payment Failed</h1>
            <p class="failure-subtitle">We couldn't process your payment</p>
        </div>

        <div class="reasons-section">
            <h4>Common Reasons for Payment Failure:</h4>
            <ul>
                <li>Insufficient balance in your account</li>
                <li>Incorrect payment credentials</li>
                <li>Network connectivity issues</li>
                <li>Payment gateway timeout</li>
                <li>Card/Account restrictions</li>
            </ul>
        </div>

        <div class="action-section">
            <?php if ($product_id): ?>
                <a href="payment.php?id=<?= $product_id ?>" class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    </svg>
                    Try Again
                </a>
                <a href="product_view.php?id=<?= $product_id ?>" class="btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to Product
                </a>
            <?php else: ?>
                <a href="/public/products.php" class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    </svg>
                    Back to Home
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .failure-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        font-family: 'Inter', sans-serif;
    }

    .failure-container {
        background: #ffffff;
        max-width: 500px;
        width: 100%;
        border-radius: 24px;
        padding: 35px 30px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    /* Animated X Mark */
    .failure-mark {
        width: 90px;
        height: 90px;
        margin: 0 auto 20px;
    }

    .failure-mark .cross-icon {
        width: 90px;
        height: 90px;
        position: relative;
        border-radius: 50%;
        border: 3px solid #dc2626;
    }

    .failure-mark .icon-line {
        height: 3px;
        background-color: #dc2626;
        display: block;
        position: absolute;
        z-index: 10;
    }

    .failure-mark .icon-line.line-left {
        top: 43px;
        left: 24px;
        width: 40px;
        transform: rotate(45deg);
    }

    .failure-mark .icon-line.line-right {
        top: 43px;
        right: 24px;
        width: 40px;
        transform: rotate(-45deg);
    }

    .failure-title {
        font-size: 26px;
        color: #111827;
        margin-bottom: 8px;
    }

    .failure-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 25px;
    }

    /* Reasons Section */
    .reasons-section {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 25px;
        text-align: left;
    }

    .reasons-section h4 {
        font-size: 13px;
        color: #92400e;
        margin-bottom: 10px;
    }

    .reasons-section ul {
        list-style: none;
    }

    .reasons-section li {
        font-size: 12px;
        color: #78350f;
        padding: 4px 0 4px 15px;
        position: relative;
    }

    .reasons-section li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #d97706;
    }

    /* Buttons */
    .action-section {
        display: flex;
        gap: 12px;
    }

    .btn-primary,
    .btn-secondary {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-primary {
        background: #dc2626;
        color: #fff;
    }

    .btn-secondary {
        background: #fff;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    @media (max-width: 480px) {
        .action-section {
            flex-direction: column;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>