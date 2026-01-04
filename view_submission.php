<?php
// view_submission.php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'employee') {
    header('Location: index.php'); exit;
}
$employee_id = (int) $_SESSION['user_id'];

if (!isset($_GET['id'])) { die("Missing submission id."); }
$submission_id = (int) $_GET['id'];

$conn = new mysqli('localhost','root','','project_p');
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

// fetch submission and ensure it belongs to this employee
$stmt = $conn->prepare("
    SELECT s.*, e.name AS experiment_name
    FROM submissions s
    LEFT JOIN experiments e ON e.id = s.experiment_id
    WHERE s.submission_id = ? AND s.employee_id = ?
    LIMIT 1
");
$stmt->bind_param('ii', $submission_id, $employee_id);
$stmt->execute();
$res = $stmt->get_result();
$submission = $res->fetch_assoc();
$stmt->close();

if (!$submission) { $conn->close(); die("Submission not found or not assigned to you."); }

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Submission #<?= htmlspecialchars($submission_id) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f6f8fb;margin:0;padding:18px;color:#222}
.card{background:#fff;padding:18px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.06);max-width:1000px;margin:0 auto}
.label{font-weight:700;color:#444;margin-top:8px}
.btn{padding:10px 14px;border-radius:8px;border:none;cursor:pointer}
.btn.verify{background:#28a745;color:#fff}
.btn.retake{background:#d9534f;color:#fff}
.form-inline{display:inline-block;margin-right:8px}
textarea{width:100%;min-height:120px;padding:10px;border-radius:8px;border:1px solid #e6eef5}
.meta{color:#666;font-size:13px}
.link-back{display:inline-block;margin-top:12px;text-decoration:none;color:#007bff}
</style>
</head>
<body>
<div class="card">
  <h2>Submission #<?= htmlspecialchars($submission['submission_id']) ?> — Experiment <?= htmlspecialchars($submission['experiment_id']) ?></h2>
  <div class="meta">Student ID: <?= htmlspecialchars($submission['student_id']) ?> • Submitted: <?= htmlspecialchars($submission['submitted_date']) ?> • Status: <?= htmlspecialchars($submission['verification_status']) ?></div>

  <div style="margin-top:12px">
    <div class="label">Submission Data</div>
    <div style="background:#fbfdff;padding:12px;border-radius:8px;margin-top:6px;white-space:pre-wrap;"><?= nl2br(htmlspecialchars($submission['submission_data'])) ?></div>
  </div>

  <?php if (!empty($submission['has_graph']) && $submission['has_graph']): ?>
    <div style="margin-top:12px">
      <div class="label">Graph Data</div>
      <div style="background:#fbfdff;padding:12px;border-radius:8px;margin-top:6px;white-space:pre-wrap;"><?= nl2br(htmlspecialchars($submission['graph_data'])) ?></div>
    </div>
  <?php endif; ?>

  <div style="margin-top:14px">
    <form method="post" action="verify_action.php" style="display:flex;flex-direction:column;gap:10px;">
      <input type="hidden" name="submission_id" value="<?= htmlspecialchars($submission['submission_id']) ?>">
      <label class="label">Employee Comments (optional)</label>
      <textarea name="employee_comments" placeholder="Add comments for the student..."></textarea>

      <div style="display:flex;gap:10px;">
        <button class="btn verify" type="submit" name="action" value="Verified">Mark as Verified</button>
        <button class="btn retake" type="submit" name="action" value="Retake">Request Retake</button>
        <a class="link-back" href="employee_dashboard.php">&larr; Back to list</a>
      </div>
    </form>
  </div>

</div>
</body>
</html>
<?php $conn->close(); ?>
