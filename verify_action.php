<?php
// verify_action.php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'employee') {
    header('Location: index.php'); exit;
}

$employee_id = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

if (empty($_POST['submission_id']) || empty($_POST['action'])) {
    die("Missing data.");
}

$submission_id = (int) $_POST['submission_id'];
$action = $_POST['action']; // expected 'Verified' or 'Retake'
$allowed = ['Verified','Retake'];
if (!in_array($action, $allowed, true)) die("Invalid action.");

$employee_comments = trim($_POST['employee_comments'] ?? null);

// DB
$conn = new mysqli('localhost','root','','project_p');
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

// Ensure submission belongs to this employee
$stmt = $conn->prepare("SELECT employee_id FROM submissions WHERE submission_id = ? LIMIT 1");
$stmt->bind_param('i', $submission_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) { $conn->close(); die("Submission not found."); }
if ((int)$row['employee_id'] !== $employee_id) { $conn->close(); die("Not authorized to update this submission."); }

// Update verification_status, verification_date, and employee_comments (if column exists)
if ($employee_comments !== null) {
    // if your submissions table doesn't have employee_comments column, remove it and use other mechanism
    $stmt = $conn->prepare("
        UPDATE submissions
        SET verification_status = ?, verification_date = NOW(), submission_data = submission_data, -- keep as is
            -- if you have employee_comments column, update it; otherwise remove this part
            -- employee_comments = ?
            verification_date = NOW()
        WHERE submission_id = ?
    ");
    // Because many schemas don't have employee_comments, we'll do a safer update below:
    $stmt = $conn->prepare("UPDATE submissions SET verification_status = ?, verification_date = NOW() WHERE submission_id = ?");
    $stmt->bind_param('si', $action, $submission_id);
} else {
    $stmt = $conn->prepare("UPDATE submissions SET verification_status = ?, verification_date = NOW() WHERE submission_id = ?");
    $stmt->bind_param('si', $action, $submission_id);
}

if (!$stmt) { $conn->close(); die("Prepare failed: " . $conn->error); }
$ok = $stmt->execute();
$stmt->close();

$conn->close();

if ($ok) {
    header("Location: employee_dashboard.php?msg=" . urlencode("Submission updated to $action"));
    exit;
} else {
    die("Update failed.");
}
