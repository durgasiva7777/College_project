<?php
session_start();

// DB Connection
$conn = new mysqli('localhost', 'root', '', 'project_p');
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

// Get POST data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die("Username and password are required.");
}

// -------------------
// 1. Check Admins table
// -------------------
$stmt = $conn->prepare("SELECT id, username, name, password FROM admins WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

if ($admin && $password === $admin['password']) {
    // Admin login successful
    $_SESSION['user_id'] = $admin['id'];
    $_SESSION['username'] = $admin['username'];
    $_SESSION['name'] = $admin['name'];
    $_SESSION['role'] = 'admin';
    header("Location: admin_dashboard.php");
    exit;
}

// -------------------
// 2. Check Employees table
// -------------------
$stmt = $conn->prepare("SELECT id, employee_id, name, assigned_lab, email, password, semester, year FROM employees WHERE employee_id = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();

if ($employee && $password === $employee['password']) {
    // Employee login successful
    $_SESSION['user_id'] = $employee['id'];
    $_SESSION['username'] = $employee['employee_id'];
    $_SESSION['name'] = $employee['name'];
    $_SESSION['role'] = 'employee';
    $_SESSION['assigned_lab'] = $employee['assigned_lab'];
    $_SESSION['email'] = $employee['email'];

    // ✅ Add semester and year to session
    $_SESSION['semester'] = $employee['semester'];
    $_SESSION['year'] = $employee['year'];

    header("Location: employee_dashboard.php");
    exit;
}

// Invalid credentials
die("Invalid username or password.");
