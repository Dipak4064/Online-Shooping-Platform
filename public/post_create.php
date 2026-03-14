<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$error = '';
$title = '';
$body = '';
$price = '';
$product_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $product_type = $_POST['product_type'] ?? '';
    $fileProvided = !empty($_FILES['image']['name']);

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

    $imagePath = null;
    if (empty($error)) {
        $uploadResult = upload_public_image($_FILES['image'], 'posts');
        if ($uploadResult['success']) {
            $imagePath = $uploadResult['path'];
        } else {
            $error = $uploadResult['error'];
        }
    }

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
$stmt = $pdo->prepare($query);
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
                    <input type="file" name="image" id="imageInput" accept="image/*" required>
                </div>
                <div id="file-name-display"
                    style="font-size: 1.1rem; color: #4f46e5; font-weight: 600; margin-top: 10px; text-align: center;">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    Post Product
                </button>
                <a href="my_store.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .create-product-page {
        min-height: 100vh;
        padding: 60px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8faff;
    }

    .create-container {
        width: 100%;
        max-width: 1000px;
        background: white;
        border-radius: 24px;
        padding: 60px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        border: 1px solid #eee;
    }

    .create-header {
        text-align: center;
        margin-bottom: 40px;
        border: 2px solid #e0e7ff;
        padding: 20px;
        border-radius: 15px;
    }

    .header-icon {
        font-size: 50px;
        margin-bottom: 4px;
    }

    .create-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a1a2e;
    }

    .create-header p {
        color: #64748b;
        font-size: 1.25rem;
    }

    .error-alert {
        background: #fef2f2;
        border: 2px solid #fecaca;
        color: #dc2626;
        padding: 18px 24px;
        border-radius: 14px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .create-form {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .form-group label {
        font-size: 1.1rem;
        font-weight: 700;
        color: #374151;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 18px 22px;
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        font-size: 1.2rem;
        color: #1f2937;
        background: #f9fafb;
        outline: none;
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #000;
        background: white;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
    }

    .image-upload-area {
        position: relative;
        border: 3px dashed #cbd5e0;
        border-radius: 16px;
        padding: 50px 20px;
        text-align: center;
        background: #fafafa;
        cursor: pointer;
        transition: 0.2s;
    }

    .image-upload-area:hover {
        border-color: #000;
        background: #f0f4f8;
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
        gap: 12px;
    }

    .upload-icon {
        font-size: 48px;
    }

    .upload-text {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }

    .upload-hint {
        font-size: 1.1rem;
        color: #64748b;
    }

    .form-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 20px;
        background: #000;
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 1.4rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.1s;
    }

    .btn-submit:hover {
        background: #2563eb;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    .btn-cancel {
        text-align: center;
        padding: 18px;
        color: #4b5563;
        text-decoration: none;
        font-size: 1.2rem;
        font-weight: 700;
        border-radius: 14px;
        background: #f3f4f6;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    @media (max-width: 640px) {
        .create-container {
            padding: 40px 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.getElementById('imageInput').addEventListener('change', function (e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : "";
        if (fileName) {
            document.getElementById('file-name-display').textContent = "Selected: " + fileName;
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>