<?php
session_start();

// DB connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'project_p'; // Your DB name

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Use plain text check
    $stmt = $conn->prepare("SELECT * FROM students WHERE username = ? AND password_hash = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($student = $result->fetch_assoc()) {
        // Login successful
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['name'];
        $_SESSION['student_username'] = $student['username'];
        $_SESSION['student_year'] = $student['year'];
        $_SESSION['student_semester'] = $student['semester'];

        header("Location: student_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Login</title>
<style>
    .centered-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}
body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
  font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
  background-image: url('images/login.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  position: relative;
}

body::before {
  content: "";
  position: fixed;
  left: 0; top: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.25);
  z-index: 0;
}

.centered-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  position: relative;
  z-index: 1;
}

.login-box {
  background: rgba(255, 255, 255, 0.85);
  border-radius: 28px;
  padding: 32px 38px 54px 38px;
  box-shadow: 0 8px 40px 0 rgba(0,0,0,0.19);
  backdrop-filter: blur(7px);
  min-width: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.icon-container {
  width: 64px;
  height: 64px;
  margin-bottom: 10px;
  background: url('icon.png') no-repeat center center;
  background-size: contain;
}

.login-box label {
  display: block;
  font-weight: 600;
  color: #232e45;
  margin-top: 16px;
  letter-spacing: 0.05em;
}

.input-box {
  margin-top: 6px;
  margin-bottom: 6px;
  width: 100%;
}

.input-box input {
  width: 100%;
  padding: 10px 16px;
  border: none;
  border-radius: 9px;
  background: #eef2f7;
  font-size: 1rem;
  margin-bottom: 4px;
  box-sizing: border-box;
  transition: box-shadow 0.2s;
}

.input-box input:focus {
  box-shadow: 0 0 0 2px #4f8cff33;
  outline: none;
}

.sign-in-btn {
  width: 100%;
  margin-top: 22px;
  background: linear-gradient(90deg, #375dff, #548cff);
  color: #fff;
  font-size: 1.07rem;
  font-weight: 700;
  border: none;
  padding: 12px;
  border-radius: 9px;
  cursor: pointer;
  letter-spacing: 0.05em;
  box-shadow: 0 2px 6px rgba(75, 122, 251, 0.16);
  transition: background 0.2s, box-shadow 0.2s;
}
.sign-in-btn:hover {
  background: linear-gradient(90deg, #548cff, #375dff);
  box-shadow: 0 4px 12px rgba(75, 122, 251, 0.24);
}

</style>
</head>
<body>
  <div class="centered-container">
    <div class="login-box">
      <div class="logo">
        <img src="images/vasavi.png" alt="Logo" style="width:80px;height:auto;">
      </div>

      <form id="loginForm" method="post" action="studlogin.php">
        <div class="input-box">
           Username:<br></br>
           <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>
        <div class="input-box">
           Password:<br></br>
           <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="sign-in-btn">Log In</button>
      </form>
    </div>
  </div>

  <!-- Optional JS to intercept submit and show client side messages (not required) -->
  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e){
      // let browser do the submit; we keep small client-side UX enhancements optional.
    });
  </script>
</body>
</html>
