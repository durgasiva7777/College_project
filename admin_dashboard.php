<?php
// admin_dashboard.php
session_start();

// SESSION CHECK
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: index.php'); // redirect to login page
    exit;
}

$display_name = htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'], ENT_QUOTES);
$username = htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Panel — <?= $display_name ?></title>

  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
     :root{
      --bg: #f5f7fb;
      --panel: #ffffff;
      --muted: #6b7280;
      --accent: #0b3b66;
      --accent-2: #00C7E6;
      --primary: #375dff;
      --success: #28a745;
      --danger: #e63946;
      --card-shadow: 0 6px 18px rgba(9,30,66,0.06);
      --inset-shadow: 6px 6px 16px rgba(0,0,0,0.04);
      --radius: 12px;
      --gap: 18px;
    }

    *{box-sizing:border-box; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale}
    body{margin:0;font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Arial;color:#1f2937;background:var(--bg)}
    a{color:inherit;text-decoration:none}

    /* App grid */
    .app {
      display:flex;
      gap:var(--gap);
      padding:20px;
      min-height:100vh;
    }

    /* SIDEBAR */
    .sidebar {
      width:260px;
      background:linear-gradient(180deg,#ffffff,#f6fbff);
      border-radius:var(--radius);
      padding:18px;
      box-shadow:var(--card-shadow);
      display:flex;
      flex-direction:column;
      gap:12px;
      position:sticky;
      top:20px;
      height:calc(100vh - 40px);
      flex-shrink:0;
      transition: transform .25s ease, opacity .25s ease;
    }
    .sidebar.hidden { transform: translateX(-110%); opacity:0; pointer-events:none; }

    .brand { display:flex; gap:12px; align-items:center; padding-bottom:8px; border-bottom:1px solid #eef6ff; }
    .brand .logo { width:44px; height:44px; border-radius:10px; background:var(--panel); display:flex; align-items:center; justify-content:center; color:var(--accent); font-weight:800; box-shadow: var(--inset-shadow) }
    .brand h3 { margin:0; font-size:16px; color:var(--accent) }
    .brand small { color:var(--muted); display:block; margin-top:2px }

    .nav { display:flex; flex-direction:column; gap:8px; margin-top:8px }
    .nav .nav-item {
      display:flex; align-items:center; gap:10px; padding:10px 12px;
      border-radius:10px; cursor:pointer; font-weight:600; color: #0a2540;
      background:transparent; border: none; transition: all .15s ease;
    }
    .nav .nav-item:hover { box-shadow: inset 6px 6px 12px rgba(0,0,0,0.03), inset -6px -6px 12px #fff }
    .nav .nav-item.active { background: linear-gradient(90deg, rgba(55,93,255,0.08), rgba(55,93,255,0.03)); color:var(--accent) }

    .sidebar-footer { margin-top:auto; font-size:13px; color:var(--muted); text-align:center; padding-top:8px; border-top:1px solid #eef6ff }

    /* MAIN */
    .main-wrap { flex:1; display:flex; flex-direction:column; gap:var(--gap) }

    .topbar {
      display:flex; align-items:center; justify-content:space-between;
      padding:12px 16px; border-radius:var(--radius); background:var(--panel); box-shadow:var(--card-shadow);
      position:sticky; top:20px; z-index:10;
    }
    .topbar-left { display:flex; align-items:center; gap:12px }
    .menu-toggle { display:none; background:transparent; border:0; font-size:20px; cursor:pointer }
    .page-title { margin:0; font-size:18px; color:var(--accent) }
    .user-info { display:flex; align-items:center; gap:12px }
    .avatar { width:44px; height:44px; border-radius:10px; background:#eef6ff; color:var(--accent); display:flex; align-items:center; justify-content:center; font-weight:700 }

    /* content area */
    .content { padding: 6px 0; }
    .section { display:none }
    .section.active { display:block }

    .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:16px; margin-bottom:16px }
    .stat-card, .form-card, .table-container { background:var(--panel); border-radius:10px; padding:18px; box-shadow:var(--card-shadow) }

    .stat-card h3 { margin:0; color:var(--muted); font-weight:600; font-size:13px }
    .stat-card .value { font-size:24px; font-weight:800; margin-top:8px; color:var(--accent) }

    .form-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:12px }
    .form-group { display:flex; flex-direction:column; gap:6px }
    .form-group input, .form-group select, .form-group textarea { padding:10px; border-radius:8px; border:1px solid #e8f1fb; background:#fbfeff }
    .btn { padding:10px 14px; border-radius:8px; font-weight:700; border:0; cursor:pointer }
    .btn-primary { background:var(--primary); color:#fff }
    .btn-success { background:var(--success); color:#fff }
    .btn-danger  { background:var(--danger); color:#fff }

    .table-header { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px }
    .search-box { padding:8px 12px; border-radius:8px; border:1px solid #e6eef8; min-width:180px }

    table{ width:100%; border-collapse:collapse; font-size:14px }
    thead th { text-align:left; padding:12px; background:#fbfdff; color:var(--accent); border-bottom:1px solid #eef6fb }
    tbody td { padding:12px; border-bottom:1px solid #f1f6fb }

    .empty-state { padding:28px; text-align:center; color:var(--muted) }

    /* modal */
    .modal { display:none; position:fixed; inset:0; background:rgba(8,15,30,0.45); align-items:center; justify-content:center; z-index:9999 }
    .modal.open { display:flex }
    .modal-content { width:100%; max-width:720px; background:var(--panel); padding:20px; border-radius:10px; box-shadow:var(--card-shadow) }

    /* responsive */
    @media (max-width: 980px) {
      .sidebar { position:fixed; left:16px; top:16px; z-index:2000; height:auto; width:86%; max-width:320px; transform: translateX(-120%); }
      .sidebar.visible { transform: translateX(0); }
      .menu-toggle { display:inline-block; }
    }

    @media (max-width: 620px) {
      .grid { grid-template-columns: 1fr }
      .topbar { padding:10px }
    }
  </style>
</head>
<body>

<div class="app">
  <!-- SIDEBAR -->
  <aside class="sidebar" aria-label="Admin sidebar">
    <div class="brand">
      <div class="logo">SV</div>
      <div>
        <h3>Sri Vasavi</h3>
        <div style="font-size:12px;color:var(--muted)">Administration</div>
      </div>
    </div>

    <nav class="nav" role="navigation" aria-label="Main navigation">
      <button class="nav-item active" data-section="dashboard">📊 Dashboard</button>
      <button class="nav-item" data-section="students">🎓 Students</button>
      <button class="nav-item" data-section="teachers">👥 Teachers</button>
      <button class="nav-item" data-section="courses">📚 Courses</button>
      <button class="nav-item" data-section="timetable">🗓 Timetable</button>
      <button class="nav-item" data-section="reports">📈 Reports</button>
      <button class="nav-item" data-section="announcements">📢 Announcements</button>
    </nav>

    <div class="sidebar-footer">
      Logged in as <strong><?= $display_name ?></strong><br>
      <small style="color:var(--muted)"><?= $username ?></small>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main-wrap">
    <div class="topbar" role="banner">
      <div style="display:flex;gap:12px;align-items:center">
        <h1 id="pageTitle">Dashboard Overview</h1>
      </div>
      <div class="user-info">
        <div style="text-align:right;margin-right:8px">
          <div style="font-weight:700"><?= $display_name ?></div>
          <div style="font-size:12px;color:var(--muted)"><?= $username ?></div>
        </div>
        <div class="avatar"><?= strtoupper(substr($display_name,0,1)) ?></div>
        <form method="post" action="logout.php" style="margin-left:12px;">
          <button class="btn btn-danger" type="submit" style="margin-left:10px">Logout</button>
        </form>
      </div>
    </div>

    <main class="content" role="main">
      <!-- DASHBOARD -->
      <section id="dashboard" class="section active">
        <div class="grid">
          <div class="stat-card">
            <h3>Total Students</h3>
            <div class="value" id="totalStudents">—</div>
          </div>
          <div class="stat-card">
            <h3>Total Teachers</h3>
            <div class="value" id="totalTeachers">—</div>
          </div>
          <div class="stat-card">
            <h3>Total Courses</h3>
            <div class="value" id="totalCourses">—</div>
          </div>
        </div>

        <div style="margin-top:16px" class="form-card">
          <h2 style="margin-top:0">Recent Activity</h2>
          <p style="color:var(--muted)">Welcome to the administration panel. Use the menu to manage students, teachers, courses, and timetables.</p>
        </div>
      </section>

      <!-- STUDENTS -->
      <section id="students" class="section">
        <div class="form-card">
          <h2>Add New Student</h2>
          <form id="studentForm">
            <div class="form-grid">
              <div class="form-group"><label>Student Name *</label><input id="studentName" required></div>
              <div class="form-group"><label>Student ID *</label><input id="studentId" required></div>
              <div class="form-group"><label>Email *</label><input id="studentEmail" type="email" required></div>
              <div class="form-group"><label>Department *</label>
                <select id="studentDept">
                  <option value="">Select</option><option>CSE</option><option>ECE</option><option>EEE</option><option>MECH</option><option>CIVIL</option>
                </select>
              </div>
              <div class="form-group"><label>Section *</label>
                <select id="studentSection"><option value="">Select</option><option>A</option><option>B</option><option>C</option></select>
              </div>
            </div>
            <div style="margin-top:12px">
              <!-- TODO: wire up action to server -->
              <button class="btn btn-primary" type="submit">Add Student</button>
            </div>
          </form>
        </div>

        <div class="table-container" style="margin-top:12px;">
          <div class="table-header">
            <h2>Student List</h2>
            <input class="search-box" id="studentSearch" placeholder="Search students...">
          </div>
          <table aria-label="student-list">
            <thead><tr><th>Name</th><th>Student ID</th><th>Email</th><th>Department</th><th>Section</th><th>Actions</th></tr></thead>
            <tbody id="studentTableBody">
              <tr><td colspan="6" class="empty-state">No students added yet</td></tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- TEACHERS -->
      <section id="teachers" class="section">
        <div class="form-card">
          <h2>Add New Teacher</h2>
          <form id="teacherForm">
            <div class="form-grid">
              <div class="form-group"><label>Teacher Name *</label><input id="teacherName" required></div>
              <div class="form-group"><label>Employee ID *</label><input id="teacherId" required></div>
              <div class="form-group"><label>Email *</label><input id="teacherEmail" type="email" required></div>
              <div class="form-group"><label>Department *</label>
                <select id="teacherDept"><option value="">Select</option><option>CSE</option><option>ECE</option><option>EEE</option></select>
              </div>
            </div>
            <div style="margin-top:12px"><button class="btn btn-primary" type="submit">Add Teacher</button></div>
          </form>
        </div>

        <div class="table-container" style="margin-top:12px;">
          <div class="table-header"><h2>Teacher List</h2><input class="search-box" id="teacherSearch" placeholder="Search teachers..."></div>
          <table><thead><tr><th>Name</th><th>Employee ID</th><th>Email</th><th>Department</th><th>Actions</th></tr></thead>
          <tbody id="teacherTableBody"><tr><td colspan="5" class="empty-state">No teachers added yet</td></tr></tbody></table>
        </div>
      </section>

      <!-- COURSES -->
      <section id="courses" class="section">
        <div class="form-card">
          <h2>Add New Course</h2>
          <form id="courseForm">
            <div class="form-grid">
              <div class="form-group"><label>Course Name *</label><input id="courseName" required></div>
              <div class="form-group"><label>Course ID *</label><input id="courseId" required></div>
              <div class="form-group"><label>Department *</label><select id="courseDept"><option value="">Select</option><option>CSE</option><option>ECE</option></select></div>
              <div class="form-group"><label>Credits *</label><input id="courseCredits" type="number" min="1" max="10" required></div>
            </div>
            <div style="margin-top:12px"><button class="btn btn-primary" type="submit">Add Course</button></div>
          </form>
        </div>

        <div class="table-container" style="margin-top:12px;">
          <div class="table-header"><h2>Course List</h2><input class="search-box" id="courseSearch" placeholder="Search courses..."></div>
          <table><thead><tr><th>Course Name</th><th>Course ID</th><th>Department</th><th>Credits</th><th>Actions</th></tr></thead>
            <tbody id="courseTableBody"><tr><td colspan="5" class="empty-state">No courses added yet</td></tr></tbody>
          </table>
        </div>
      </section>

      <!-- TIMETABLE -->
      <section id="timetable" class="section">
        <div class="form-card">
          <h2>Add Timetable Entry</h2>
          <form id="timetableForm">
            <div class="form-grid">
              <div class="form-group"><label>Day</label><select id="timetableDay"><option>Monday</option><option>Tuesday</option></select></div>
              <div class="form-group"><label>Time</label><select id="timetableTime"><option>09:30-10:30</option></select></div>
              <div class="form-group"><label>Course</label><input id="timetableCourse"></div>
              <div class="form-group"><label>Teacher</label><input id="timetableTeacher"></div>
            </div>
            <div style="margin-top:12px"><button class="btn btn-primary" type="submit">Add Entry</button></div>
          </form>
        </div>

        <div class="table-container" style="margin-top:12px;">
          <div class="table-header"><h2>All Timetable Entries</h2></div>
          <table><thead><tr><th>Day</th><th>Time</th><th>Course</th><th>Teacher</th><th>Actions</th></tr></thead>
            <tbody id="timetableTableBody"><tr><td colspan="5" class="empty-state">No timetable entries yet</td></tr></tbody>
          </table>
        </div>
      </section>

      <!-- REPORTS -->
      <section id="reports" class="section">
        <div class="form-card">
          <h2>Generate Report</h2>
          <div style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
            <div style="min-width:200px">
              <label>Report Type</label>
              <select id="reportType"><option value="">Select</option><option value="students">Student List</option></select>
            </div>
            <div><button class="btn btn-success" type="button" id="generateReportBtn">Generate</button></div>
          </div>
        </div>

        <div class="table-container" id="reportContainer" style="display:none;margin-top:12px;">
          <div class="table-header"><h2 id="reportTitle">Report</h2><button class="btn btn-success" id="exportCsvBtn">Export CSV</button></div>
          <div id="reportContent">Report will show here</div>
        </div>
      </section>

      <!-- ANNOUNCEMENTS -->
      <section id="announcements" class="section">
        <div class="form-card">
          <h2>Create Announcement</h2>
          <form id="announcementForm">
            <div class="form-group"><label>Title</label><input id="announcementTitle" required></div>
            <div class="form-group"><label>Description</label><textarea id="announcementDesc" rows="4" required></textarea></div>
            <div style="margin-top:12px"><button class="btn btn-primary" type="submit">Post Announcement</button></div>
          </form>
        </div>

        <div class="table-container" style="margin-top:12px;">
          <h2 style="margin-top:0">Recent Announcements</h2>
          <div id="announcementList"><div class="empty-state">No announcements yet</div></div>
        </div>
      </section>

    </main>
  </div>
</div>

<!-- Modal (edit) -->
<div class="modal" id="editModal" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="modal-content">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h3 id="modalTitle">Edit Item</h3>
      <button class="modal-close" type="button" onclick="closeModal()">×</button>
    </div>
    <div id="editFormFields" style="margin-top:12px">
      <!-- dynamic -->
    </div>
  </div>
</div>

<script>
/* navigation */
const navButtons = document.querySelectorAll('.nav .nav-item');
const sections = document.querySelectorAll('.section');
const pageTitle = document.getElementById('pageTitle');

function setActiveSection(id){
  sections.forEach(s => s.classList.toggle('active', s.id === id));
  navButtons.forEach(b => b.classList.toggle('active', b.dataset.section === id));
  // update page title
  pageTitle.textContent = document.querySelector('.nav .nav-item[data-section="'+id+'"]').textContent + ' Overview';
}

navButtons.forEach(btn => {
  btn.addEventListener('click', () => setActiveSection(btn.dataset.section));
});

/* sample client-side behavior for forms & tables (not persistent) */
const studentTableBody = document.getElementById('studentTableBody');
const teacherTableBody = document.getElementById('teacherTableBody');
const courseTableBody  = document.getElementById('courseTableBody');
const timetableTableBody = document.getElementById('timetableTableBody');
const announcementList = document.getElementById('announcementList');

document.getElementById('studentForm').addEventListener('submit', (e)=>{
  e.preventDefault();
  const name = document.getElementById('studentName').value.trim();
  const sid  = document.getElementById('studentId').value.trim();
  const email= document.getElementById('studentEmail').value.trim();
  const dept = document.getElementById('studentDept').value;
  const sec  = document.getElementById('studentSection').value;

  if(!name || !sid){ alert('Please fill required fields'); return; }

  const tr = document.createElement('tr');
  tr.innerHTML = `<td>${escapeHtml(name)}</td><td>${escapeHtml(sid)}</td><td>${escapeHtml(email)}</td><td>${escapeHtml(dept)}</td><td>${escapeHtml(sec)}</td>
    <td><button class="btn btn-primary" onclick="editRow(this)">Edit</button></td>`;
  replaceEmptyRow(studentTableBody);
  studentTableBody.appendChild(tr);
  e.target.reset();
});

document.getElementById('teacherForm').addEventListener('submit', (e)=>{
  e.preventDefault();
  const name = document.getElementById('teacherName').value.trim();
  const id = document.getElementById('teacherId').value.trim();
  const email = document.getElementById('teacherEmail').value.trim();
  const dept = document.getElementById('teacherDept').value;
  const tr = document.createElement('tr');
  tr.innerHTML = `<td>${escapeHtml(name)}</td><td>${escapeHtml(id)}</td><td>${escapeHtml(email)}</td><td>${escapeHtml(dept)}</td><td><button class="btn btn-primary" onclick="editRow(this)">Edit</button></td>`;
  replaceEmptyRow(teacherTableBody);
  teacherTableBody.appendChild(tr);
  e.target.reset();
});

document.getElementById('courseForm').addEventListener('submit',(e)=>{
  e.preventDefault();
  const name = document.getElementById('courseName').value.trim();
  const id = document.getElementById('courseId').value.trim();
  const dept = document.getElementById('courseDept').value;
  const credits = document.getElementById('courseCredits').value;
  const tr = document.createElement('tr');
  tr.innerHTML = `<td>${escapeHtml(name)}</td><td>${escapeHtml(id)}</td><td>${escapeHtml(dept)}</td><td>${escapeHtml(credits)}</td><td><button class="btn btn-primary" onclick="editRow(this)">Edit</button></td>`;
  replaceEmptyRow(courseTableBody);
  courseTableBody.appendChild(tr);
  e.target.reset();
});

document.getElementById('timetableForm').addEventListener('submit',(e)=>{
  e.preventDefault();
  const day = document.getElementById('timetableDay').value;
  const time = document.getElementById('timetableTime').value;
  const course = document.getElementById('timetableCourse').value;
  const teacher = document.getElementById('timetableTeacher').value;
  const tr = document.createElement('tr');
  tr.innerHTML = `<td>${escapeHtml(day)}</td><td>${escapeHtml(time)}</td><td>${escapeHtml(course)}</td><td>${escapeHtml(teacher)}</td><td><button class="btn btn-primary" onclick="editRow(this)">Edit</button></td>`;
  replaceEmptyRow(timetableTableBody);
  timetableTableBody.appendChild(tr);
  e.target.reset();
});

document.getElementById('announcementForm').addEventListener('submit',(e)=>{
  e.preventDefault();
  const title = document.getElementById('announcementTitle').value.trim();
  const desc  = document.getElementById('announcementDesc').value.trim();
  if(!title || !desc) return alert('Fill title and description');
  const d = document.createElement('div');
  d.style.padding = '12px';
  d.style.borderBottom = '1px solid #eef6fb';
  d.innerHTML = `<strong>${escapeHtml(title)}</strong><p style="margin:6px 0">${escapeHtml(desc)}</p>`;
  if(announcementList.querySelector('.empty-state')) announcementList.innerHTML = '';
  announcementList.prepend(d);
  e.target.reset();
});

/* helpers */
function escapeHtml(s){ return String(s).replace(/[&<>"'\/]/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'\\/'}[c]; }); }
function replaceEmptyRow(tbody){
  const empty = tbody.querySelector('.empty-state');
  if(empty) tbody.innerHTML = '';
}

/* modal (edit) stubs */
function editRow(btn){
  const tr = btn.closest('tr');
  const cells = Array.from(tr.children).map(td => td.textContent);
  openModal('Edit Row', `<pre style="white-space:pre-wrap">${escapeHtml(cells.join(' | '))}</pre>`);
}
function openModal(title, html){
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('editFormFields').innerHTML = html;
  document.getElementById('editModal').classList.add('open');
  document.getElementById('editModal').setAttribute('aria-hidden','false');
}
function closeModal(){
  document.getElementById('editModal').classList.remove('open');
  document.getElementById('editModal').setAttribute('aria-hidden','true');
}

/* simple stat fill (demo) */
document.getElementById('totalStudents').textContent = '0';
document.getElementById('totalTeachers').textContent = '0';
document.getElementById('totalCourses').textContent = '0';

/* Reports / CSV export stubs */
document.getElementById('generateReportBtn').addEventListener('click',()=>{
  const type = document.getElementById('reportType').value;
  if(!type) return alert('Choose report type');
  document.getElementById('reportContainer').style.display = 'block';
  document.getElementById('reportTitle').textContent = 'Report: '+type;
  document.getElementById('reportContent').textContent = 'Report content (client-side demo)';
});
document.getElementById('exportCsvBtn').addEventListener('click', ()=> alert('Export CSV - implement server side.'));

/* small UI nicety: press Escape to close modal */
document.addEventListener('keydown',(e)=>{ if(e.key === 'Escape') closeModal(); });

</script>
</body>
</html>
