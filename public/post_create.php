<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$error = '';
$title = '';
$body = '';
$price = '';
$product_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Capture and trim all inputs
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $product_type = $_POST['product_type'] ?? '';
    $fileProvided = !empty($_FILES['image']['name']);

    // 2. Strict Empty Check for all fields
    if (empty($title)) {
        $error = 'Product Name is required.';
    } elseif (empty($price) || !is_numeric($price)) {
        $error = 'A valid numeric Price is required.';
    } elseif (empty($product_type)) {
        $error = 'Please select a Category.';
    } elseif (empty($body)) {
        $error = 'Description cannot be empty.';
    } elseif (!$fileProvided) {
        $error = 'Please upload a product image.';
    }

    // 3. Process Upload only if no errors so far
    $imagePath = null;
    if (empty($error)) {
        $uploadResult = upload_public_image($_FILES['image'], 'posts');
        if ($uploadResult['success']) {
            $imagePath = $uploadResult['path'];
        } else {
            $error = $uploadResult['error'];
        }
    }

    // 4. Final Insertion
    if (empty($error) && $imagePath) {
        $postId = create_post((int) current_user()['id'], [
            'title' => $title,
            'body' => $body,
            'price' => $price,
            'product_type' => $product_type,
            'image_path' => $imagePath,
        ]);

        if ($postId) {
            echo "<script>window.location.href='my_store.php?status=success';</script>";
            exit;
        }
        $error = 'Could not save product. Please check your database connection.';
    }
}
$pdo = get_db_connection();
$query = "SELECT name FROM categories ORDER BY name ASC";
$stmt = $pdo->prepare(query: $query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="brand-wrapper">
    <div class="brand-container">
        <header class="form-title">
            <h1>New Listing</h1>
            <p>Define your product details below.</p>
        </header>

        <?php if ($error): ?>
            <div class="brand-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="brand-form">
            <div class="input-section">
                <label>Product Name</label>
                <input type="text" name="title" placeholder="e.g. Minimalist Watch"
                    value="<?= htmlspecialchars($title) ?>" required>
            </div>

            <div class="row">
                <div class="input-section">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" placeholder="0.00"
                        value="<?= htmlspecialchars($price) ?>" required>
                </div>
                <div class="input-section">
                    <label>Category</label>
                    <select name="product_type" required>
                        <option value="" disabled selected>Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['name']) ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="input-section">
                <label>Description</label>
                <textarea name="body" rows="4" placeholder="Describe your item..."
                    required><?= htmlspecialchars($body) ?></textarea>
            </div>

            <div class="input-section">
                <label>Product Image</label>
                <div class="file-upload">
                    <input type="file" name="image" accept="image/*" required>
                </div>
            </div>

            <div class="brand-actions">
                <button type="submit" class="btn-black">Publish to Store</button>
                <a href="profile.php" class="cancel-link">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --brand-black: #111111;
        --brand-gray: #757575;
        --brand-border: #e5e5e5;
        --brand-bg: #ffffff;
    }

    .brand-wrapper {
        background: var(--brand-bg);
        min-height: 80vh;
        padding: 60px 20px;
        color: var(--brand-black);
    }

    .brand-container {
        max-width: 500px;
        margin: 0 auto;
    }

    .form-title {
        text-align: center;
        margin-bottom: 40px;
    }

    .form-title h1 {
        font-size: 28px;
        font-weight: 500;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    .form-title p {
        color: var(--brand-gray);
        font-size: 14px;
    }

    .brand-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .input-section {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .input-section label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    input,
    textarea,
    select {
        border: 1px solid var(--brand-border);
        padding: 12px 16px;
        border-radius: 0px;
        /* Branded sharp corners or subtle 2px */
        font-size: 15px;
        transition: border-color 0.2s;
        outline: none;
        -webkit-appearance: none;
    }

    input:focus,
    textarea:focus {
        border-color: var(--brand-black);
    }

    .brand-error {
        color: #d32f2f;
        font-size: 13px;
        text-align: center;
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #f8d7da;
    }

    .brand-actions {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 20px;
    }

    .btn-black {
        background: var(--brand-black);
        color: white;
        border: none;
        padding: 16px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: opacity 0.2s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-black:hover {
        opacity: 0.8;
    }

    .cancel-link {
        text-align: center;
        font-size: 13px;
        color: var(--brand-gray);
        text-decoration: none;
    }

    .cancel-link:hover {
        color: var(--brand-black);
        text-decoration: underline;
    }

    /* Custom file input look */
    .file-upload input {
        border: none;
        padding-left: 0;
        font-size: 13px;
        color: var(--brand-gray);
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>