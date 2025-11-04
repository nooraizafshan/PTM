<?php
// Prevent header issues
ob_start();

// Show PHP errors during development (turn off on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database helper
require_once __DIR__ . '/../../config/db.php';
$conn = dbConnect();
if (!$conn) {
    die("Database connection not available. Please check config/db.php and DB server.");
}

// Variables for form feedback
$success = $error = "";

// Handle meeting scheduling form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parentEmail = trim($_POST['parent_name'] ?? '');
    $student = trim($_POST['student_name'] ?? '');
    $date = trim($_POST['meeting_date'] ?? '');
    $time = trim($_POST['meeting_time'] ?? '');
    $status = trim($_POST['meeting_status'] ?? 'scheduled');

    if ($parentEmail === '' || $student === '' || $date === '' || $time === '') {
        $error = "All fields are required.";
    } else {
        // ✅ Get parent name from users table
        $stmt = $conn->prepare("SELECT name FROM users WHERE email = ? AND role = 'parent' LIMIT 1");
        $stmt->bind_param("s", $parentEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $parent = $result->fetch_assoc();
        $stmt->close();

        if (!$parent) {
            $error = "Parent not found for email: " . htmlspecialchars($parentEmail);
        } else {
            $parentName = $parent['name'];
            // ✅ Insert meeting record
            $stmt = $conn->prepare("INSERT INTO ptm_meetings (parent_name, student_name, meeting_date, meeting_time, status) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $parentName, $student, $date, $time, $status);
            if ($stmt->execute()) {
                $success = "Meeting scheduled successfully!";
            } else {
                $error = "Database error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Fetch parents list
$parents = [];
$res = $conn->query("SELECT name, email FROM users WHERE role='parent' ORDER BY name ASC");
while ($row = $res->fetch_assoc()) $parents[] = $row;

// Fetch students (✅ all students now, not filtered by parent)
$students = [];
$res = $conn->query("SELECT student_name FROM students WHERE status='active' ORDER BY student_name ASC");
while ($row = $res->fetch_assoc()) $students[] = $row['student_name'];

// Fetch meetings
$meetings = [];
$res = $conn->query("SELECT * FROM ptm_meetings ORDER BY meeting_date ASC, meeting_time ASC");
while ($row = $res->fetch_assoc()) $meetings[] = $row;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Parent-Teacher Meetings</title>
<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f2f2f2;}
.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;}
.btn{padding:10px 20px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;background:#4285f4;color:#fff;transition:0.3s;}
.btn:hover{background:#2d3a8b;}
.report-card{background:white;border-radius:12px;border:1px solid #e9ecef;overflow:hidden;margin-bottom:24px;}
.card-header{padding:16px;background:#f8f9fa;border-bottom:1px solid #e9ecef;}
.card-title{font-size:18px;font-weight:600;color:#2c3e50;}
.report-table{width:100%;border-collapse:collapse;}
.report-table th, .report-table td{padding:12px;border-bottom:1px solid #f8f9fa;text-align:left;}
.report-table th{background:#f8f9fa;color:#6c757d;text-transform:uppercase;font-weight:600;}
.modal{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;}
.modal-content{background:white;padding:24px;border-radius:12px;width:90%;max-width:520px;box-shadow:0 4px 20px rgba(0,0,0,0.2);}
.close-btn{font-size:20px;cursor:pointer;color:#6c757d;float:right;}
input, select{width:100%;padding:10px 14px;border:1px solid #ccc;border-radius:8px;font-size:14px;margin-bottom:15px;box-sizing:border-box;}
.success{background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin-bottom:15px;}
.error{background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin-bottom:15px;}
.badge{padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;}
.badge-scheduled{background:#d4edda;color:#155724;}
.badge-completed{background:#cce5ff;color:#004085;}
.badge-cancelled{background:#f8d7da;color:#721c24;}
</style>
</head>
<body>
<div class="page-header">
    <h2>Parent-Teacher Meetings</h2>
    <button class="btn" onclick="document.getElementById('modal').style.display='flex'">Schedule Meeting</button>
</div>

<?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="error"><?= nl2br(htmlspecialchars($error)) ?></div><?php endif; ?>

<div class="report-card">
    <div class="card-header"><div class="card-title">All Meetings</div></div>
    <table class="report-table">
        <thead><tr><th>Parent</th><th>Student</th><th>Date</th><th>Time</th><th>Status</th></tr></thead>
        <tbody>
            <?php if ($meetings): foreach ($meetings as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['parent_name']) ?></td>
                <td><?= htmlspecialchars($m['student_name']) ?></td>
                <td><?= htmlspecialchars($m['meeting_date']) ?></td>
                <td><?= htmlspecialchars($m['meeting_time']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($m['status']) ?>"><?= ucfirst(htmlspecialchars($m['status'])) ?></span></td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" style="text-align:center;color:#888;">No meetings found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for scheduling -->
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('modal').style.display='none'">&times;</span>
        <h3 style="margin-bottom:20px;color:#2c3e50;font-size:22px;text-align:center;">Schedule Meeting</h3>
        <form method="post">
            <label>Parent</label>
            <select name="parent_name" required>
                <option value="">Select Parent</option>
                <?php foreach ($parents as $p): ?>
                    <option value="<?= htmlspecialchars($p['email']) ?>"><?= htmlspecialchars($p['name']) ?> (<?= htmlspecialchars($p['email']) ?>)</option>
                <?php endforeach; ?>
            </select>

            <label>Student</label>
            <select name="student_name" required>
                <option value="">Select Student</option>
                <?php foreach ($students as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Date</label>
            <input type="date" name="meeting_date" required>
            <label>Time</label>
            <input type="time" name="meeting_time" required>

            <label>Status</label>
            <select name="meeting_status">
                <option value="scheduled">Scheduled</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <div style="text-align:center;margin-top:15px;">
                <button type="submit" class="btn">Save</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
<?php ob_end_flush(); ?>
