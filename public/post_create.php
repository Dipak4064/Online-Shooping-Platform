<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
require_once __DIR__ . '/../includes/header.php';

$error = '';
$title = '';
$body = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $imagePath = null;

    if (!$title || !$body) {
        $error = 'Title and content are required.';
    }

    $fileProvided = !empty($_FILES['image']['name']);
    if ($fileProvided && ($_FILES['image']['size'] ?? 0) > 5 * 1024 * 1024) {
        $error = 'Images must be 5MB or smaller.';
    }

    if (!$error && $fileProvided) {
        $uploaded = upload_public_image($_FILES['image'], 'posts');
        if ($uploaded) {
            $imagePath = $uploaded;
        } else {
            $error = 'Image upload failed. Use JPG, PNG, GIF, or WEBP formats.';
        }
    }

    if (!$error) {
        $postId = create_post((int) current_user()['id'], [
            'title' => $title,
            'body' => $body,
            'image_path' => $imagePath,
        ]);

        if ($postId) {
            header('Location: posts.php?status=created');
            exit;
        }

        $error = 'Could not save your post. Please try again.';
    }
}
?>
<section class="post-editor">
    <div class="post-editor-header">
        <h1>Share something new</h1>
        <p>Tell the community about your latest find or shopping experience.</p>
    </div>
    <?php if ($error): ?>
        <div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="post-editor-form">
        <label class="auth-field">
            <span>Title</span>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </label>
        <label class="auth-field">
            <span>Content</span>
            <textarea name="body" rows="8" placeholder="Share your story..." required><?php echo htmlspecialchars($body); ?></textarea>
        </label>
        <label class="auth-field">
            <span>Feature image (optional)</span>
            <input type="file" name="image" accept="image/*">
        </label>
        <button type="submit" class="btn btn-full">Publish post</button>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
