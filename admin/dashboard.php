<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$products_count = (int) $conn->query("SELECT COUNT(*) as cx FROM product")->fetch_assoc()['cx'];
$orders_count = (int) $conn->query("SELECT COUNT(*) as cx FROM orders")->fetch_assoc()['cx'];
$customers_count = (int) $conn->query("SELECT COUNT(*) as cx FROM customer")->fetch_assoc()['cx'];
$rev_row = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'Pending'")->fetch_assoc();
$total_revenue = $rev_row['total'] !== null ? (float) $rev_row['total'] : 0.0;

include 'includes/header.php';
?>

<h1 class="admin-page-title">Overview</h1>
<p class="admin-page-desc">Sales, catalog, and recent activity at a glance.</p>

<div class="admin-stat-grid">
    <div class="admin-stat-card admin-stat-card--revenue">
        <div class="admin-stat-card__label">Total revenue</div>
        <div class="admin-stat-card__value">$<?php echo number_format($total_revenue, 2); ?></div>
    </div>
    <div class="admin-stat-card admin-stat-card--orders">
        <div class="admin-stat-card__label">Total orders</div>
        <div class="admin-stat-card__value"><?php echo $orders_count; ?></div>
    </div>
    <div class="admin-stat-card admin-stat-card--products">
        <div class="admin-stat-card__label">Products listed</div>
        <div class="admin-stat-card__value"><?php echo $products_count; ?></div>
    </div>
    <div class="admin-stat-card admin-stat-card--customers">
        <div class="admin-stat-card__label">Registered customers</div>
        <div class="admin-stat-card__value"><?php echo $customers_count; ?></div>
    </div>
</div>

<section class="admin-section">
    <h2 class="admin-section__title">Recent orders</h2>
    <div class="admin-table-wrap">
        <?php
        $recent_orders = $conn->query("SELECT o.order_id, o.total_amount, o.order_date, o.status, c.first_name, c.last_name FROM orders o JOIN customer c ON o.customer_id = c.customer_id ORDER BY o.order_id DESC LIMIT 8");
        ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($recent_orders->num_rows === 0) {
                    echo '<tr><td colspan="5" class="admin-muted" style="text-align:center;padding:2.5rem;">No orders yet.</td></tr>';
                }
                while ($row = $recent_orders->fetch_assoc()):
                    $st_slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($row['status']));
                    if ($st_slug === '') {
                        $st_slug = 'unknown';
                    }
                    ?>
                <tr>
                    <td><strong>#<?php echo (int) $row['order_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars(date('M j, Y · g:i A', strtotime($row['order_date']))); ?></td>
                    <td>$<?php echo number_format((float) $row['total_amount'], 2); ?></td>
                    <td><span class="admin-badge admin-badge--<?php echo htmlspecialchars($st_slug); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
