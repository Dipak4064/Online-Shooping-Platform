<?php
require_once __DIR__ . '/../includes/functions.php'; // Ensure functions are loaded
require_once __DIR__ . '/../includes/header.php';

// Fetch products mapped as posts
$posts = get_posts();
$user = current_user();
$flash = '';

// Handle Status Messages
if (isset($_GET['status'])) {
    $map = [
        'created' => 'Post published successfully.',
        'updated' => 'Post updated successfully.',
        'deleted' => 'Post removed.',
    ];
    $status = $_GET['status'];
    if (isset($map[$status])) {
        $flash = $map[$status];
    }
}
?>
<div class="posts-page-wrapper">
    <div class="container">
        <section class="posts-header-modern">
            <div class="header-content">
                <h1>Community Posts</h1>
                <p>Share your hauls, reviews, and shopping tips with the community.</p>
            </div>
            <div class="header-actions">
                <?php if ($user): ?>
                    <a class="btn-primary-modern" href="<?php echo BASE_URL; ?>post_create.php">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create Post
                    </a>
                <?php else: ?>
                    <a class="btn-outline-modern" href="<?php echo BASE_URL; ?>login.php">Sign in to post</a>
                <?php endif; ?>
            </div>
        </section>

        <?php if ($flash): ?>
            <div class="status-alert-modern success">
                <?php echo htmlspecialchars($flash); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h3>No posts yet</h3>
                <p>Be the first to share your thoughts with the community!</p>
            </div>
        <?php else: ?>
            <div class="posts-grid-modern">
                <?php foreach ($posts as $post): ?>
                    <article class="post-card-modern">
                        <div class="post-image-wrapper">
                            <?php if (!empty($post['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars(asset_url($post['image_path'])); ?>"
                                    alt="<?php echo htmlspecialchars($post['title'] ?? 'Post Image'); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="post-card-body">
                            <span class="category-badge">Community</span>
                            <h3><?php echo htmlspecialchars($post['title'] ?? 'Untitled'); ?></h3>
                            <p class="post-excerpt">
                                <?php
                                $excerpt = strip_tags($post['body'] ?? '');
                                echo htmlspecialchars(mb_strlen($excerpt) > 140 ? mb_substr($excerpt, 0, 140) . '…' : $excerpt);
                                ?>
                            </p>

                            <div class="post-footer">
                                <div class="author-info">
                                    <div class="author-avatar">
                                        <?php echo strtoupper(substr($post['author_name'] ?? 'A', 0, 1)); ?>
                                    </div>
                                    <div class="author-meta">
                                        <span
                                            class="name"><?php echo htmlspecialchars($post['author_name'] ?? 'Anonymous'); ?></span>
                                        <span
                                            class="date"><?php echo date('M d, Y', strtotime($post['created_at'] ?? 'now')); ?></span>
                                    </div>
                                </div>

                                <?php if ($user && (int) $user['id'] === (int) ($post['user_id'] ?? 0)): ?>
                                    <div class="post-actions">
                                        <a href="<?php echo BASE_URL; ?>post_edit.php?id=<?php echo $post['id']; ?>"
                                            class="icon-btn edit" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form method="post" action="<?php echo BASE_URL; ?>post_delete.php"
                                            onsubmit="return confirm('Delete this post?');" style="display:inline;">
                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                            <button type="submit" class="icon-btn delete" title="Delete">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Modern Styling from previous context */
    .posts-page-wrapper {
        background-color: #f8fafc;
        min-height: 100vh;
        padding-bottom: 4rem;
    }

    .posts-header-modern {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4rem 0 2.5rem 0;
        gap: 2rem;
    }

    .header-content h1 {
        font-size: 2.75rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        letter-spacing: -0.025em;
    }

    .header-content p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .btn-primary-modern {
        background: #4f46e5;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        transition: 0.2s;
    }

    .btn-primary-modern:hover {
        background: #4338ca;
        transform: translateY(-2px);
    }

    .btn-outline-modern {
        border: 2px solid #e2e8f0;
        color: #475569;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
    }

    .posts-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 2rem;
    }

    .post-card-modern {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
    }

    .post-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .post-image-wrapper {
        height: 200px;
        overflow: hidden;
        background: #f1f5f9;
    }

    .post-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .post-card-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .category-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #4f46e5;
        background: #eef2ff;
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        margin-bottom: 1rem;
        width: fit-content;
    }

    .post-card-body h3 {
        margin: 0 0 1rem 0;
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
    }

    .post-excerpt {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .post-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .author-avatar {
        width: 36px;
        height: 36px;
        background: #4f46e5;
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    .author-meta .name {
        font-weight: 700;
        font-size: 0.9rem;
        color: #334155;
        display: block;
    }

    .author-meta .date {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .icon-btn.edit {
        background: #f1f5f9;
        color: #475569;
    }

    .icon-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-alert-modern {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2.5rem;
        font-weight: 600;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .empty-state {
        text-align: center;
        padding: 5rem 0;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 4rem;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>