<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}
$css_path = __DIR__ . '/../assets/css/style.css';
$css_ver = file_exists($css_path) ? (string) filemtime($css_path) : '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0a0c0f">
    <title>Ecommerce Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo htmlspecialchars($css_ver); ?>">
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <a href="index.php" class="nav-brand">Ecommerce Store</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['customer_id'])): ?>
                <a href="account.php">My account</a>
            <?php endif; ?>
            <a href="cart.php">Cart</a>
            <?php if (isset($_SESSION['customer_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn btn-nav">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
