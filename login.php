<?php
require_once 'config.php';

if (isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT customer_id, first_name, password FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['customer_name'] = $row['first_name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
include 'includes/header.php';
?>

<div class="auth-card">
    <h2>Login</h2>
    <?php 
    if(isset($_GET['msg']) && $_GET['msg'] == 'registered') {
        echo "<div class='alert alert-success'>Registration successful. Please login.</div>";
    }
    if(isset($_GET['msg']) && $_GET['msg'] == 'login_required') {
        echo "<div class='alert alert-danger'>Please log in to continue.</div>";
    }
    if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; 
    ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn" style="width: 100%;">Login</button>
        <p style="margin-top: 1rem; text-align: center;">Don't have an account? <a href="register.php">Sign up here</a></p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
