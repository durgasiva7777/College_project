<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Profile - Sri Vasavi Engineering College</title>
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/main.css">
</head>
<body>

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
  
  <!-- Welcome Banner with Logout Button -->
  <div class="student-name-banner">
    <span>Welcome <?php echo htmlspecialchars($_SESSION['name']); ?>...!</span>
    <a href="logout.php" class="logout-btn"> Logout</a>
  </div>

  <!-- Layout -->
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="updated_exp.php">Updated Experiments</a>
      <a href="completed_exp.php">Completed Experiments</a>
      <a href="retake_exp.php">Retake Experiments</a>
      <a href="time_table.php">Lab Time Table</a>
     
      <a href="profile.php">Profile</a>
      <a href="syllabus.php">Lab Syllabus</a>
      <a href="practice.php">Practice Section</a>
    </div>

    <!-- Content -->
    <div class="content">
      <div class="profile-card">
        <h2>Student Profile</h2>
        <div class="profile-item"><span>Name:</span> <?php echo htmlspecialchars($_SESSION['name']); ?></div>
        <div class="profile-item"><span>Roll Number:</span> <?php echo htmlspecialchars($_SESSION['roll_number']); ?></div>
        <div class="profile-item"><span>Branch:</span> <?php echo htmlspecialchars($_SESSION['branch']); ?></div>
        <div class="profile-item"><span>Semester:</span> <?php echo htmlspecialchars($_SESSION['semester']); ?></div>
        <div class="profile-item"><span>Email:</span> <?php echo htmlspecialchars($_SESSION['email']); ?></div>
        <div class="profile-item"><span>Phone:</span> <?php echo htmlspecialchars($_SESSION['phone']); ?></div>
      </div>
    </div>
  </div>

</body>
</html>