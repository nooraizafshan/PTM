<?php
// Include database connection
require_once __DIR__ . '/../../config/db.php';

// Get connection
$conn = dbConnect();
if (!$conn) {
    die("Database connection not available.");
}

// Get class and date from form or defaults
$class_filter = $_POST['class'] ?? '5-A';
$date = $_POST['date'] ?? date('Y-m-d');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance'])) {
    $attendance_data = $_POST['attendance'];
    $savedCount = 0;

    foreach ($attendance_data as $student_id => $status) {
        // Check if record exists
        $check = $conn->prepare("SELECT attendance_id FROM attendance WHERE student_id=? AND attendance_date=?");
        if ($check === false) die("Prepare failed (check attendance): " . $conn->error);
        $check->bind_param("is", $student_id, $date);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE attendance SET status=? WHERE student_id=? AND attendance_date=?");
            if ($stmt === false) die("Prepare failed (update attendance): " . $conn->error);
            $stmt->bind_param("sis", $status, $student_id, $date);
        } else {
            $stmt = $conn->prepare("INSERT INTO attendance (student_id, attendance_date, status) VALUES (?, ?, ?)");
            if ($stmt === false) die("Prepare failed (insert attendance): " . $conn->error);
            $stmt->bind_param("iss", $student_id, $date, $status);
        }

        if ($stmt->execute()) $savedCount++;
        $stmt->close();
        $check->close();
    }

    $success = "Attendance saved successfully for $savedCount students!";
}

// Fetch students and their attendance for the selected date
$students_stmt = $conn->prepare("
    SELECT s.student_id, s.student_name, s.class, s.roll_number, a.status AS attendance_status
    FROM students s
    LEFT JOIN attendance a ON s.student_id = a.student_id AND a.attendance_date = ?
    WHERE s.class = ?
    ORDER BY s.roll_number ASC
");
if ($students_stmt === false) die("Prepare failed (fetch students): " . $conn->error);
$students_stmt->bind_param("ss", $date, $class_filter);
$students_stmt->execute();
$result = $students_stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
$students_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mark Attendance</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body { font-family: Arial, sans-serif; background:#f2f2f2; padding:20px; margin:0; }
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.page-title { display:flex; align-items:center; gap:12px; }
.page-title i { width:48px; height:48px; background:linear-gradient(135deg,#4285f4,#34a853); border-radius:12px; display:flex; align-items:center; justify-content:center; color:white; font-size:20px; }
.page-title h2 { margin:0; font-size:24px; }
.attendance-card { background:white; border-radius:12px; padding:20px; border:1px solid #e9ecef; }
.card-header { display:flex; justify-content: space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
.filter-controls { display:flex; gap:12px; }
.form-control { padding:8px 12px; border-radius:6px; border:1px solid #ccc; font-size:14px; }
table { width:100%; border-collapse: collapse; margin-top:10px; }
th, td { padding:12px; border-bottom:1px solid #eee; text-align:left; font-size:14px; }
th { background:#f8f9fa; font-weight:600; text-transform:uppercase; font-size:12px; }
.student-info { display:flex; align-items:center; gap:12px; }
.student-avatar { width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#4285f4,#34a853); color:white; display:flex; justify-content:center; align-items:center; font-weight:600; }
.attendance-toggle { display:flex; gap:6px; flex-wrap:wrap; }
.toggle-btn { padding:6px 12px; border-radius:6px; border:1px solid #ccc; cursor:pointer; font-size:12px; transition:0.2s; background:white; }
.toggle-btn.active-present { background:#d4edda; border-color:#28a745; color:#155724; }
.toggle-btn.active-absent { background:#f8d7da; border-color:#dc3545; color:#721c24; }
.toggle-btn.active-leave { background:#fff3cd; border-color:#ffc107; color:#856404; }
.quick-actions { display:flex; justify-content:space-between; margin-top:20px; align-items:center; flex-wrap:wrap; gap:12px; }
.summary { display:flex; gap:16px; }
.summary-item { display:flex; align-items:center; gap:6px; font-size:14px; }
.summary-item.present { color:#28a745; }
.summary-item.absent { color:#dc3545; }
.summary-item.leave { color:#ffc107; }
.alert { padding:12px 16px; border-radius:6px; margin-bottom:20px; }
.alert-success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
button.btn-primary { background:#4285f4; color:white; padding:8px 16px; border:none; border-radius:6px; cursor:pointer; }
button.btn-secondary { background:#6c757d; color:white; padding:8px 16px; border:none; border-radius:6px; cursor:pointer; }
@media(max-width:768px){ .attendance-toggle { flex-direction:column; } table, th, td { font-size:12px; } }
</style>
</head>
<body>

<?php if (isset($success)): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
</div>
<?php endif; ?>

<div class="page-header">
<div class="page-title">
<i class="fas fa-calendar-check"></i>
<div>
<h2>Mark Attendance</h2>
<p>Record daily student attendance</p>
</div>
</div>
</div>

<form method="POST" id="attendanceForm">
<div class="attendance-card">
<div class="card-header">
<div class="filter-controls">
<input type="date" name="date" class="form-control" value="<?php echo $date; ?>" required>
<select name="class" class="form-control" onchange="this.form.submit()">
<option value="5-A" <?php if($class_filter=='5-A') echo 'selected'; ?>>5-A</option>
<option value="5-B" <?php if($class_filter=='5-B') echo 'selected'; ?>>5-B</option>
<option value="5-C" <?php if($class_filter=='5-C') echo 'selected'; ?>>5-C</option>
</select>
</div>
</div>

<table>
<thead>
<tr>
<th>Roll No</th>
<th>Student Name</th>
<th>Class</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php foreach($students as $student):
$status = $student['attendance_status'] ?? '';
?>
<tr>
<td><?php echo $student['roll_number']; ?></td>
<td><?php echo $student['student_name']; ?></td>
<td><?php echo $student['class']; ?></td>
<td>
<div class="attendance-toggle">
<input type="hidden" name="attendance[<?php echo $student['student_id']; ?>]" id="status_<?php echo $student['student_id']; ?>" value="<?php echo $status; ?>">
<button type="button" class="toggle-btn" data-status="present" onclick="setAttendance(this, <?php echo $student['student_id']; ?>, 'present')">Present</button>
<button type="button" class="toggle-btn" data-status="absent" onclick="setAttendance(this, <?php echo $student['student_id']; ?>, 'absent')">Absent</button>
<button type="button" class="toggle-btn" data-status="leave" onclick="setAttendance(this, <?php echo $student['student_id']; ?>, 'leave')">Leave</button>
</div>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="quick-actions">
<div class="summary">
<div class="summary-item present"><i class="fas fa-check-circle"></i> Present: <strong id="presentCount">0</strong></div>
<div class="summary-item absent"><i class="fas fa-times-circle"></i> Absent: <strong id="absentCount">0</strong></div>
<div class="summary-item leave"><i class="fas fa-exclamation-circle"></i> Leave: <strong id="leaveCount">0</strong></div>
</div>
<div style="display:flex; gap:8px; flex-wrap:wrap;">
<button type="button" class="btn-secondary" onclick="resetForm()">Reset</button>
<button type="submit" class="btn-primary">Save Attendance</button>
</div>
</div>
</div>
</form>

<script>
function setAttendance(btn, id, status){
    document.getElementById('status_'+id).value = status;
    btn.closest('tr').querySelectorAll('button').forEach(b=>{
        b.classList.remove('active-present','active-absent','active-leave');
    });
    btn.classList.add('active-'+status);
    updateSummary();
}

function updateSummary(){
    document.getElementById('presentCount').textContent=document.querySelectorAll('.active-present').length;
    document.getElementById('absentCount').textContent=document.querySelectorAll('.active-absent').length;
    document.getElementById('leaveCount').textContent=document.querySelectorAll('.active-leave').length;
}

function resetForm(){
    document.querySelectorAll('.attendance-toggle button').forEach(b=>b.classList.remove('active-present','active-absent','active-leave'));
    document.querySelectorAll('[id^="status_"]').forEach(i=>i.value='');
    updateSummary();
}

// Sync saved attendance with buttons on load
function syncButtons(){
    document.querySelectorAll('.attendance-toggle').forEach(div=>{
        const hiddenInput = div.querySelector('input[type=hidden]');
        const value = hiddenInput.value;
        if(value){
            const btn = div.querySelector(`button[data-status="${value}"]`);
            if(btn) btn.classList.add('active-'+value);
        }
    });
    updateSummary();
}

// Validate before submit
document.getElementById('attendanceForm').addEventListener('submit', function(e){
    let incomplete = false;
    document.querySelectorAll('[id^="status_"]').forEach(i=>{
        if(!i.value) incomplete = true;
    });
    if(incomplete){
        e.preventDefault();
        alert('Please mark attendance for all students!');
        return false;
    }
    return confirm('Are you sure you want to save attendance?');
});

window.onload = syncButtons;
</script>

</body>
</html>
