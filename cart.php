<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $product_id = intval($_POST['product_id']);
    
    if ($action == 'add') {
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        header("Location: cart.php");
        exit();
    } elseif ($action == 'remove') {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
        header("Location: cart.php");
        exit();
    } elseif ($action == 'update') {
        $quantity = intval($_POST['quantity']);
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
        header("Location: cart.php");
        exit();
    }
}

include 'includes/header.php';
?>

<h2>Your Shopping Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-success">Your cart is currently empty. <a href="index.php">Continue shopping</a>.</div>
<?php else: ?>
    <table class="cart-table" style="margin-top: 2rem;">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $quantity): 
                $stmt = $conn->prepare("SELECT name, price, stock FROM product WHERE product_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $subtotal = $row['price'] * $quantity;
                    $total += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
                <td>
                    <form action="cart.php" method="POST" style="display:flex; gap: 0.5rem; align-items:center;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" max="<?php echo $row['stock']; ?>" class="form-control" style="width: 80px; padding: 0.3rem;">
                        <button type="submit" class="btn" style="padding: 0.3rem 0.6rem; font-size:0.9rem;">Update</button>
                    </form>
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <button type="submit" class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size:0.9rem;">Remove</button>
                    </form>
                </td>
            </tr>
            <?php 
                }
            endforeach; 
            ?>
        </tbody>
    </table>
    
    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; background: var(--card-bg); padding: 1.5rem; border-radius: 16px; box-shadow: var(--glass-shadow);">
        <h3 style="margin: 0;">Grand Total: $<?php echo number_format($total, 2); ?></h3>
        <div>
            <a href="index.php" class="btn btn-secondary" style="margin-right: 1rem;">Continue Shopping</a>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
