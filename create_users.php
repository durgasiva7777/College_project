<?php
// create_user.php — run once to insert admin/employee users
// Usage: call in browser or CLI (but remove afterwards)

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'project_p';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) die("DB connect error: " . $conn->connect_error);

// Define users to create:
$users = [
    ['username' => 'emp1', 'password' => 'empPass123', 'name' => 'Employee One', 'role'=>'employee'],
    ['username' => 'admin', 'password' => 'AdminSecure!23', 'name' => 'Site Admin', 'role'=>'admin'],
];

$stmt = $conn->prepare("INSERT INTO users (username, password_hash, name, role) VALUES (?, ?, ?, ?)");
if (!$stmt) die("Prepare failed: " . $conn->error);

foreach ($users as $u) {
    $hash = password_hash($u['password'], PASSWORD_DEFAULT);
    $stmt->bind_param('ssss', $u['username'], $hash, $u['name'], $u['role']);
    if ($stmt->execute()) {
        echo "Created user: {$u['username']} ({$u['role']})<br>";
    } else {
        echo "Failed to create {$u['username']}: " . $stmt->error . "<br>";
    }
}
$stmt->close();
$conn->close();
