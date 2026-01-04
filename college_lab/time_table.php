<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lab Syllabus - Sri Vasavi Engineering College</title>
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
    <span>Welcome <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Student'; ?>...!</span>
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
  <div class="content" id="content">
    
  </div>
</div>

<script>
const timetableData = {
  "Chemistry": [
    "Monday 10:00 AM - 12:00 PM",
    "Thursday 2:00 PM - 4:00 PM"
  ],
  "Theory of Machines": [
    "Tuesday 9:00 AM - 11:00 AM",
    "Friday 1:00 PM - 3:00 PM"
  ]
};

// Automatically show subjects when page loads
window.addEventListener('DOMContentLoaded', showSubjects);


function showSubjects() {
  content.innerHTML = '<h2>Lab Subjects</h2><div id="subjects" class="subject-list"></div>';
  const subjectsContainer = document.getElementById('subjects');
  for(let subject in timetableData) {
    const div = document.createElement('div');
    div.className = 'subject-card';
    div.textContent = subject;
    div.addEventListener('click', () => showTimetable(subject));
    subjectsContainer.appendChild(div);
  }
}

function showTimetable(subject) {
  content.innerHTML = `<h2>${subject} Lab Timetable</h2>`;
  const backBtn = document.createElement('div');
  backBtn.className = 'back-btn';
  backBtn.textContent = ' Back to Subjects';
  backBtn.addEventListener('click', showSubjects);
  content.appendChild(backBtn);

  const list = document.createElement('div');
  list.className = 'experiment-list';

  timetableData[subject].forEach(time => {
    const timeRow = document.createElement('div');
    timeRow.className = 'time-row';
    timeRow.textContent = time;
    list.appendChild(timeRow);
  });

  content.appendChild(list);
}
</script>

</body>
</html>
