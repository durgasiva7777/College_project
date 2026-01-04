<?php
session_start();
if(isset($_SESSION['role'])){
    // If already logged in, redirect automatically
    if($_SESSION['role'] === 'admin') header("Location: admin_dashboard.php");
    else if($_SESSION['role'] === 'employee') header("Location: employee_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - College Portal</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="centered-container">
    <div class="login-box">
        <h2>Login</h2>
        <form method="post" action="login_process.php">
            <label>Username / Employee ID</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>
