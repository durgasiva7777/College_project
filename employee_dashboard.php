<?php
session_start();

// SESSION CHECK
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'employee') {
    header('Location: index.php');
    exit;
}

$display_name = htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'], ENT_QUOTES);
$username = $_SESSION['username'] ?? '';
$assigned_lab = $_SESSION['assigned_lab'] ?? '-';
$email = $_SESSION['email'] ?? '-';
$semester = $_SESSION['semester'] ?? '-';
$year = $_SESSION['year'] ?? '-';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $display_name ?> — Employee Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#f0f0f3;
  --card:#fff;
  --accent:#003366;
  --muted:#666;
  --primary:#bd2626;
}

/* General Body */
body {
  margin:0;
  font-family:'Poppins',sans-serif;
  background:var(--bg);
  color:#222;
}

/* Header */
header {
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:16px 32px;
  background:var(--bg);
  box-shadow: inset 6px 6px 12px #c5c5c5, inset -6px -6px 12px #fff;
  border-radius:12px;
  margin:16px auto;
  max-width:1200px;
}
header h1 { margin:0; font-size:20px; color:var(--primary); }
header form button {
  background:#f44336;
  color:#fff;
  padding:10px 16px;
  border:none;
  border-radius:10px;
  cursor:pointer;
  font-weight:600;
  box-shadow:6px 6px 12px #c5c5c5, -6px -6px 12px #fff;
  transition: all 0.2s ease-in-out;
}
header form button:hover { box-shadow: inset 6px 6px 12px #c5c5c5, inset -6px -6px 12px #fff; }

/* Layout */
.container {
  display:flex;
  gap:20px;
  max-width:1200px;
  margin:20px auto;
  padding:0 16px;
}
.sidebar {
  width:280px;
  display:flex;
  flex-direction:column;
  gap:16px;
}
.menu-item {
  background:var(--bg);
  padding:16px;
  border-radius:12px;
  text-align:center;
  font-weight:600;
  box-shadow:8px 8px 16px #c5c5c5,-8px -8px 16px #fff;
  cursor:pointer;
  color:black;
  text-decoration:none;
  transition: 0.2s;
}
.menu-item:hover { box-shadow: inset 6px 6px 12px #c5c5c5, inset -6px -6px 12px #fff; }

/* Main Cards */
.main { flex:1; display:flex; flex-direction:column; gap:20px; }
.card {
  background:var(--card);
  border-radius:16px;
  padding:24px;
  box-shadow:8px 8px 16px #c5c5c5,-8px -8px 16px #fff;
  transition: 0.2s;
}
.card h2 { color:var(--primary); margin-top:0; }
.info p { margin:6px 0; }

/* Tables */
#submissions-table, #experiments-table {
  width:100%;
  border-collapse:collapse;
  text-align:center;
}
#submissions-table th, #submissions-table td,
#experiments-table th, #experiments-table td {
  border:1px solid #ccc;
  padding:10px;
  font-size:14px;
}
#submissions-table th, #experiments-table th {
  background-color:#00C7E6;
  color:#fff;
  font-weight:600;
}
#submissions-table tr:nth-child(even), #experiments-table tr:nth-child(even){ background:#f9f9f9; }
#submissions-table tr:hover, #experiments-table tr:hover{ background:#e6f7ff; }

/* Buttons */
.action-btn {
  padding:6px 12px;
  border-radius:8px;
  text-decoration:none;
  font-weight:500;
  color:#fff;
  background:#bd2626;
  cursor:pointer;
}

/* Neomorphic Profile Card */
#profile-card {
  display:none;
  padding:40px;
  max-width:800px;
  margin:0 auto;
  border-radius:20px;
  background:#f0f0f3;
  box-shadow: 8px 8px 16px #c5c5c5, -8px -8px 16px #fff;
}
#profile-card h2 {
  text-align:center;
  color:var(--primary);
  margin-bottom:24px;
}
</style>
</head>
<body>

<header>
    <h1>Welcome, <?= $display_name ?> </h1>
    <form method="post" action="logout.php">
        <button type="submit">Logout</button>
    </form>
</header>

<div class="container">
  <aside class="sidebar">
    <div class="menu-item" onclick="showCard('profile-card')">Profile</div>
    <div class="menu-item" onclick="showCard('update-experiment-card')">Update Experiment</div>
    <div class="menu-item" onclick="showCard('verify-students-card')">Verify Students</div>
    <div class="menu-item" onclick="showCard('schedule-lab-card')">Schedule Lab Exam</div>
  </aside>

  <section class="main">
    <!-- Employee Profile -->
    <div class="card" id="profile-card" style="display:none;">
      <h2>Employee Profile</h2>
      <div class="info">
        <p><strong>Username:</strong> <?= htmlspecialchars($username, ENT_QUOTES) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($display_name, ENT_QUOTES) ?></p>
        <p><strong>Assigned Lab:</strong> <?= htmlspecialchars($assigned_lab, ENT_QUOTES) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email, ENT_QUOTES) ?></p>
        <p><strong>Semester:</strong> <?= htmlspecialchars($semester, ENT_QUOTES) ?></p>
        <p><strong>Year:</strong> <?= htmlspecialchars($year, ENT_QUOTES) ?></p>
      </div>
    </div>

    <!-- Update Experiment -->
    <div class="card" id="update-experiment-card" style="display:none;">
      <h2>Update Lab Experiment</h2>
      <form method="post" action="update_experiment.php">
        <label>Experiment ID / No</label><br>
        <input name="experiment_no" style="width:100%;padding:10px;margin:8px 0;border:1px solid #eee;border-radius:8px"><br>
        <label>Details</label><br>
        <textarea name="details" rows="6" style="width:100%;padding:10px;border:1px solid #eee;border-radius:8px"></textarea><br>
        <button class="action-btn" type="submit">Update Experiment</button>
      </form>
    </div>

    <!-- Verify Students -->
    <div class="card" id="verify-students-card" style="display:none;">
      <h2>Verify Students</h2>
      <p>Feature to list and verify student submissions goes here.</p>
    </div>

    <!-- Schedule Lab Exam -->
    <div class="card" id="schedule-lab-card" style="display:none;">
      <h2>Schedule Lab Exam</h2>
      <form method="post" action="schedule_lab.php">
        <label>Date</label><br>
        <input name="exam_date" type="date" style="padding:10px;width:100%;margin:8px 0;border:1px solid #eee;border-radius:8px"><br>
        <label>Section</label><br>
        <select name="section" style="padding:10px;width:100%;margin:8px 0;border:1px solid #eee;border-radius:8px">
          <option>A</option><option>B</option><option>C</option><option>D</option>
        </select><br>
        <button class="action-btn" type="submit">Schedule Exam</button>
      </form>
    </div>
  </section>
</div>

<script>
function hideAllCards(){
  document.querySelectorAll('.main .card').forEach(c=>c.style.display='none');
}
function showCard(id){
  hideAllCards();
  document.getElementById(id).style.display='block';
}
// Show profile initially
// showCard('profile-card');
</script>

</body>
</html>
