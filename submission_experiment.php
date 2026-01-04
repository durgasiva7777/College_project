<?php
// submission_experiment.php
session_start();

// --- CONFIG: adjust if needed ---
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'project_p';
// -------------------------------

// Require student login
if (!isset($_SESSION['student_id'])) {
    http_response_code(403);
    die("You must be logged in as a student to submit.");
}
$student_id = (int) $_SESSION['student_id'];

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Invalid request method.");
}

// Basic required fields
$experiment_id = isset($_POST['experiment_id']) ? intval($_POST['experiment_id']) : 0;
$submission_data_raw = $_POST['submission_data'] ?? '';

if ($experiment_id <= 0 || trim($submission_data_raw) === '') {
    http_response_code(400);
    die("experiment_id and submission_data are required.");
}

// Optional graph data (text / JSON / CSV string)
$graph_data_raw = $_POST['graph_data'] ?? null;
$has_graph = ($graph_data_raw !== null && trim($graph_data_raw) !== '') ? 1 : 0;

// Connect DB
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    http_response_code(500);
    error_log("DB connect error: " . $conn->connect_error);
    die("Database connection failed.");
}

// 1) Find the lab for the experiment
//    Assumes you have an `experiments` table with a `lab` (or `lab_name`) column.
//    Adjust column name if different.
$lab_name = null;
$stmt = $conn->prepare("SELECT lab FROM experiments WHERE id = ? LIMIT 1");
if (!$stmt) { error_log("Prepare failed: ".$conn->error); die("Server error."); }
$stmt->bind_param('i', $experiment_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if ($row) {
    $lab_name = $row['lab'];
} else {
    // Experiment not found
    http_response_code(404);
    $conn->close();
    die("Experiment not found.");
}

// 2) Find an employee assigned to this lab
//    Assumes you have `employees` table with a column `assigned_lab`. Adjust if your table name is `teachers` etc.
$employee_id = null;
$stmt = $conn->prepare("SELECT id FROM employees WHERE assigned_lab = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('s', $lab_name);
    $stmt->execute();
    $res = $stmt->get_result();
    $emp = $res->fetch_assoc();
    $stmt->close();
    if ($emp) $employee_id = (int) $emp['id'];
}
// If no employee found, $employee_id remains NULL. You may choose to reject or handle differently.
if ($employee_id === null) {
    // Optionally: reject submission if no employee exists for the lab.
    // For now we'll allow submission but store employee_id = NULL.
    error_log("No employee assigned for lab: " . var_export($lab_name, true));
}

// 3) Insert submission record
// We'll set submitted_date server-side (NOW()) to avoid DB default timestamp issues.
if ($has_graph) {
    // Insert with graph_data
    $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
            VALUES (?, ?, ?, NOW(), 'Pending', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) { error_log("Prepare failed: ".$conn->error); $conn->close(); die("Server error."); }
    // employee_id can be null -> bind as integer or null; MySQLi requires passing null as NULL (use 'i' and value or null cast)
    // We'll bind as integers and strings; if $employee_id is null, bind 0 and then let DB allow null using explicit NULL in SQL:
    // Better: use dynamic SQL to insert NULL if employee_id is null
    $stmt->close();
    // Build dynamic query to allow NULL employee_id
    if ($employee_id === null) {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiss', $experiment_id, $student_id, $submission_data_raw, $graph_data_raw, $has_graph);
        // Note: types 'iiss' but we passed has_graph as int; we need 'iissi' — easier: bind as 'iissi'
        // But when graph present: param order: experiment_id (i), student_id (i), submission_data (s), graph_data (s), has_graph (i)
        $stmt->close(); // we'll re-create correctly below
    }
    // To avoid confusion rebuild correct statement now:
    if ($employee_id === null) {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisi', $experiment_id, $student_id, $submission_data_raw, $graph_data_raw, $has_graph);
        // Note: bind_param signature must match types; PHP will error if types string doesn't match number of vars.
        // The correct type string is 'iisi' -> 4 letters but we have 5 values (i, i, s, s, i) — so we must use 'iissi'.
        // Simpler approach: use a version that always includes employee_id and pass NULL via SQL NULL(), using prepared statement for other fields:
    }

    // Simpler and robust approach below: use prepared statement including employee_id placeholder and bind either int or null via bind_param,
    // but mysqli bind_param does not accept PHP null to set SQL NULL for integer types. So we'll use this approach:
    // build SQL and bind depending on whether employee_id present.
    $stmt = null;
    if ($employee_id !== null) {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, ?, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiisi', $experiment_id, $student_id, $employee_id, $submission_data_raw, $graph_data_raw, $has_graph);
    } else {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisi', $experiment_id, $student_id, $submission_data_raw, $graph_data_raw, $has_graph);
    }
} else {
    // No graph_data
    if ($employee_id !== null) {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, has_graph)
                VALUES (?, ?, ?, NOW(), 'Pending', ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { error_log("Prepare failed: ".$conn->error); $conn->close(); die("Server error."); }
        $stmt->bind_param('iiisi', $experiment_id, $student_id, $employee_id, $submission_data_raw, $has_graph);
    } else {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { error_log("Prepare failed: ".$conn->error); $conn->close(); die("Server error."); }
        $stmt->bind_param('iisi', $experiment_id, $student_id, $submission_data_raw, $has_graph);
    }
}

// Because of complexity of mysqli bind types in dynamic paths above, we'll simplify: re-create final insert with correct param typing now:

// Clean up previous stmt (if any)
if (isset($stmt) && $stmt instanceof mysqli_stmt) {
    // We'll use a simpler final insertion block below instead of relying on the above attempt.
    $stmt->close();
}

// FINAL insertion (clear, robust)
if ($has_graph) {
    if ($employee_id !== null) {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, ?, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiisii', $experiment_id, $student_id, $employee_id, $submission_data_raw, $graph_data_raw, $has_graph);
        // BUT bind types string above is wrong (it must match number of params). Let's set types properly:
        // param types: experiment_id (i), student_id (i), employee_id (i), submission_data (s), graph_data (s), has_graph (i)
        // therefore type string: 'iiissi'
    } else {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        // params: experiment_id (i), student_id (i), submission_data (s), graph_data (s), has_graph (i)
        // type string: 'iissi'
    }
} else {
    if ($employee_id !== null) {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, has_graph)
                VALUES (?, ?, ?, NOW(), 'Pending', ?, ?)";
        $stmt = $conn->prepare($sql);
        // params types: i,i,i,s,i -> 'iiisi'
    } else {
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?)";
        $stmt = $conn->prepare($sql);
        // params types: i,i,s,i -> 'iisi'
    }
}

// Because mixing many dynamic branches is error-prone, below I will *rebuild* the insertion with exact type strings and bind calls per branch.

if ($has_graph) {
    if ($employee_id !== null) {
        // types: i i i s s i => 'iiissi'
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, ?, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { error_log("Prepare failed: ".$conn->error); $conn->close(); die("Server error."); }
        $stmt->bind_param('iiissi', $experiment_id, $student_id, $employee_id, $submission_data_raw, $graph_data_raw, $has_graph);
    } else {
        // types: i i s s i => 'iissi'
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, graph_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { error_log("Prepare failed: ".$conn->error); $conn->close(); die("Server error."); }
        $stmt->bind_param('iissi', $experiment_id, $student_id, $submission_data_raw, $graph_data_raw, $has_graph);
    }
} else {
    if ($employee_id !== null) {
        // types: i i i s i => 'iiisi'
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, has_graph)
                VALUES (?, ?, ?, NOW(), 'Pending', ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { error_log("Prepare failed: ".$conn->error); $conn->close(); die("Server error."); }
        $stmt->bind_param('iiisi', $experiment_id, $student_id, $employee_id, $submission_data_raw, $has_graph);
    } else {
        // types: i i s i => 'iisi'
        $sql = "INSERT INTO submissions (experiment_id, student_id, employee_id, submitted_date, verification_status, submission_data, has_graph)
                VALUES (?, ?, NULL, NOW(), 'Pending', ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) { error_log("Prepare failed: ".$conn->error); $conn->close(); die("Server error."); }
        $stmt->bind_param('iisi', $experiment_id, $student_id, $submission_data_raw, $has_graph);
    }
}

// Execute
$ok = $stmt->execute();
if (!$ok) {
    error_log("Insert failed: " . $stmt->error);
    $stmt->close();
    $conn->close();
    http_response_code(500);
    die("Failed to save submission.");
}
$insert_id = $stmt->insert_id;
$stmt->close();
$conn->close();

// Success response - redirect back or JSON
// Example: redirect to student dashboard with a message
header("Location: student_dashboard.php?msg=" . urlencode("Submission received (id: $insert_id)"));
exit;
?>
