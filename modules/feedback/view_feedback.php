<?php
require_once __DIR__ . '/../../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure parent is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header("Location: ../../modules/auth/login.php");
    exit();
}

// ✅ Database connection
$conn = dbConnect();
if (!$conn) {
    die("Database connection not available. Please check config/db.php");
}

$parent_email = $_SESSION['email'] ?? '';
if (empty($parent_email)) {
    die("Parent email not found in session. Please log in again.");
}

// ✅ Fetch student linked to parent
$student_name = '';
$stmt = $conn->prepare("SELECT student_name FROM students WHERE parent_email = ? AND status='active' LIMIT 1");
if ($stmt) {
    $stmt->bind_param("s", $parent_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $student_name = $row['student_name'];
    }
    $stmt->close();
}

if (empty($student_name)) {
    echo "<h3 style='color:red;'>⚠️ No active student found for your account.</h3>";
    echo "<p>Please contact the school administrator.</p>";
    exit();
}

// ✅ Fetch all feedback for this student
$feedbacks = [];
$stmt = $conn->prepare("SELECT subject, feedback, created_at FROM feedbacks WHERE student_name = ? ORDER BY created_at DESC");
if ($stmt) {
    $stmt->bind_param("s", $student_name);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Feedback</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;margin:0;padding:20px;background:#f2f2f2;color:#2c3e50;}
.container{max-width:900px;margin:auto;background:#fff;padding:30px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;}
.header h1{font-size:26px;color:#3a4ca8;margin:0;}
.logout-btn{background:#dc3545;color:white;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;text-decoration:none;}
.logout-btn:hover{background:#c82333;}
.student-info{background:#e8eaf6;padding:15px;border-radius:8px;margin-bottom:25px;color:#3a4ca8;}
.feedback-card{background:#fafafa;border:1px solid #e0e0e0;border-radius:10px;padding:18px;margin-bottom:15px;transition:all 0.2s;}
.feedback-card:hover{box-shadow:0 2px 8px rgba(0,0,0,0.1);}
.feedback-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;}
.subject{background:#3a4ca8;color:white;padding:6px 14px;border-radius:20px;font-size:14px;}
.date{color:#888;font-size:13px;}
.feedback-text{color:#333;line-height:1.6;margin-top:10px;font-size:15px;}
.no-feedback{text-align:center;color:#999;padding:40px;}
.no-feedback i{font-size:50px;margin-bottom:10px;color:#ccc;}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-comment-dots"></i> Student Feedback</h1>
       
    </div>

   

    <?php if (empty($feedbacks)): ?>
        <div class="no-feedback">
            <i class="fas fa-inbox"></i>
            <p>No feedback available yet.</p>
        </div>
    <?php else: ?>
        <?php foreach ($feedbacks as $fb): ?>
            <div class="feedback-card">
                <div class="feedback-header">
                    <span class="subject"><?= htmlspecialchars($fb['subject']) ?></span>
                    <span class="date"><?= date('M d, Y', strtotime($fb['created_at'])) ?></span>
                </div>
                <div class="feedback-text">
                    <?= nl2br(htmlspecialchars($fb['feedback'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
