<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

$data = $_GET['data'] ?? null;

if (!$data) {
    header("Location: index.php?error=no_data");
    exit;
}

$decoded_json = base64_decode($data);
$result = json_decode($decoded_json, true);

$status = $result['status'] ?? '';
$amount = $result['total_amount'] ?? 0;
$transaction_id = $result['transaction_code'] ?? '';
$transaction_uuid = $result['transaction_uuid'] ?? '';
$payment_method = $result['payment_method'] ?? 'eSewa';
$product_code = $result['product_code'] ?? '';

$txn = get_transaction_by_uuid($transaction_uuid);
$p_id = $txn['product_id'] ?? 0;

if ($status === 'COMPLETE') {
    $baseUrl = "https://rc.esewa.com.np/api/epay/transaction/status/";
    $fullUrl = $baseUrl . "?product_code=" . urlencode($product_code) . "&transaction_uuid=" . urlencode($transaction_uuid) . "&total_amount=" . urlencode($amount);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $fullUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $verification = json_decode($response, true);

    if (isset($verification['status']) && $verification['status'] === 'COMPLETE') {
        $pdo = get_db_connection();

        $update = $pdo->prepare("UPDATE transaction SET 
            transaction_code = ?, 
            status = 'successful' 
            WHERE uuid_id = ? AND status = 'pending'");

        $update->execute([
            $verification['ref_id'] ?? $transaction_id,
            $transaction_uuid
        ]);
        if (isset($_SESSION['user'])) {
            $u_id = $_SESSION['user']['id'];
            $u_email = $_SESSION['user']['email'];
            $u_name = $_SESSION['user']['name'];

            send_payment_receipt($u_email, $u_name, $amount, $transaction_id);
            create_order_record($u_id, $u_name, $p_id, "ktm", "Your Product is On The Way.", $amount, 'successful');
        }

    } else {
        header("Location: failure.php?msg=verification_failed");
        exit;
    }
} else {
    header("Location: failure.php?msg=payment_not_complete");
    exit;
}

$current_date = date('F d, Y');
$current_time = date('h:i A');
?>

<div class="success-wrapper">
    <div class="success-container">

        <div class="checkmark-wrapper">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
        </div>

        <div class="success-content">
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-subtitle">Your transaction has been completed successfully</p>

            <!-- Payment Badge -->
            <div class="payment-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>Verified Payment</span>
            </div>
        </div>

        <div class="details-card">
            <div class="card-header">
                <h3>Transaction Details</h3>
                <span class="status-badge">Completed</span>
            </div>

            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value"><?= htmlspecialchars($transaction_id) ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Amount Paid</span>
                    <span class="detail-value amount">Rs. <?= number_format($amount, 2) ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value"><?= htmlspecialchars($payment_method) ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Date</span>
                    <span class="detail-value"><?= $current_date ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Time</span>
                    <span class="detail-value"><?= $current_time ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Transaction UUID</span>
                    <span class="detail-value uuid"><?= htmlspecialchars($transaction_uuid) ?></span>
                </div>
            </div>
        </div>

        <div class="action-section">
            <a href="/public/index.php" class="btn-primary">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Back to Home
            </a>

            <button onclick="window.print()" class="btn-secondary">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print Receipt
            </button>
        </div>

        <div class="info-footer">
            <p>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                A confirmation email has been sent to your registered email address
            </p>
        </div>

    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .success-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .success-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: drift 20s linear infinite;
    }

    @keyframes drift {
        from {
            transform: translate(0, 0);
        }

        to {
            transform: translate(50px, 50px);
        }
    }

    .success-container {
        background: #ffffff;
        max-width: 500px;
        width: 100%;
        border-radius: 24px;
        padding: 35px 30px;
        position: relative;
        z-index: 1;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animated Checkmark */
    /* Container to center the icon */
    .checkmark-wrapper {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
    }

    .checkmark {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: block;
        stroke-width: 3;
        stroke: #059669;
        /* Success Green */
        stroke-miterlimit: 10;
        box-shadow: inset 0 0 0 #059669;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 3;
        stroke-miterlimit: 10;
        stroke: #059669;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scale {

        0%,
        100% {
            transform: none;
        }

        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }

    @keyframes fill {
        100% {
            box-shadow: inset 0 0 0 50px #059669;
            /* Fills the background green */
        }
    }

    /* Change checkmark color to white once background is filled */
    .checkmark__check {
        stroke: #059669;
        /* Initial color */
        transition: stroke 0.3s;
    }

    .checkmark {
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    /* Make the check white after the green fill */
    @keyframes fill {
        100% {
            box-shadow: inset 0 0 0 50px #059669;
        }
    }

    /* Ensure the check becomes white so it's visible against green */
    .checkmark__check {
        stroke: #059669;
    }

    /* This specific rule ensures the check turns white at the end of the animation */
    .checkmark.scale .checkmark__check,
    .checkmark {
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards,
            colorWhite 0s forwards 0.8s;
    }

    @keyframes colorWhite {
        100% {
            stroke: #ffffff;
        }
    }

    .success-checkmark {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        border-radius: 50%;
        display: block;
        stroke-width: 3;
        stroke: #059669;
        stroke-miterlimit: 10;
        box-shadow: inset 0 0 0 #059669;
        animation: fill 0.4s ease-in-out 0.4s forwards, scale 0.3s ease-in-out 0.9s both;
        position: relative;
    }

    .success-checkmark .check-icon {
        width: 120px;
        height: 120px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #059669;
    }

    .success-checkmark .icon-line {
        height: 4px;
        background-color: #059669;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }

    .success-checkmark .icon-line.line-tip {
        top: 56px;
        left: 25px;
        width: 25px;
        transform: rotate(45deg);
        animation: icon-line-tip 0.75s;
    }

    .success-checkmark .icon-line.line-long {
        top: 50px;
        right: 15px;
        width: 50px;
        transform: rotate(-45deg);
        animation: icon-line-long 0.75s;
    }

    .success-checkmark .icon-circle {
        top: -4px;
        left: -4px;
        z-index: 10;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        position: absolute;
        box-sizing: content-box;
        border: 4px solid rgba(5, 150, 105, 0.2);
    }

    .success-checkmark .icon-fix {
        top: 12px;
        width: 10px;
        left: 32px;
        z-index: 1;
        height: 100px;
        position: absolute;
        transform: rotate(-45deg);
        background-color: #ffffff;
    }

    @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 26px;
        }

        54% {
            width: 0;
            left: 1px;
            top: 26px;
        }

        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }

        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }

        100% {
            width: 25px;
            left: 25px;
            top: 56px;
        }
    }

    @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        84% {
            width: 55px;
            right: 0;
            top: 35px;
        }

        100% {
            width: 50px;
            right: 15px;
            top: 50px;
        }
    }

    /* Success Content */
    .success-content {
        text-align: center;
        margin-bottom: 35px;
    }

    .success-title {
        font-size: 32px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 10px;
    }

    .success-subtitle {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 20px;
    }

    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ecfdf5;
        color: #059669;
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 700;
        border: 2px solid #059669;
    }

    /* Details Card */
    .details-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        border: 2px solid #e5e7eb;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .status-badge {
        background: #059669;
        color: #ffffff;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .detail-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 15px;
        color: #111827;
        font-weight: 600;
    }

    .detail-value.amount {
        color: #059669;
        font-size: 18px;
        font-weight: 800;
    }

    .detail-value.uuid {
        font-size: 12px;
        word-break: break-all;
        font-family: 'Courier New', monospace;
    }

    /* Action Buttons */
    .action-section {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .btn-primary,
    .btn-secondary {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 24px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #111827 0%, #374151 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(17, 24, 39, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 24, 39, 0.4);
    }

    .btn-secondary {
        background: #ffffff;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    /* Info Footer */
    .info-footer {
        text-align: center;
    }

    .info-footer p {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 12px 20px;
        border-radius: 10px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .success-wrapper {
            padding: 20px 15px;
        }

        .success-container {
            padding: 40px 25px;
        }

        .success-title {
            font-size: 26px;
        }

        .success-checkmark,
        .success-checkmark .check-icon,
        .success-checkmark .icon-circle {
            width: 100px;
            height: 100px;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .action-section {
            flex-direction: column;
        }

        .info-footer p {
            font-size: 12px;
            padding: 10px 15px;
        }
    }

    /* Print Styles */
    /* Updated Print Styles */
    @media print {
        body {
            background: white !important;
            padding: 0;
            margin: 0;
        }

        .success-wrapper {
            background: white !important;
            min-height: auto;
            padding: 0;
        }

        .success-container {
            box-shadow: none !important;
            border: 1px solid #eee;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Force the grid to stay in 2 columns for the receipt */
        .details-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            /* Forces two columns */
            gap: 20px !important;
        }

        /* Hide buttons and unnecessary UI elements */
        .action-section,
        .info-footer,
        .success-wrapper::before,
        header,
        footer,
        nav {
            display: none !important;
        }

        /* Ensure the amount color and bold text show up */
        .detail-value.amount {
            color: #059669 !important;
            -webkit-print-color-adjust: exact;
        }

        .status-badge {
            border: 1px solid #059669 !important;
            color: #059669 !important;
            background: transparent !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>