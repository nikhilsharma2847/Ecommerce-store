<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?msg=login_required");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$total = 0;

// Calculate total
foreach ($_SESSION['cart'] as $id => $quantity) {
    $stmt = $conn->prepare("SELECT price FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $total += $row['price'] * $quantity;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_checkout'])) {
    $conn->begin_transaction();
    try {
        // Create order
        $stmt = $conn->prepare("INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, 'Processing')");
        $stmt->bind_param("id", $customer_id, $total);
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // Create order items & reduce stock
        foreach ($_SESSION['cart'] as $id => $quantity) {
            // Get current price
            $p_stmt = $conn->prepare("SELECT price FROM product WHERE product_id = ?");
            $p_stmt->bind_param("i", $id);
            $p_stmt->execute();
            $p_res = $p_stmt->get_result()->fetch_assoc();
            $price = $p_res['price'];

            // Insert item
            $i_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $i_stmt->bind_param("iiid", $order_id, $id, $quantity, $price);
            $i_stmt->execute();

            // Update product stock
            $u_stmt = $conn->prepare("UPDATE product SET stock = stock - ? WHERE product_id = ?");
            $u_stmt->bind_param("ii", $quantity, $id);
            $u_stmt->execute();
        }

        // Mock Payment Insert
        $pay_method = 'PayPal Checkout';
        $pay_stmt = $conn->prepare("INSERT INTO payment (order_id, payment_method, amount, status) VALUES (?, ?, ?, 'Completed')");
        $pay_stmt->bind_param("isd", $order_id, $pay_method, $total);
        $pay_stmt->execute();

        $conn->commit();
        
        // Clear cart
        unset($_SESSION['cart']);
        
        $success_msg = "Order placed successfully! Your order ID is #$order_id.";
    } catch (Exception $e) {
        $conn->rollback();
        $error_msg = "Failed to process order. Please try again.";
    }
}

include 'includes/header.php';
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h2>Checkout Process</h2>
    
    <?php if (isset($success_msg)): ?>
        <div class="alert alert-success">
            <?php echo $success_msg; ?>
            <br><br>
            <a href="account.php" class="btn" style="margin-right: 0.5rem;">View my orders</a>
            <a href="index.php" class="btn btn-secondary">Return to Home</a>
        </div>
    <?php else: ?>
        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <div style="background: var(--card-bg); padding: 2rem; border-radius: 16px; box-shadow: var(--glass-shadow);">
            <h3>Order Summary</h3>
            <p><strong>Total Items:</strong> <?php echo count($_SESSION['cart']); ?></p>
            <p><strong>Total Amount to Pay:</strong> <span style="font-size: 1.5rem; color: var(--primary-color); font-weight:700;">$<?php echo number_format($total, 2); ?></span></p>
            
            <hr style="margin: 2rem 0; border: 0; border-top: 1px solid var(--border-color);">
            
            <h4>PayPal Integration Demo</h4>
            <p style="color:#666; font-size:0.9rem; margin-bottom: 1rem;">Clicking process will simulate a PayPal payment authorization and place your order.</p>

            <!-- Real-world would include PayPal Smart Buttons / Form here -->
            <form method="POST" action="">
                <input type="hidden" name="process_checkout" value="1">
                <button type="submit" class="btn" style="width: 100%; background: #003087; font-size:1.1rem;">
                    Pay with PayPal & Place Order
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
