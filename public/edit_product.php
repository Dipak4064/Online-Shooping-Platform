<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$error = '';
$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$postId) {
    header("Location: my_store.php");
    exit;
}

$post = get_post_by_id($postId);

if (!$post || (int) $post['user_id'] !== (int) current_user()['id']) {
    header("Location: my_store.php?error=unauthorized");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $product_type = $_POST['product_type'] ?? '';

    if (empty($title) || empty($price) || empty($product_type) || empty($body)) {
        $error = 'All fields are required.';
    }

    $imagePath = $post['image_path']; // Default to old image
    if (empty($error) && !empty($_FILES['image']['name'])) {
        $uploadResult = upload_public_image($_FILES['image'], 'posts');
        if ($uploadResult['success']) {
            $imagePath = $uploadResult['path'];
        } else {
            $error = $uploadResult['error'];
        }
    }

    if (empty($error)) {
        $updateData = [
            'title' => $title,
            'body' => $body,
            'price' => $price,
            'product_type' => $product_type,
            'image_path' => $imagePath
        ];

        if (update_post($postId, $updateData)) {
            header("Location: product_view.php?id=$postId&status=updated");
            exit;
        }
        $error = 'Could not update product.';
    }
}

require_once __DIR__ . '/../includes/header.php';
?>



<div class="edit-page-container">
    <div class="edit-card shadow-sm">
        <a href="product_view.php?id=<?= $postId ?>" class="close-icon">&times;</a>

        <div class="edit-header">
            <h1>Edit Listing</h1>
            <p>Editing Item #<?= $postId ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger mx-5"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="edit-form">

            <div class="input-group-section">
                <label class="section-label">Product Image</label>
                <div class="image-upload-flex">
                    <div class="image-preview-box" id="imagePreview">
                        <img src="<?= asset_url($post['image_path']) ?>" alt="Preview">
                    </div>
                    <div class="upload-controls">
                        <label for="imageInput" class="btn-outline">Choose New Photo</label>
                        <input type="file" name="image" id="imageInput" accept="image/*" hidden>
                        <span class="file-info" id="fileName">Current: <?= basename($post['image_path']) ?></span>
                    </div>
                </div>
            </div>

            <div class="input-group-section">
                <label class="section-label">Product Title</label>
                <input type="text" name="title" class="full-input" value="<?= htmlspecialchars($post['title']) ?>"
                    required>
            </div>

            <div class="input-group-section">
                <label class="section-label">Category</label>
                <select name="product_type" class="full-input" required>
                    <option value="electronics" <?= $post['product_type'] == 'electronics' ? 'selected' : '' ?>>Electronics
                    </option>
                    <option value="apparel" <?= $post['product_type'] == 'apparel' ? 'selected' : '' ?>>Apparel</option>
                    <option value="home" <?= $post['product_type'] == 'home' ? 'selected' : '' ?>>Home</option>
                </select>
            </div>

            <div class="input-group-section">
                <label class="section-label">Price (Rs.)</label>
                <input type="number" name="price" step="0.01" class="full-input"
                    value="<?= htmlspecialchars($post['price']) ?>" required>
            </div>

            <div class="input-group-section">
                <label class="section-label">Full Description</label>
                <textarea name="body" rows="6" class="full-input"
                    required><?= htmlspecialchars($post['body']) ?></textarea>
            </div>

            <div class="form-footer">
                <div class="main-actions">
                    <button type="submit" class="btn-save">Save Changes</button>
                    <a href="my_store.php" class="btn-cancel">Cancel</a>
                </div>

                <a href="delete_product.php?id=<?= $postId ?>" class="btn-delete"
                    onclick="return confirm('Are you sure you want to delete this listing?')">
                    Delete Listing
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .edit-page-container {
        background-color: #f8faff;
        min-height: 100vh;
        padding: 60px 20px;
        display: flex;
        justify-content: center;
    }

    .edit-card {
        background: #fff;
        width: 100%;
        max-width: 850px;
        border-radius: 12px;
        position: relative;
        padding-bottom: 30px;
        border: 1px solid #eee;
    }

    .close-icon {
        position: absolute;
        top: 25px;
        left: 30px;
        font-size: 28px;
        color: #b0c4de;
        text-decoration: none;
    }

    .edit-header {
        padding: 40px 50px 20px 50px;
    }

    .edit-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .edit-header p {
        color: #888;
        font-size: 14px;
    }

    .edit-form {
        padding: 0 50px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .section-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
    }

    .full-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e1e8f0;
        border-radius: 6px;
        font-size: 15px;
    }

    .image-upload-flex {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .image-preview-box {
        width: 100px;
        height: 100px;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .btn-outline {
        border: 1px solid #cbd5e0;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .file-info {
        font-size: 12px;
        color: #777;
        margin-left: 10px;
    }

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .main-actions {
        display: flex;
        gap: 10px;
    }

    .btn-save {
        background: #000;
        color: #fff;
        border: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-cancel {
        background: #f0f4f8;
        color: #555;
        text-decoration: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-weight: 600;
    }

    .btn-delete {
        color: white;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        background-color: red;
        padding: 12px 16px;
        border-radius: 6px;
    }

    .btn-delete:hover {
        text-decoration: none;
        background-color: darkred;
        color: white;
    }
</style>

<script>
    document.getElementById('imageInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('imagePreview').innerHTML = `<img src="${e.target.result}">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>