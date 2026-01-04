<?php
session_start();
$PROJECT_FOLDER = 'project_p';
if (!isset($_SESSION['student_id'])) {
    header("Location: studlogin.php");
    exit;
}

$student_name     = $_SESSION['student_name'] ?? 'Student';
$student_username = $_SESSION['student_username'] ?? '';
$student_year     = $_SESSION['student_year'] ?? '-';
$student_semester = $_SESSION['student_semester'] ?? '-';

$conn = new mysqli("localhost", "root", "", "project_p");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// fetch all labs
$labs = [];
$sql = "SELECT * FROM labs ORDER BY name";
$result = $conn->query($sql);
if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $labs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Dashboard</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --max-width: 1800px;
  --bg: #f0f3f6;
  --card: #ffffff;
  --muted: #6f7780;
  --accent: #00c7e6;
  --shadow-light: rgba(255,255,255,0.95);
  --shadow-dark: rgba(146,160,175,0.30);
  --sidebar-w: 320px;
  --radius: 16px;
  --gap: 22px;
  --page-padding: 20px;
}

*{box-sizing:border-box}
html,body { height:100%; margin:0; font-family:'Poppins',sans-serif; background:var(--bg); -webkit-font-smoothing:antialiased; color:#1f2933; overflow: hidden;}
a{color:inherit;text-decoration:none}

/* Page frame */
.page {
  max-width: calc(var(--max-width) + 60px);
  margin: 26px auto;
  padding: var(--page-padding);
}

/* Header */
.header {
  display:flex;
  align-items:center;
  gap:22px;
  padding:22px;
  border-radius:var(--radius);
  background: linear-gradient(180deg, var(--card), #fbfdff);
  box-shadow: 18px 18px 36px var(--shadow-dark), -18px -18px 36px var(--shadow-light);
}
.header .logo {
  width:160px;
  height:110px;
  object-fit:contain;
  border-radius:12px;
  background:linear-gradient(180deg,#fff,#f6fbff);
  padding:10px;
  box-shadow: inset 4px 4px 8px rgba(0,0,0,0.02);
  /* removed margin-left so sidebar won't shift */
}
.header .college-info { flex:1; }
.header h1 { margin:0; font-size:20px; color:#073b57; font-weight:800; letter-spacing:0.2px; }
.header p { margin:6px 0 0 0; font-size:13px; color:var(--muted); }

/* Top bar */
.topbar {
  margin-top:18px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:14px;
  border-radius:var(--radius);
  background: linear-gradient(180deg, #f8fbff, #ffffff);
  box-shadow: 12px 12px 26px var(--shadow-dark), -12px -12px 26px var(--shadow-light);
}
.topbar .welcome { font-weight:700; color:#0d4b66; font-size:15px; }
.topbar form { margin:0; }

/* Layout: sidebar + main */
.layout {
  margin-top:20px;
  display:flex;
  gap:var(--gap);
  align-items:flex-start;
}

/* Sidebar: sticky so it doesn't move */
.sidebar {
  width:var(--sidebar-w);
  display:flex;
  flex-direction:column;
  gap:12px;
  padding:16px;
  border-radius:18px;
  background: linear-gradient(180deg,#f9fcff,#ffffff);
  box-shadow: 16px 16px 36px var(--shadow-dark), -16px -16px 36px var(--shadow-light);
  flex-shrink:0;
  position: sticky;
  top: 28px; /* stays visible while page scrolls */
  height: calc(100vh - 160px);
  overflow: auto;
}
.sidebar .menu-item {
  padding:14px 12px;
  text-align:center;
  border-radius:12px;
  cursor:pointer;
  font-weight:800;
  color:#0c3a52;
  background: linear-gradient(180deg, rgba(255,255,255,0.7), rgba(247,250,252,0.85));
  box-shadow: 8px 8px 18px rgba(0,0,0,0.04), -8px -8px 18px rgba(255,255,255,0.98);
  transition: transform .12s ease, box-shadow .12s ease, background .12s ease;
}
.sidebar .menu-item:hover { transform: translateY(-3px); box-shadow: inset 6px 6px 12px rgba(0,0,0,0.02), -6px -6px 12px rgba(255,255,255,0.95); }
.sidebar .menu-item.active { background: linear-gradient(90deg,#e9fbff,#ffffff); border-left:4px solid var(--accent); color:#00495f; }

/* Main content */
.main {
  flex:1;
  min-height:460px;
  max-width: calc(var(--max-width) - var(--sidebar-w));
}

/* Card (neumorphic white) */
.card {
  background: var(--card);
  border-radius:18px;
  padding:22px;
  margin-bottom:20px;
  box-shadow: 20px 20px 44px var(--shadow-dark), -20px -20px 44px var(--shadow-light);
  transition: box-shadow .16s ease, transform .14s ease;
}
.card:hover { box-shadow: 10px 10px 26px rgba(0,0,0,0.06), -10px -10px 26px rgba(255,255,255,0.98); transform: translateY(-4px); }
.card h2 { margin:0 0 14px 0; color:#0b475f; font-size:20px; }

/* Experiment list container: set max-height & scroll */
.experiment-list { 
  list-style:none; 
  padding:0; 
  margin:0; 
  display:grid; 
  gap:12px; 
  max-height: 520px;            /* limit height */
  overflow: auto;              /* enable scrolling */
  padding-right: 8px;          /* allow space for scrollbar */
}

/* Lab-specific experiments area: also scrollable if long */
.lab-experiments {
  max-height: 540px;
  overflow: auto;
  padding-right: 8px;
}

/* style native scrollbar (modern browsers) */
.experiment-list::-webkit-scrollbar,
.lab-experiments::-webkit-scrollbar {
  width: 10px;
}
.experiment-list::-webkit-scrollbar-track,
.lab-experiments::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 10px;
}
.experiment-list::-webkit-scrollbar-thumb,
.lab-experiments::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #d6eaf0, #b6e1ea);
  border-radius: 10px;
  border: 2px solid transparent;
  background-clip: padding-box;
}

/* Experiment item */
.experiment-list li {
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:14px;
  border-radius:12px;
  background: linear-gradient(180deg,#ffffff,#fbfdff);
  box-shadow: 10px 10px 20px rgba(0,0,0,0.03), -10px -10px 20px rgba(255,255,255,0.95);
}
.experiment-list li:hover { transform: translateY(-3px); }
.experiment-list li .title { font-weight:700; color:#093544; font-size:15px; }
.experiment-list li .meta { font-size:13px; color:var(--muted); }

/* Buttons */
.btn { border:0; padding:9px 16px; border-radius:12px; font-weight:800; cursor:pointer; box-shadow: 8px 8px 16px rgba(0,0,0,0.04), -8px -8px 16px rgba(255,255,255,0.95); transition: transform .12s ease, box-shadow .12s ease; }
.btn.primary { background: linear-gradient(180deg,var(--accent),#00a3bf); color:white; letter-spacing:0.3px; }
.btn.ghost { background:transparent; border:1px solid #e7fbff; color:#0b3b58; }
.btn.small { padding:7px 10px; font-size:14px; }

/* Profile card */
.profile-grid { display:flex; gap:18px; align-items:center; }
.profile-avatar { width:110px; height:110px; border-radius:18px; display:flex; align-items:center; justify-content:center; font-weight:800; color:#00a3bd; background: linear-gradient(180deg,#fff,#f4fbfd); box-shadow: 12px 12px 26px rgba(0,0,0,0.04), -12px -12px 26px rgba(255,255,255,0.95); font-size:40px; }
.profile-info p { margin:8px 0; color:#214048; font-weight:600; }

/* small screens tweaks */
@media (max-width:1100px){
  :root{ --sidebar-w:260px; }
  .page { padding:14px; }
  .header .logo { width:120px; height:80px; }
  .topbar .welcome { font-size:14px; }
  .sidebar { position: relative; height:auto; top:0; overflow:visible; }
}
@media (max-width:900px){
  .layout { flex-direction:column; gap:12px; }
  .sidebar { width:100%; display:flex; flex-direction:row; overflow:auto; padding:8px; gap:8px; }
  .sidebar .menu-item { flex:1; white-space:nowrap; font-size:13px; }
  .header { flex-direction:column; align-items:flex-start; gap:12px; }
  .profile-avatar { width:86px; height:86px; font-size:30px; }
}
</style>
</head>
<body>

<div class="page">

  <!-- Header -->
  <header class="header">
    <img src="images/vasavi.png" class="logo" alt="College Logo">
    <div class="college-info">
      <h1>SRI VASAVI ENGINEERING COLLEGE (AUTONOMOUS)</h1>
      <p>Pedatadepalli, Tadepalligudem - 534101</p>
    </div>
    <div style="text-align:right;">
      <img src="coll.jpg" alt="Profile" style="width:96px;height:96px;border-radius:12px;object-fit:cover;box-shadow:10px 10px 22px rgba(0,0,0,0.04);">
    </div>
  </header>

  <!-- Top bar -->
  <div class="topbar">
    <div class="welcome">Welcome <strong><?= htmlspecialchars($student_name ?? '', ENT_QUOTES | ENT_HTML5) ?></div>
    <form method="post" action="student_logout.php" style="margin:0;">
      <button class="btn primary" type="submit">LOG OUT</button>
    </form>
  </div>

  <!-- Layout -->
  <div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar" aria-label="Student menu">
      <div class="menu-item active" data-section="updated" onclick="showPhase('updated')">Updated Experiments</div>
      <div class="menu-item" data-section="completed" onclick="showPhase('completed')">Completed Experiments</div>
      <div class="menu-item" data-section="retake" onclick="showPhase('retake')">Retake Experiments</div>
      <div class="menu-item" data-section="profile" onclick="showPhase('profile')">Profile</div>
    </aside>

    <!-- Main -->
    <main class="main">

      <!-- Updated Experiments: show labs -->
      <div id="updated" class="phase card" style="display:block;">
        <h2>Labs</h2>
        <ul class="experiment-list">
          <?php if(empty($labs)): ?>
            <li><div class="title">No labs available</div></li>
          <?php else: ?>
            <?php foreach($labs as $lab): ?>
              <li style="cursor:pointer" onclick="showLab(<?= (int)$lab['id'] ?>)">
                <div>
                  <div class="title"><?= htmlspecialchars($lab['name'] ?? '', ENT_QUOTES | ENT_HTML5) ?></div>
                  <div class="meta"><?= htmlspecialchars($lab['description'] ?? '', ENT_QUOTES | ENT_HTML5) ?></div>
                </div>
                <div><button class="btn ghost small">Open</button></div>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>

      <!-- Lab experiments (one card per lab, hidden by default) -->
      <?php foreach($labs as $lab): ?>
      <?php
         $lab_id = (int)$lab['id'];
         $lab_name = $lab['name'] ?? '';
      ?>
      <div id="lab-<?= $lab_id ?>" class="phase card" style="display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
          <div style="display:flex;gap:12px;align-items:center;">
            <button class="btn ghost small" onclick="backToLabs()">← Back</button>
            <h2 style="margin:0"><?= htmlspecialchars($lab_name, ENT_QUOTES | ENT_HTML5) ?> Experiments</h2>
          </div>
          <div style="font-size:13px;color:var(--muted)"><?= date('F j, Y') ?></div>
        </div>

        <div class="lab-experiments">
        <ul class="experiment-list">
        <?php
          $stmt = $conn->prepare("SELECT id, name, file_path FROM experiments WHERE lab = ?");
          $stmt->bind_param('s', $lab_name);
          $stmt->execute();
          $res = $stmt->get_result();
          if($res && $res->num_rows > 0){
              while($exp = $res->fetch_assoc()){
                  $exp_name = $exp['name'] ?? '';
                  $file_path = $exp['file_path'] ?? '';
                  ?>
                  <li>
                    <div>
                      <div class="title"><?= htmlspecialchars($exp_name, ENT_QUOTES | ENT_HTML5) ?></div>
                    
                    </div>
                    <div>
                      <button class="btn primary" onclick="startExperiment('<?= addslashes($file_path) ?>')">Start</button>
                    </div>
                  </li>
                  <?php
              }
          } else {
              echo "<li><div class='title'>No experiments found.</div></li>";
          }
          $stmt->close();
        ?>
        </ul>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Completed Experiments -->
      <div id="completed" class="phase card" style="display:none;">
        <h2>Completed Experiments</h2>
        <p class="meta" style="color:var(--muted)">You have not completed any experiments yet.</p>
        <ul class="experiment-list"></ul>
      </div>

      <!-- Retake Experiments -->
      <div id="retake" class="phase card" style="display:none;">
        <h2>Retake Experiments</h2>
        <p class="meta" style="color:var(--muted)">No retakes scheduled.</p>
        <ul class="experiment-list"></ul>
      </div>

      <!-- Student Profile -->
      <div id="profile" class="phase card" style="display:none;">
        <h2>Student Profile</h2>
        <div class="profile-grid" style="margin-top:12px;">
          <div class="profile-avatar"><?= strtoupper(substr($student_name,0,1)) ?></div>
          <div class="profile-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($student_name ?? '', ENT_QUOTES | ENT_HTML5) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($student_username ?? '', ENT_QUOTES | ENT_HTML5) ?></p>
            <p><strong>Year:</strong> <?= htmlspecialchars($student_year ?? '', ENT_QUOTES | ENT_HTML5) ?></p>
            <p><strong>Semester:</strong> <?= htmlspecialchars($student_semester ?? '', ENT_QUOTES | ENT_HTML5) ?></p>
          </div>
        </div>
      </div>

    </main>

  </div>
</div>

<script>
function hideAllPhases(){
    document.querySelectorAll('.phase').forEach(p=>{
        p.style.display='none';
        p.classList.remove('active');
    });
    document.querySelectorAll('.sidebar .menu-item').forEach(mi=>mi.classList.remove('active'));
}

function showPhase(id){
    hideAllPhases();
    const el = document.getElementById(id);
    if(el){ el.style.display='block'; el.classList.add('active'); }
    const items = Array.from(document.querySelectorAll('.sidebar .menu-item'));
    const map = { 'updated':0, 'completed':1, 'retake':2, 'profile':3 };
    if(map[id] !== undefined) items[map[id]].classList.add('active');
}

function showLab(labId){
    // show the lab content but KEEP the sidebar state (do not remove active class)
    document.querySelectorAll('.phase').forEach(p=>{
        p.style.display='none';
        p.classList.remove('active');
    });
    const el = document.getElementById('lab-'+labId);
    if(el){
        el.style.display='block';
        el.classList.add('active');
    }
    // keep "Updated Experiments" highlighted so left name/menu doesn't move
    document.querySelectorAll('.sidebar .menu-item').forEach(mi=>mi.classList.remove('active'));
    const updatedBtn = document.querySelector('.sidebar .menu-item[data-section="updated"]') || document.querySelector('.sidebar .menu-item');
    if(updatedBtn) updatedBtn.classList.add('active');
}

function backToLabs(){
    showPhase('updated');
}

function startExperiment(path){
    if(path && path.trim() !== ''){
        window.open(path, '_blank');
    } else {
        alert('Experiment file not found!');
    }
}

// default
showPhase('updated');
</script>

</body>
</html>
