<?php
require_once __DIR__ . '/../../config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if teacher is logged in
if (!isset($_SESSION['user_id'])) {
    die("<p style='color:red;'>Please log in to submit the report.</p>");
}

// Connect to database
$conn = dbConnect();
if (!$conn) {
    die("<p style='color:red;'>Database connection not available.</p>");
}

// Fetch all active students
$students = [];
$studentQuery = $conn->query("SELECT student_id, student_name FROM students WHERE status='active' ORDER BY student_name ASC");
if ($studentQuery) {
    while ($row = $studentQuery->fetch_assoc()) {
        $students[] = $row;
    }
}

// Define subjects
$subjects = ['Mathematics', 'Science', 'English', 'History', 'Geography', 'Computer'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id       = $_POST['student_id'];
    $subject          = $_POST['subject'];
    $marks_obtained   = $_POST['marks_obtained'];
    $total_marks      = $_POST['total_marks'];
    $percentage       = $_POST['percentage'];
    $grade            = $_POST['grade'];
    $remarks          = $_POST['remarks'] ?? '';
    $term             = $_POST['term'];
    $teacher_name     = $_SESSION['user_name'] ?? 'Teacher';

    // Get student name from DB
    $name = "";
    $studentResult = $conn->query("SELECT student_name FROM students WHERE student_id = $student_id LIMIT 1");
    if ($studentResult && $studentResult->num_rows > 0) {
        $row = $studentResult->fetch_assoc();
        $name = $row['student_name'];
    }

    // Insert into student_report
    $stmt = $conn->prepare("INSERT INTO student_report
        (student_id, name, subject, marks_obtained, total_marks, percentage, grade, remarks, term, teacher_name, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    if ($stmt) {
        $stmt->bind_param(
            "issddsssss",
            $student_id,
            $name,
            $subject,
            $marks_obtained,
            $total_marks,
            $percentage,
            $grade,
            $remarks,
            $term,
            $teacher_name
        );
        $stmt->execute();
        $stmt->close();
        echo "<p style='color:#00b894;'>Report saved successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Fetch saved reports
$reports = [];
$reportQuery = $conn->query("
    SELECT r.*, s.student_name 
    FROM student_report r 
    JOIN students s ON r.student_id = s.student_id
    ORDER BY r.created_at DESC
");
if ($reportQuery) {
    while ($row = $reportQuery->fetch_assoc()) {
        $reports[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Generate Student Report</title>
<style>
    
body {
    font-family: Arial, sans-serif;
    background: #f0f4f3;
    margin: 0;
     min-height: 100vh;
    padding: 20px;
    
    color: #333;
}

.form-container {
    
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        
}

.form-container h2 {
    background: linear-gradient(135deg, #a8e6cf, #7fcdcd, #81c784);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 26px;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
}
input, select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

button {
    background: linear-gradient(135deg, #00b894, #00cec9);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: transform 0.2s;
}
button:hover {
    transform: translateY(-2px);
}

table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th {
    background: linear-gradient(135deg, #a8e6cf, #7fcdcd, #81c784);
    color: white;
    text-transform: uppercase;
}
tr:hover {
    background: #f1f7f5;
}

.badge {
    padding: 5px 10px;
    border-radius: 12px;
    color: white;
    font-weight: 600;
}
.badge-excellent { background: #00b894; }
.badge-good { background: #00cec9; }
.badge-average { background: #81c784; }
.badge-low { background: #7fcdcd; }
</style>
</head>
<body>

<div class="form-container">
    <h2>Upload Student Report</h2>
    <form method="post">
        <label>Student Name</label>
        <select name="student_id" required>
            <option value="">-- Select Student --</option>
            <?php foreach ($students as $stu): ?>
                <option value="<?php echo $stu['student_id']; ?>">
                    <?php echo htmlspecialchars($stu['student_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Subject</label>
        <select name="subject" required>
            <option value="">-- Select Subject --</option>
            <?php foreach ($subjects as $subj): ?>
                <option value="<?php echo htmlspecialchars($subj); ?>"><?php echo htmlspecialchars($subj); ?></option>
            <?php endforeach; ?>
        </select>

        <label>Marks Obtained</label>
        <input type="number" name="marks_obtained" step="0.01" required>

        <label>Total Marks</label>
        <input type="number" name="total_marks" step="0.01" required>

        <label>Percentage</label>
        <input type="number" name="percentage" step="0.01" required>

        <label>Grade</label>
        <input type="text" name="grade" required>

        <label>Remarks</label>
        <input type="text" name="remarks">

        <label>Term</label>
        <select name="term" required>
            <option value="">-- Select Term --</option>
            <option value="Term 1">Term 1</option>
            <option value="Term 2">Term 2</option>
            <option value="Mid Term">Mid Term</option>
            <option value="Final Term">Final Term</option>
        </select>

        <button type="submit">Save Report</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Student</th>
            <th>Subject</th>
            <th>Marks Obtained</th>
            <th>Total Marks</th>
            <th>Percentage</th>
            <th>Grade</th>
            <th>Term</th>
            <th>Teacher</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($reports)): ?>
        <?php foreach ($reports as $rep): ?>
        <tr>
            <td><?php echo htmlspecialchars($rep['student_name']); ?></td>
            <td><?php echo htmlspecialchars($rep['subject']); ?></td>
            <td><?php echo $rep['marks_obtained']; ?></td>
            <td><?php echo $rep['total_marks']; ?></td>
            <td>
                <?php 
                $perc = $rep['percentage'];
                $badge = $perc >= 90 ? 'badge-excellent' : ($perc >= 75 ? 'badge-good' : ($perc >= 50 ? 'badge-average' : 'badge-low'));
                echo "<span class='badge $badge'>$perc%</span>";
                ?>
            </td>
            <td><?php echo htmlspecialchars($rep['grade']); ?></td>
            <td><?php echo htmlspecialchars($rep['term']); ?></td>
            <td><?php echo htmlspecialchars($rep['teacher_name']); ?></td>
            <td><?php echo htmlspecialchars($rep['remarks']); ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">No reports found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
