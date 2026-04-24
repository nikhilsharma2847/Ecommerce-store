<?php
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php?msg=login_required');
    exit();
}

$customer_id = (int) $_SESSION['customer_id'];
$profile_success = '';
$profile_error = '';

$stmt = $conn->prepare('SELECT customer_id, first_name, last_name, email, address, phone_number FROM customer WHERE customer_id = ?');
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

if (!$customer) {
    session_destroy();
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = sanitize($conn, $_POST['first_name']);
    $last_name = sanitize($conn, $_POST['last_name']);
    $address = sanitize($conn, $_POST['address']);
    $phone_number = sanitize($conn, $_POST['phone_number']);

    if ($first_name === '' || $last_name === '') {
        $profile_error = 'First and last name are required.';
    } else {
        $up = $conn->prepare('UPDATE customer SET first_name = ?, last_name = ?, address = ?, phone_number = ? WHERE customer_id = ?');
        $up->bind_param('ssssi', $first_name, $last_name, $address, $phone_number, $customer_id);
        if ($up->execute()) {
            $profile_success = 'Profile updated successfully.';
            $customer['first_name'] = $first_name;
            $customer['last_name'] = $last_name;
            $customer['address'] = $address;
            $customer['phone_number'] = $phone_number;
            $_SESSION['customer_name'] = $first_name;
        } else {
            $profile_error = 'Could not update profile. Please try again.';
        }
    }
}

$orders_stmt = $conn->prepare(
    'SELECT o.order_id, o.total_amount, o.order_date, o.status,
            p.payment_id, p.payment_method, p.amount AS payment_amount, p.payment_date, p.status AS payment_status
     FROM orders o
     LEFT JOIN payment p ON p.order_id = o.order_id
     WHERE o.customer_id = ?
     ORDER BY o.order_date DESC'
);
$orders_stmt->bind_param('i', $customer_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();

$orders = [];
while ($row = $orders_result->fetch_assoc()) {
    $orders[] = $row;
}

$item_stmt = $conn->prepare(
    'SELECT oi.quantity, oi.price, p.name
     FROM order_items oi
     INNER JOIN product p ON p.product_id = oi.product_id
     WHERE oi.order_id = ?
     ORDER BY oi.order_item_id ASC'
);

include 'includes/header.php';
?>

<div class="account-page">
    <header class="account-header">
        <h1>My account</h1>
        <p class="account-intro">Manage your profile, view orders, and see payment details.</p>
    </header>

    <div class="account-layout">
        <section id="profile" class="account-card" aria-labelledby="profile-heading">
            <h2 id="profile-heading">Profile</h2>
            <?php if ($profile_success !== ''): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($profile_success); ?></div>
            <?php endif; ?>
            <?php if ($profile_error !== ''): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($profile_error); ?></div>
            <?php endif; ?>
            <form method="post" action="" class="account-form">
                <input type="hidden" name="update_profile" value="1">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required
                               value="<?php echo htmlspecialchars($customer['first_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required
                               value="<?php echo htmlspecialchars($customer['last_name']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" readonly
                           value="<?php echo htmlspecialchars($customer['email']); ?>"
                           title="Email cannot be changed here">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars((string) $customer['address']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone</label>
                    <input type="text" id="phone_number" name="phone_number" class="form-control"
                           value="<?php echo htmlspecialchars((string) $customer['phone_number']); ?>">
                </div>
                <button type="submit" class="btn">Save profile</button>
            </form>
        </section>

        <section id="orders" class="account-card account-card--wide" aria-labelledby="orders-heading">
            <h2 id="orders-heading">Orders &amp; payments</h2>
            <?php if (count($orders) === 0): ?>
                <p class="account-empty">You have not placed any orders yet. <a href="index.php">Browse products</a></p>
            <?php else: ?>
                <div class="order-list">
                    <?php foreach ($orders as $order): ?>
                        <?php
                        $oid = (int) $order['order_id'];
                        $item_stmt->bind_param('i', $oid);
                        $item_stmt->execute();
                        $items_res = $item_stmt->get_result();
                        $line_items = [];
                        while ($ir = $items_res->fetch_assoc()) {
                            $line_items[] = $ir;
                        }
                        ?>
                        <article class="order-block" id="order-<?php echo $oid; ?>">
                            <div class="order-block__head">
                                <div>
                                    <span class="order-id">Order #<?php echo $oid; ?></span>
                                    <span class="order-date"><?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($order['order_date']))); ?></span>
                                </div>
                                <div class="order-meta">
                                    <span class="order-status order-status--<?php echo htmlspecialchars(preg_replace('/[^a-z0-9]+/', '-', strtolower($order['status']))); ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                    <strong class="order-total">$<?php echo number_format((float) $order['total_amount'], 2); ?></strong>
                                </div>
                            </div>

                            <?php if (!empty($order['payment_id'])): ?>
                                <div class="payment-strip">
                                    <strong>Payment</strong>
                                    <span><?php echo htmlspecialchars($order['payment_method']); ?></span>
                                    <span class="payment-muted"><?php echo htmlspecialchars($order['payment_status']); ?></span>
                                    <span>$<?php echo number_format((float) $order['payment_amount'], 2); ?></span>
                                    <span class="payment-muted"><?php echo htmlspecialchars(date('M j, Y', strtotime($order['payment_date']))); ?></span>
                                </div>
                            <?php else: ?>
                                <p class="payment-pending">No payment record for this order.</p>
                            <?php endif; ?>

                            <?php if (count($line_items) > 0): ?>
                                <table class="cart-table order-items-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($line_items as $li): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($li['name']); ?></td>
                                                <td><?php echo (int) $li['quantity']; ?></td>
                                                <td>$<?php echo number_format((float) $li['price'], 2); ?></td>
                                                <td>$<?php echo number_format((float) $li['price'] * (int) $li['quantity'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
