<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: login.html");
    exit();
}

$subject = isset($_GET['subject']) ? htmlspecialchars($_GET['subject']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Updated Experiments - Sri Vasavi Engineering College</title>
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="css/main.css">
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
        <a href="updated_exp.php" >Updated Experiments</a>
        <a href="completed_exp.php">Completed Experiments</a>
        <a href="retake_exp.php">Retake Experiments</a>
        <a href="time_table.php">Lab Time Table</a>
       
        <a href="profile.php">Profile</a>
        <a href="syllabus.php">Lab Syllabus</a>
        <a href="practice.php">Practice Section</a>
    </div>

    <!-- Content -->
    <div class="content">
        <?php if (empty($subject)): ?>
            <!-- Subject Selection Page -->
            <h1 class="page-title">Select Subject</h1>
            <div class="subject-selection">
                <div class="subject-card" onclick="selectSubject('chemistry')">
                    <h3>Chemistry</h3>
                </div>
                <div class="subject-card" onclick="selectSubject('Theoryofmachines')">
                    <h3>Theory of Machines</h3>
                </div>
            </div>
        <?php else: ?>
            <!-- Experiments Page -->
            <h1 class="page-title">Updated Experiments for <?php echo htmlspecialchars($subject); ?></h1>
            <button class="back-btn" onclick="goBackToSubjects()"> Back to Subjects</button>
            <div id="experimentContainer"></div>
        <?php endif; ?>
    </div>
</div>

<script>
  const subject = "<?php echo $subject; ?>";
  
  const data = {
    "chemistry": [
      "Experiment 1: Introduction to Chemistry Laboratory",
      "Experiment 2: Estimation of ferrous Ion",
      "Experiment 3: Preparation of Phenol",
      "Experiment 4: Determination of Strength of an Acid in Pb - Acid Battery",
      "Experiment 5: Conductometric Titration (Strong Acid vs Strong Base)",
      "Experiment 6: Conductomatric Titration (Weak Acid vs Strong Base)",
      "Experiment 7: Determination of Cell Constant and Conductance of Solution",
      "Experiment 8: Estimation of Iron using Potentiometry",
      "Experiment 9: Verify Beer's-Lamberts Law",
      "Experiment 10: WaveLength Measurement of Sample through UV-Visible Spectroscopy",
      "Experiment 11: Measurement of 10 dq By Spectrophotometric Method",
      "Experiment 12: Identification of Simple Organic Spectrophotometric Method",
      "Experiment 13: Preparation of Nanomaterials"
    ],
    "Theoryofmachines": [
      "Experiment 1: Hartnev Governor",
      "Experiment 2: Whirling of Shaft",
      "Experiment 3: Natural Frequency of Single Degree Undamped Free Vibrations",
      "Experiment 4: Compound Screw Jack",
      "Experiment 5: Study of Gears"
    ]
  };

  function selectSubject(subject) {
      window.location.href = `updated_exp.php?subject=${subject}`;
  }

  function goBackToSubjects() {
      window.location.href = 'updated_exp.php';
  }

  function showExperiments(subject) {
      const container = document.getElementById('experimentContainer');
      container.innerHTML = '';

      if (!subject || !data[subject]) {
          container.innerHTML = '<div class="no-experiments">No experiments available for this subject.</div>';
          return;
      }

      const experiments = data[subject];
      const list = document.createElement('div');
      list.className = 'experiment-list';

      experiments.forEach((exp, index) => {
          const row = document.createElement('div');
          row.className = 'experiment-row';

          const item = document.createElement('div');
          item.className = 'experiment-item';
          item.textContent = exp;

          const btn = document.createElement('button');
          btn.className = 'start-btn';
          btn.textContent = 'Start';
btn.addEventListener('click', () => {
    const expNumberMatch = exp.match(/Experiment\s*(\d+)/i);
    const expNumber = expNumberMatch ? expNumberMatch[1] : 1;
    
    const url = `experiments/${subject}/exp${expNumber}.php`;
    window.location.href = url;
});
          row.appendChild(item);
          row.appendChild(btn);
          list.appendChild(row);
      });

      container.appendChild(list);
  }

  // Show experiments if subject is selected
  <?php if (!empty($subject)): ?>
      showExperiments(subject);
  <?php endif; ?>
</script>

</body>
</html>