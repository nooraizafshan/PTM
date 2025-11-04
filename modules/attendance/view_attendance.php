<?php
require_once __DIR__ . '/../../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure parent is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../modules/auth/login.php");
    exit();
}

$conn = dbConnect();
if (!$conn) {
    die("Database connection failed. Please check your config/db.php file.");
}

// ✅ Get logged-in parent's email
$parent_email = trim($_SESSION['email']);

// ✅ Fetch active child of the parent
$stmt = $conn->prepare("
    SELECT student_id, student_name, class, roll_number
    FROM students
    WHERE TRIM(LOWER(parent_email)) = LOWER(?) AND status = 'active'
    LIMIT 1
");
$stmt->bind_param("s", $parent_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No active child found for this parent.");
}

$child = $result->fetch_assoc();
$student_id = $child['student_id'];

// ✅ Fetch attendance for this child
$att_stmt = $conn->prepare("
    SELECT attendance_date, status, remarks
    FROM attendance
    WHERE student_id = ?
    ORDER BY attendance_date DESC
");
$att_stmt->bind_param("i", $student_id);
$att_stmt->execute();
$att_result = $att_stmt->get_result();

// Collect attendance data
$attendance_records = [];
while ($row = $att_result->fetch_assoc()) {
    $attendance_records[] = $row;
}

$att_stmt->close();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Attendance - <?php echo htmlspecialchars($child['student_name']); ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body { font-family: Arial,sans-serif; background:#f5f6fa; padding:20px; }
.container { max-width:800px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08);}
h1 { text-align:center; color:#3a4ca8; margin-bottom:20px; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
th { background:#3a4ca8; color:white; }
tr:nth-child(even) { background:#f9f9f9; }
tr:hover { background:#eef; }
.status-present { color:#28a745; font-weight:600; }
.status-absent { color:#dc3545; font-weight:600; }
.status-late { color:#ffc107; font-weight:600; }
.status-excused { color:#17a2b8; font-weight:600; }
</style>
</head>
<body>

<div class="container">
    <h1>Attendance for <?php echo htmlspecialchars($child['student_name']); ?> (<?php echo htmlspecialchars($child['class']); ?>)</h1>

    <?php if(count($attendance_records) === 0): ?>
        <p>No attendance records found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($attendance_records as $record): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($record['attendance_date'])); ?></td>
                    <td class="status-<?php echo strtolower($record['status']); ?>">
                        <i class="fas <?php 
                            echo $record['status'] === 'present' ? 'fa-check-circle' :
                                 ($record['status'] === 'absent' ? 'fa-times-circle' :
                                 ($record['status'] === 'late' ? 'fa-clock' : 'fa-info-circle'));
                        ?>"></i> <?php echo ucfirst($record['status']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($record['remarks']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
