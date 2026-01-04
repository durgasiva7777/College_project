<?php
session_start();
include 'db_connect.php';

error_log("POST data: " . print_r($_POST, true));

if (!isset($_SESSION['student_id'])) {
    die("You must log in first.");
}

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['experiment_id'], $_POST['employee_id'], $_POST['submission_data'])) {
        die("Missing required fields.");
    }

    $experiment_id = intval($_POST['experiment_id']);
    $employee_id = intval($_POST['employee_id']);
    $submission_data = $conn->real_escape_string($_POST['submission_data']);

    $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submission_data)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        die("Database error.");
    }

    // Bind parameters once with matching number of placeholders and vars
    $stmt->bind_param("iiis", $experiment_id, $student_id, $employee_id, $submission_data);

    if ($stmt->execute()) {
        echo "Experiment submitted successfully and sent for verification.";
    } else {
        echo "Error submitting experiment: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
