<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = sanitize($conn, $_POST['first_name']);
    $lastName = sanitize($conn, $_POST['last_name']);
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    $address = sanitize($conn, $_POST['address']);
    $phone = sanitize($conn, $_POST['phone']);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT customer_id FROM customer WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO customer (first_name, last_name, email, password, address, phone_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $hashed_password, $address, $phone);
        
        if ($stmt->execute()) {
            header("Location: login.php?msg=registered");
            exit();
        } else {
            $error = "Registration failed! Please try again.";
        }
    }
}
include 'includes/header.php';
?>

<div class="auth-card">
    <h2>Create an Account</h2>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <button type="submit" class="btn" style="width: 100%;">Sign Up</button>
        <p style="margin-top: 1rem; text-align: center;">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
