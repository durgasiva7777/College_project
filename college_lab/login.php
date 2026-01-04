<?php
session_start();
include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements for security (recommended), but keeping your style here:
    $sql = "SELECT * FROM students WHERE username='$username' AND password='$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Store important user data in session, add student_id here
        $_SESSION['student_id'] = $row['student_id'];  // Add this line

        $_SESSION['name'] = $row['name'];
        $_SESSION['roll_number'] = $row['roll_number'];
        $_SESSION['branch'] = $row['branch'];
        $_SESSION['semester'] = $row['semester'];
        $_SESSION['email']= $row['email'];
        $_SESSION['phone']= $row['phone'];

        header("Location: profile.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='login.html';</script>";
    }
    $conn->close();
}
?>
