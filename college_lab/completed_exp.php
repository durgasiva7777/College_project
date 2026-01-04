<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.html");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT submission_id, experiment_id, verification_date, submission_data 
        FROM submissions 
        WHERE student_id = ? AND verification_status = 'Verified' 
        ORDER BY verification_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$completed_submissions = [];
while ($row = $result->fetch_assoc()) {
    $completed_submissions[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Completed Experiments - Sri Vasavi Engineering College</title>
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/completed_exp.css">

</head>
<body>

<!-- Header Section -->
<div class="header-container">
    <div class="header-content">
        <img src="college_logo.png" 
             alt="Sri Vasavi Engineering College Logo" class="college-logo" />
        <div class="header-text">
            <div class="college-name">SRI VASAVI ENGINEERING COLLEGE (AUTONOMOUS)</div>
            <div class="college-location">Pedatadepalli, Tadepalligudem - 534101, West Godavari District (AP)</div>
        </div>
        <img src="student.jpg" 
             alt="Student Profile Image" class="student-image" />
    </div>
</div>

<!-- Welcome Banner with Logout -->
<div class="student-name-banner">
    <span>Welcome <?php echo htmlspecialchars($_SESSION['name']); ?>...!</span>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- Layout -->
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="updated_exp.php">Updated Experiments</a>
        <a href="completed_exp.php" >Completed Experiments</a>
        <a href="retake_exp.php">Retake Experiments</a>
        <a href="time_table.php">Lab Time Table</a>
        <a href="profile.php">Profile</a>
        <a href="syllabus.php">Lab Syllabus</a>
        <a href="practice.php">Practice Section</a>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div class="profile-container">
            <h2>Completed Experiments</h2>
            <?php if (count($completed_submissions) > 0): ?>
                <?php foreach ($completed_submissions as $exp): ?>
                <div class="experiment-item">
                    <h3>Experiment ID: <?php echo htmlspecialchars($exp['experiment_id']); ?></h3>
                    <div class="date">Verified on: <?php echo htmlspecialchars($exp['verification_date'] ?? 'Unknown'); ?></div>
                    <div><?php echo $exp['submission_data']; ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-experiments">No completed experiments found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>