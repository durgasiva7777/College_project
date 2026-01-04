<?php
session_start();

// DB config
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'project_p';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'] ?? '';
    $password    = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, name, assigned_lab FROM teachers WHERE employee_id = ? AND password = ? LIMIT 1");
    $stmt->bind_param('ss', $employee_id, $password);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user) {
        // LOGIN SUCCESS
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $employee_id;
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = 'employee';  // important for dashboard check

        header('Location: employee_dashboard.php');
        exit;
    } else {
        $error = 'Invalid Employee ID or Password';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Employee Login</title>
</head>
<body>
<h2>Employee Login</h2>
<?php if($error) echo "<p style='color:red'>$error</p>"; ?>
<form method="post">
    <label>Employee ID:</label><br>
    <input type="text" name="employee_id" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
