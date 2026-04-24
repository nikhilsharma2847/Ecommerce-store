<?php
/**
 * Product card fragment. Expects $row; optional $badge_label (string) for ribbon.
 */
if (!isset($row)) {
    return;
}
$desc = isset($row['description']) ? $row['description'] : '';
$preview = strlen($desc) > 88 ? substr($desc, 0, 88) . '...' : $desc;
$badge = isset($badge_label) ? $badge_label : '';
?>
<div class="product-card">
    <a href="product.php?id=<?php echo (int) $row['product_id']; ?>" class="product-card__media-link" tabindex="-1" aria-label="<?php echo htmlspecialchars($row['name']); ?> — view product">
        <div class="product-image">
            <?php if (!empty($row['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="" loading="lazy" decoding="async" width="600" height="450" onerror="this.onerror=null;this.src='assets/img/placeholder-product.svg';">
            <?php else: ?>
                <span class="product-image-placeholder">No image</span>
            <?php endif; ?>
            <?php if ($badge !== ''): ?>
                <span class="product-badge"><?php echo htmlspecialchars($badge); ?></span>
            <?php endif; ?>
        </div>
    </a>
    <div class="product-details">
        <h3><a href="product.php?id=<?php echo (int) $row['product_id']; ?>" class="product-title-link"><?php echo htmlspecialchars($row['name']); ?></a></h3>
        <div class="product-meta">
            <span class="product-rating" aria-hidden="true">★★★★★</span>
            <span class="product-rating__text">4.8</span>
        </div>
        <p class="product-desc"><?php echo htmlspecialchars($preview); ?></p>
        <div class="product-row">
            <div class="product-price">$<?php echo number_format((float) $row['price'], 2); ?></div>
            <a href="product.php?id=<?php echo (int) $row['product_id']; ?>" class="btn btn-sm">View</a>
        </div>
    </div>
</div>
