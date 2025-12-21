<?php
require_once __DIR__ . '/../includes/header.php';

$slug = $_GET['slug'] ?? '';

if ($slug) {
    $category = get_category_by_slug($slug);
    if (!$category) {
        echo '<p>Category not found.</p>';
        require_once __DIR__ . '/../includes/footer.php';
        exit;
    }
    $products = get_products(['category_id' => $category['id']]);
    ?>
    <section class="page-header">
        <h1><?php echo htmlspecialchars($category['name']); ?></h1>
        <p><?php echo htmlspecialchars($category['description']); ?></p>
    </section>
    <div class="grid products-grid">
        <?php foreach ($products as $product): ?>
            <div class="card product-card">
                <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($product['slug']); ?>">
                    <img src="<?php echo htmlspecialchars(product_image_url($product)); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                </a>
                <p class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
} else {
    $categories = get_categories();
    ?>
    <section class="page-header">
        <h1>Categories</h1>
    </section>
    <div class="grid categories-grid">
        <?php foreach ($categories as $category): ?>
            <a class="card category-card" href="<?php echo BASE_URL; ?>category.php?slug=<?php echo urlencode($category['slug']); ?>">
                <?php $categoryImage = category_image_url($category['slug']); ?>
                <?php if ($categoryImage): ?>
                    <img class="category-thumb" src="<?php echo htmlspecialchars($categoryImage); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                <p><?php echo htmlspecialchars($category['description']); ?></p>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
