<?php
require_once __DIR__ . '/../../config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if teacher is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: modules/auth/login.php");
    exit();
}

// Database connection
$conn = dbConnect();
if(!$conn) die("Database connection failed.");

$today = date('Y-m-d');

// --- Stats ---
// Total active students
$res = $conn->query("SELECT COUNT(*) as total_students FROM students WHERE status='active'");
$total_students = $res ? $res->fetch_assoc()['total_students'] : 0;

// Present today (join with active students)
$res = $conn->query("
    SELECT COUNT(a.attendance_id) as present_today 
    FROM attendance a
    INNER JOIN students s ON s.student_id = a.student_id
    WHERE a.attendance_date = '$today' AND a.status='present' AND s.status='active'
");
$present_today = $res ? $res->fetch_assoc()['present_today'] : 0;

// Upcoming meetings (from today onwards)




// Count upcoming scheduled meetings
$res_meetings = $conn->query("SELECT COUNT(*) AS upcoming FROM ptm_meetings WHERE meeting_date >= '$today'");
$upcoming_meetings = $res_meetings ? $res_meetings->fetch_assoc()['upcoming'] : 0;

// Average attendance of all active students
$res = $conn->query("
    SELECT 
        SUM(CASE WHEN a.status='present' THEN 1 ELSE 0 END) as total_present,
        COUNT(a.attendance_id) as total_records
    FROM attendance a
    INNER JOIN students s ON s.student_id = a.student_id
    WHERE s.status='active'
");
if($res && $row = $res->fetch_assoc()){
    $avg_attendance = $row['total_records'] > 0 ? round(($row['total_present'] / $row['total_records']) * 100) : 0;
} else {
    $avg_attendance = 0;
}

// Quick Actions (optional)
$res = $conn->query("
    SELECT COUNT(*) as not_marked 
    FROM students s 
    LEFT JOIN attendance a ON s.student_id = a.student_id AND a.attendance_date='$today'
    WHERE s.status='active' AND a.attendance_id IS NULL
");
$not_marked = $res ? $res->fetch_assoc()['not_marked'] : 0;
// --- Upcoming Meetings (for Quick Actions) ---
// Quick actions: Pending Feedback


// Upcoming scheduled meetings (only for active students)
// Upcoming Meetings (count all scheduled meetings from today)
// Upcoming Meetings (count all scheduled meetings from today)
// $res = $conn->query("
//     SELECT COUNT(*) AS upcoming_meetings
//     FROM ptm_meetings
//     WHERE meeting_date >= '$today'
//       AND status='scheduled'
// ");
// $upcoming_meetings = $res ? $res->fetch_assoc()['upcoming_meetings'] : 0;

// Pending Feedback (count students without feedback)
$res = $conn->query("
    SELECT COUNT(*) AS pending_feedback
    FROM students s
    LEFT JOIN feedbacks f ON s.student_name = f.student_name
    WHERE s.status='active' AND f.id IS NULL
");
$pending_feedback = $res ? $res->fetch_assoc()['pending_feedback'] : 0;






$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Dashboard - EduConnect</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f2f2f2;color:#2c3e50;}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-bottom:30px;}
.stat-card{background:white;padding:24px;border-radius:12px;border:1px solid #e9ecef;display:flex;align-items:center;gap:16px;transition:all 0.3s;}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.1);}
.stat-icon{width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;color:white;}
.stat-icon.blue{background:linear-gradient(135deg,#4285f4,#357ae8);}
.stat-icon.green{background:linear-gradient(135deg,#34a853,#2d9447);}
.stat-icon.orange{background:linear-gradient(135deg,#fbbc04,#f9ab00);}
.stat-icon.purple{background:linear-gradient(135deg,#9c27b0,#8e24aa);}
.stat-content h3{font-size:32px;font-weight:700;margin-bottom:4px;}
.stat-content p{font-size:14px;color:#6c757d;}
.quick-actions{background:white;padding:24px;border-radius:12px;border:1px solid #e9ecef;margin-bottom:30px;}
.quick-actions h2{font-size:18px;font-weight:600;margin-bottom:20px;}
.action-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;}
.action-btn{padding:16px 20px;background:#f8f9fa;border:1px solid #e9ecef;border-radius:10px;text-decoration:none;color:#2c3e50;display:flex;align-items:center;gap:12px;transition:all 0.2s;}
.action-btn:hover{background:#e9ecef;transform:translateX(5px);}
.action-btn i{font-size:20px;color:#4285f4;}
.welcome-card{background:white;padding:24px;border-radius:12px;border:1px solid #e9ecef;}
.welcome-card h2{font-size:18px;font-weight:600;margin-bottom:16px;}
.welcome-card p{color:#6c757d;line-height:1.6;}
</style>
</head>
<body>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-content">
            <h3><?= $total_students ?></h3>
            <p>Total Students</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-content">
            <h3><?= $present_today ?></h3>
            <p>Present Today</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-content">
            <h3><?= $upcoming_meetings ?></h3>
            <p>Upcoming Meetings</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-chart-line"></i></div>
        <div class="stat-content">
            <h3><?= $avg_attendance ?>%</h3>
            <p>Avg Attendance</p>
        </div>
    </div>
</div>

<div class="quick-actions">
    <h2>Quick Actions</h2>
    <div class="action-grid">
        <a href="teacher_dashboard.php?page=mark-attendance" class="action-btn">
            <i class="fas fa-calendar-check"></i>
            <span>Mark Attendance (<?= $not_marked ?>)</span>
        </a>
        <a href="teacher_dashboard.php?page=generate-report" class="action-btn">
            <i class="fas fa-chart-bar"></i>
            <span>Generate Report</span>
        </a>
        <a href="teacher_dashboard.php?page=meetings" class="action-btn">
            <i class="fas fa-video"></i>
            <span>Schedule Meeting (<?= $upcoming_meetings?>)</span>
        </a>
        <a href="teacher_dashboard.php?page=feedback" class="action-btn">
            <i class="fas fa-comment-alt"></i>
            <span>Send Feedback (<?= $pending_feedback ?>)</span>
        </a>
    </div>
</div>

<div class="welcome-card">
    <h2><i class="fas fa-info-circle" style="color:#4285f4;margin-right:8px;"></i>Welcome to EduConnect Teacher Dashboard</h2>
    <p>
        This is your central hub for managing students, attendance, progress reports, and parent communication. 
        Use the sidebar to navigate through different sections.
    </p>
</div>

</body>
</html>
