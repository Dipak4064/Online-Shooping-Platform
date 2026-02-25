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

<div class="create-product-page">
    <div class="create-container">
        <div class="create-header">
            <div class="header-icon">📦</div>
            <h1>Create New Product</h1>
            <p>Add your product to the marketplace</p>
        </div>

        <?php if ($error): ?>
            <div class="error-alert">
                <span class="error-icon">⚠️</span>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="create-form">
            <div class="form-group">
                <label for="title">Product Name</label>
                <input type="text" id="title" name="title" placeholder="Enter product name"
                    value="<?= htmlspecialchars($title) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price (Rs.)</label>
                    <input type="number" id="price" name="price" step="0.01" placeholder="0.00"
                        value="<?= htmlspecialchars($price) ?>" required>
                </div>
                <div class="form-group">
                    <label for="product_type">Category</label>
                    <select id="product_type" name="product_type" required>
                        <option value="" disabled selected>Choose category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['name']) ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="body">Description</label>
                <textarea id="body" name="body" rows="5" placeholder="Describe your product in detail..."
                    required><?= htmlspecialchars($body) ?></textarea>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                <div class="image-upload-area">
                    <div class="upload-content">
                        <span class="upload-icon">🖼️</span>
                        <span class="upload-text">Click to upload image</span>
                        <span class="upload-hint">PNG, JPG up to 5MB</span>
                    </div>
                    <input type="file" name="image" accept="image/*" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <span>🚀</span> Publish Product
                </button>
                <a href="my_store.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .create-product-page {
        min-height: 100vh;
        padding: 40px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .create-container {
        width: 100%;
        max-width: 580px;
        background: white;
        border-radius: 20px;
        padding: 50px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .create-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .header-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .create-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 8px 0;
    }

    .create-header p {
        color: #6b7280;
        font-size: 16px;
        margin: 0;
    }

    .error-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
    }

    .error-icon {
        font-size: 18px;
    }

    .create-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        color: #1f2937;
        background: #f9fafb;
        outline: none;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #1f2937;
        background: white;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #9ca3af;
    }

    .image-upload-area {
        position: relative;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        background: #f9fafb;
        cursor: pointer;
    }

    .image-upload-area:hover {
        border-color: #1f2937;
        background: #f9fafb;
    }

    .image-upload-area input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .upload-icon {
        font-size: 36px;
    }

    .upload-text {
        font-size: 15px;
        font-weight: 600;
        color: #374151;
    }

    .upload-hint {
        font-size: 13px;
        color: #9ca3af;
    }

    .form-actions {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-top: 10px;
    }

    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 24px;
        background: #1f2937;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-submit:hover {
        background: #3b82f6;
    }

    .btn-cancel {
        text-align: center;
        padding: 14px;
        color: #6b7280;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        border-radius: 12px;
    }

    .btn-cancel:hover {
        background: #f3f4f6;
        color: #374151;
    }

    @media (max-width: 640px) {
        .create-container {
            padding: 30px 24px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .create-header h1 {
            font-size: 24px;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>