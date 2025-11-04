<?php
session_start();

// Example: parent email stored in session
if(!isset($_SESSION['email'])){
    die("Please login first.");
}
$parent_email = $_SESSION['email'];

// Database connection
$conn = new mysqli("localhost", "root", "", "educonnect");
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch student linked to this parent
$student_info = null;
$reports = [];
$selected_term = '';

$stmt = $conn->prepare("SELECT student_id, student_name, class, roll_number FROM students WHERE parent_email=? LIMIT 1");
$stmt->bind_param("s", $parent_email);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
    $student_info = $result->fetch_assoc();
}
$stmt->close();

// If term selected
if($student_info && isset($_GET['term']) && $_GET['term'] != ''){
    $selected_term = $_GET['term'];
    $stmt = $conn->prepare("SELECT subject, marks_obtained, total_marks, percentage, grade, remarks FROM student_report WHERE student_id=? AND term=?");
    $stmt->bind_param("is", $student_info['student_id'], $selected_term);
    $stmt->execute();
    $reports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Progress Report</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        h2 { color: #333; }
        .student-info { background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #007bff; color: #fff; }
        select, button { padding: 5px; margin-top: 10px; }
        .no-data { margin-top: 15px; padding: 10px; background: #ffebcc; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Student Progress Report</h2>

    <?php if($student_info): ?>
        <div class="student-info">
            <p><strong>Student:</strong> <?= htmlspecialchars($student_info['student_name']) ?></p>
            <p><strong>Class:</strong> <?= htmlspecialchars($student_info['class']) ?> | <strong>Roll No:</strong> <?= htmlspecialchars($student_info['roll_number']) ?></p>
        </div>

        <form method="GET">
            <select name="term" required>
                <option value="">-- Select Term --</option>
                <option value="Midterm" <?= $selected_term=='Midterm'?'selected':'' ?>>Midterm</option>
                <option value="Term 1" <?= $selected_term=='Term 1'?'selected':'' ?>>Term 1</option>
                <option value="Term 2" <?= $selected_term=='Term 2'?'selected':'' ?>>Term 2</option>
                <option value="Final" <?= $selected_term=='Final'?'selected':'' ?>>Final</option>
            </select>
            <button type="submit">View</button>
        </form>

        <?php if(!empty($reports)): ?>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Marks Obtained</th>
                    <th>Total Marks</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                    <th>Remarks</th>
                </tr>
                <?php foreach($reports as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['subject']) ?></td>
                        <td><?= $r['marks_obtained'] ?></td>
                        <td><?= $r['total_marks'] ?></td>
                        <td><?= $r['percentage'] ?>%</td>
                        <td><?= htmlspecialchars($r['grade']) ?></td>
                        <td><?= htmlspecialchars($r['remarks']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif($selected_term): ?>
            <div class="no-data">No reports found for this term.</div>
        <?php endif; ?>

    <?php else: ?>
        <div class="no-data">No student found for this parent.</div>
    <?php endif; ?>
</body>
</html>
