<?php
// admin/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}
$admin_css_base = __DIR__ . '/../../assets/css/style.css';
$admin_css_admin = __DIR__ . '/../../assets/css/admin.css';
$v1 = file_exists($admin_css_base) ? (string) filemtime($admin_css_base) : '1';
$v2 = file_exists($admin_css_admin) ? (string) filemtime($admin_css_admin) : '1';
$body_class = isset($admin_body_class) ? $admin_body_class : 'admin-area';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0a0c10">
    <title>Admin Panel — Ecommerce Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo htmlspecialchars($v1); ?>">
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo htmlspecialchars($v2); ?>">
</head>
<body class="<?php echo htmlspecialchars($body_class); ?>">

<header class="admin-nav">
    <div class="admin-nav__brand">Admin · Ecommerce Store</div>
    <nav class="admin-nav__links">
        <?php if (isset($_SESSION['admin_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_products.php">Products</a>
            <a href="manage_orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container">
