<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = sanitize($conn, $_POST['name']);
    $desc = sanitize($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_url = sanitize($conn, $_POST['image_url']);

    $stmt = $conn->prepare("INSERT INTO product (name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $name, $desc, $price, $stock, $image_url);
    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>Product added successfully.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Failed to add product.</div>";
    }
}

if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $conn->query("DELETE FROM product WHERE product_id = $del_id");
    header("Location: manage_products.php");
    exit();
}

$result = $conn->query("SELECT * FROM product ORDER BY product_id DESC");

include 'includes/header.php';
?>

<h1 class="admin-page-title">Products</h1>
<p class="admin-page-desc">Add inventory and manage what appears in the storefront.</p>

<?php if (isset($msg)) {
    echo $msg;
} ?>

<div class="admin-panel">
    <h3>Add product</h3>
    <form method="POST" action="">
        <input type="hidden" name="action" value="add">
        <div class="admin-form-grid">
            <div class="form-group">
                <label>Product name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="form-group form-group--full">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Image URL (optional)</label>
                <input type="text" name="image_url" class="form-control" placeholder="assets/img/products/photo.jpg or https://...">
            </div>
        </div>
        <button type="submit" class="btn" style="margin-top: 1rem;">Add product</button>
    </form>
</div>

<h2 class="admin-section__title">Catalog</h2>
<div class="admin-table-wrap">
    <table class="cart-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows === 0) {
                echo '<tr><td colspan="5" class="admin-muted" style="text-align:center;padding:2.5rem;">No products yet. Add one above.</td></tr>';
            }
            while ($row = $result->fetch_assoc()) :
                ?>
            <tr>
                <td><?php echo (int) $row['product_id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>$<?php echo number_format((float) $row['price'], 2); ?></td>
                <td><?php echo (int) $row['stock']; ?></td>
                <td>
                    <a href="manage_products.php?delete=<?php echo (int) $row['product_id']; ?>" class="btn btn-secondary admin-btn-danger" style="padding: 0.35rem 0.75rem; font-size: 0.88rem;" onclick="return confirm('Delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
