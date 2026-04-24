<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$product = $result->fetch_assoc();
include 'includes/header.php';
?>

<div style="display: flex; gap: 2rem; margin-top: 2rem; background: var(--card-bg); padding: 2rem; border-radius: 16px; box-shadow: var(--glass-shadow); border: 1px solid var(--border-color); flex-wrap: wrap;">
    <div style="flex: 1; background: var(--bg-elevated); min-height: 300px; display:flex; align-items:center; justify-content:center; border-radius: 8px;">
        <?php if(!empty($product['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width:100%; border-radius: 8px;" onerror="this.onerror=null;this.src='assets/img/placeholder-product.svg';">
        <?php else: ?>
            <span style="color:var(--text-muted);">Product Image</span>
        <?php endif; ?>
    </div>
    <div style="flex: 1;">
        <h1 style="font-size: 2.5rem; color: var(--primary-color);"><?php echo htmlspecialchars($product['name']); ?></h1>
        <h2 style="color: var(--text-muted); font-size: 2rem; margin-top: 1rem;">$<?php echo number_format($product['price'], 2); ?></h2>
        
        <p style="margin: 2rem 0; font-size: 1.1rem; line-height: 1.8;">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </p>

        <p style="margin-bottom: 2rem;"><strong>Availability:</strong> 
            <?php echo $product['stock'] > 0 ? "<span style='color:green;'>In Stock ({$product['stock']})</span>" : "<span style='color:red;'>Out of Stock</span>"; ?>
        </p>

        <?php if ($product['stock'] > 0): ?>
            <form action="cart.php" method="POST" style="display: flex; gap: 1rem;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="form-control" style="width: auto;">
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        <?php else: ?>
            <button class="btn btn-secondary" disabled>Out of Stock</button>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
