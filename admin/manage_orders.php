<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $order_id = intval($_POST['order_id']);
    $status = sanitize($conn, $_POST['status']);

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    if ($stmt->execute()) {
        $msg = "<div class='alert alert-success'>Order #$order_id updated to <strong>" . htmlspecialchars($status) . "</strong>.</div>";
    }
}

$orders = $conn->query("SELECT o.order_id, o.total_amount, o.order_date, o.status, c.first_name, c.last_name, c.email FROM orders o JOIN customer c ON o.customer_id = c.customer_id ORDER BY o.order_id DESC");

include 'includes/header.php';
?>

<h1 class="admin-page-title">Orders</h1>
<p class="admin-page-desc">Review purchases and update fulfillment status.</p>

<?php if (isset($msg)) {
    echo $msg;
} ?>

<div class="admin-table-wrap">
    <table class="cart-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($orders->num_rows === 0) {
                echo '<tr><td colspan="6" class="admin-muted" style="text-align:center;padding:2.5rem;">No orders yet.</td></tr>';
            }
            while ($row = $orders->fetch_assoc()):
                $st_slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($row['status']));
                if ($st_slug === '') {
                    $st_slug = 'unknown';
                }
                ?>
            <tr>
                <td><strong>#<?php echo (int) $row['order_id']; ?></strong></td>
                <td>
                    <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?><br>
                    <span class="admin-muted"><?php echo htmlspecialchars($row['email']); ?></span>
                </td>
                <td><?php echo htmlspecialchars(date('M j, Y · g:i A', strtotime($row['order_date']))); ?></td>
                <td>$<?php echo number_format((float) $row['total_amount'], 2); ?></td>
                <td><span class="admin-badge admin-badge--<?php echo htmlspecialchars($st_slug); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                <td>
                    <form action="manage_orders.php" method="POST" class="admin-order-form">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="order_id" value="<?php echo (int) $row['order_id']; ?>">
                        <select name="status" class="form-control">
                            <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Processing" <?php echo $row['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="Shipped" <?php echo $row['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn" style="padding: 0.4rem 0.75rem; font-size: 0.88rem;">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
