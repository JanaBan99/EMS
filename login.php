<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn->prepare("SELECT * FROM employees WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['employee_id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['system_role'];
            
            // Log activity
            require_once 'auth_utils.php';
            log_activity('Login', 'User logged in');
            
            header("Location: Dashboard_new.php");
            exit();
        }
    }
    $error = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Site EMS</title>
    <style>
        body { background: #f5f7fa; font-family: 'Segoe UI', sans-serif; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #ff9800; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Site EMS Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>