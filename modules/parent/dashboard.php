<?php
require_once __DIR__ . '/../../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Check if parent is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'parent') {
    header("Location: ../../modules/auth/login.php");
    exit();
}

// ✅ Database connection
$conn = dbConnect();
if (!$conn) {
    die("❌ Database connection not available. Please check config/db.php");
}

$parent_email = $_SESSION['email'] ?? '';
if (empty($parent_email)) {
    die("❌ Parent email not found in session. Please log in again.");
}

$today = date('Y-m-d');
$current_month = date('Y-m');

// ✅ Fetch student linked to parent
$student_info = null;
$student_id = null;

$query = "SELECT student_id, student_name, class FROM students WHERE parent_email = ? AND status='active' LIMIT 1";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("s", $parent_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $student_info = $row;
        $student_id = $row['student_id'];
    }
    $stmt->close();
}

if (!$student_info) {
    echo "<h3 style='color:red;'>⚠️ No student found associated with your account.</h3>";
    echo "<p>Please ensure your <b>parent_email</b> in the <code>students</code> table matches your login email (<b>$parent_email</b>).</p>";
    echo "<p>Contact the administrator if the issue persists.</p>";
    exit();
}

$student_name = $student_info['student_name'];

// ✅ Fetch Attendance Stats
function getCount($conn, $query) {
    $res = $conn->query($query);
    return $res ? (int)$res->fetch_assoc()[array_key_first($res->fetch_assoc())] : 0;
}

$days_present = $conn->query("
    SELECT COUNT(*) as c FROM attendance 
    WHERE student_id = '$student_id' 
      AND status='present' 
      AND DATE_FORMAT(attendance_date, '%Y-%m') = '$current_month'
")->fetch_assoc()['c'] ?? 0;

$days_absent = $conn->query("
    SELECT COUNT(*) as c FROM attendance 
    WHERE student_id = '$student_id' 
      AND status='absent' 
      AND DATE_FORMAT(attendance_date, '%Y-%m') = '$current_month'
")->fetch_assoc()['c'] ?? 0;

// ✅ Attendance Percentage
$res = $conn->query("
    SELECT 
        SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as total_present,
        COUNT(*) as total_days
    FROM attendance 
    WHERE student_id = '$student_id'
");

if ($res && $row = $res->fetch_assoc()) {
    $attendance_percentage = $row['total_days'] > 0 ? round(($row['total_present'] / $row['total_days']) * 100) : 0;
} else {
    $attendance_percentage = 0;
}

// ✅ Unread Feedback Count
$res = $conn->query("
    SELECT COUNT(*) as unread_feedback 
    FROM feedbacks 
    WHERE student_name = '" . $conn->real_escape_string($student_name) . "'
");
$unread_feedback = $res ? $res->fetch_assoc()['unread_feedback'] : 0;

// ✅ Upcoming Meetings
$res = $conn->query("
    SELECT COUNT(*) as upcoming_meetings 
    FROM ptm_meetings 
    WHERE student_id = '$student_id' 
      AND meeting_date >= '$today'
      AND status='scheduled'
");
$upcoming_meetings = $res ? $res->fetch_assoc()['upcoming_meetings'] : 0;

// ✅ Recent Feedback
$recent_feedbacks = [];
$res = $conn->query("
    SELECT subject, feedback, created_at 
    FROM feedbacks 
    WHERE student_name = '" . $conn->real_escape_string($student_name) . "'
    ORDER BY created_at DESC 
    LIMIT 3
");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $recent_feedbacks[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Parent Dashboard - EduConnect</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f2f2f2;color:#2c3e50;}
.student-banner{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:24px;border-radius:12px;margin-bottom:30px;box-shadow:0 4px 12px rgba(102,126,234,0.3);}
.student-banner h1{font-size:28px;margin:0 0 8px 0;}
.student-banner p{font-size:16px;margin:4px 0;opacity:0.95;}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:30px;}
.stat-card{background:white;padding:24px;border-radius:12px;border:1px solid #e9ecef;display:flex;align-items:center;gap:16px;transition:all 0.3s;}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.1);}
.stat-icon{width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;color:white;}
.stat-icon.blue{background:linear-gradient(135deg,#4285f4,#357ae8);}
.stat-icon.green{background:linear-gradient(135deg,#34a853,#2d9447);}
.stat-icon.red{background:linear-gradient(135deg,#ea4335,#d33426);}
.stat-icon.orange{background:linear-gradient(135deg,#fbbc04,#f9ab00);}
.stat-content h3{font-size:32px;font-weight:700;margin-bottom:4px;}
.stat-content p{font-size:14px;color:#6c757d;}
.quick-actions{background:white;padding:24px;border-radius:12px;border:1px solid #e9ecef;margin-bottom:30px;}
.quick-actions h2{font-size:18px;font-weight:600;margin-bottom:20px;}
.action-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;}
.action-btn{padding:16px 20px;background:#f8f9fa;border:1px solid #e9ecef;border-radius:10px;text-decoration:none;color:#2c3e50;display:flex;align-items:center;gap:12px;transition:all 0.2s;}
.action-btn:hover{background:#e9ecef;transform:translateX(5px);}
.action-btn i{font-size:20px;color:#4285f4;}
.recent-feedback{background:white;padding:24px;border-radius:12px;border:1px solid #e9ecef;}
.recent-feedback h2{font-size:18px;font-weight:600;margin-bottom:20px;}
.feedback-item{padding:16px;background:#f8f9fa;border-radius:8px;margin-bottom:12px;border-left:4px solid #4285f4;}
.feedback-item:last-child{margin-bottom:0;}
.feedback-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;}
.feedback-subject{font-weight:600;color:#2c3e50;}
.feedback-date{font-size:12px;color:#6c757d;}
.feedback-text{font-size:14px;color:#495057;line-height:1.5;}
.no-feedback{text-align:center;padding:30px;color:#999;}
</style>
</head>
<body>

<div class="student-banner">
    <h1><i class="fas fa-user-graduate"></i> <?= htmlspecialchars($student_info['student_name']) ?></h1>
    <p><i class="fas fa-chalkboard"></i> Class: <?= htmlspecialchars($student_info['class']) ?></p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-content">
            <h3><?= $days_present ?></h3>
            <p>Days Present (This Month)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="stat-content">
            <h3><?= $days_absent ?></h3>
            <p>Days Absent (This Month)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="stat-content">
            <h3><?= $attendance_percentage ?>%</h3>
            <p>Overall Attendance</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-content">
            <h3><?= $upcoming_meetings ?></h3>
            <p>Upcoming Meetings</p>
        </div>
    </div>
</div>

<div class="quick-actions">
    <h2>Quick Actions</h2>
    <div class="action-grid">
        <a href="parent_dashboard.php?page=view-feedback" class="action-btn">
            <i class="fas fa-comment-alt"></i>
            <span>View Feedback (<?= $unread_feedback ?>)</span>
        </a>
        <a href="parent_dashboard.php?page=attendance-report" class="action-btn">
            <i class="fas fa-calendar-check"></i>
            <span>Attendance Report</span>
        </a>
        <a href="parent_dashboard.php?page=meetings" class="action-btn">
            <i class="fas fa-video"></i>
            <span>PTM Meetings</span>
        </a>
        <a href="parent_dashboard.php?page=progress-report" class="action-btn">
            <i class="fas fa-chart-bar"></i>
            <span>Progress Report</span>
        </a>
    </div>
</div>

<div class="recent-feedback">
    <h2><i class="fas fa-bell"></i> Recent Feedback from Teacher</h2>
    <?php if (empty($recent_feedbacks)): ?>
        <div class="no-feedback">
            <i class="fas fa-inbox" style="font-size:48px;margin-bottom:10px;"></i>
            <p>No feedback available yet.</p>
        </div>
    <?php else: ?>
        <?php foreach ($recent_feedbacks as $feedback): ?>
            <div class="feedback-item">
                <div class="feedback-header">
                    <span class="feedback-subject"><?= htmlspecialchars($feedback['subject']) ?></span>
                    <span class="feedback-date"><?= date('M d, Y', strtotime($feedback['created_at'])) ?></span>
                </div>
                <div class="feedback-text">
                    <?= htmlspecialchars(substr($feedback['feedback'], 0, 120)) ?><?= strlen($feedback['feedback']) > 120 ? '...' : '' ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
