<?php
// modules/progress/upload_progress.php
include '../../db_connect.php'; // Adjust the path if needed

$success_message = "";
$error_message = "";

// Fetch all students for dropdown
$students_query = "SELECT id, name, class, roll_no FROM students ORDER BY class, roll_no";
$students_result = $conn->query($students_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_report'])) {
    $student_id = $_POST['student_id'];
    $term = $_POST['term'];
    $teacher_name = $_POST['teacher_name'];

    $subjects = $_POST['subject'];
    $marks_obtained = $_POST['marks_obtained'];
    $total_marks = $_POST['total_marks'];
    $remarks = $_POST['remarks'];

    if (empty($student_id) || empty($term) || empty($teacher_name)) {
        $error_message = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO progress_reports 
            (student_id, subject, marks_obtained, total_marks, percentage, grade, remarks, term, teacher_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $all_success = true;

        for ($i = 0; $i < count($subjects); $i++) {
            if (!empty($subjects[$i]) && !empty($marks_obtained[$i]) && !empty($total_marks[$i])) {
                $percentage = ($marks_obtained[$i] / $total_marks[$i]) * 100;

                if ($percentage >= 90) $grade = 'A+';
                elseif ($percentage >= 80) $grade = 'A';
                elseif ($percentage >= 70) $grade = 'B';
                elseif ($percentage >= 60) $grade = 'C';
                elseif ($percentage >= 50) $grade = 'D';
                else $grade = 'F';

                $stmt->bind_param("issddssss",
                    $student_id,
                    $subjects[$i],
                    $marks_obtained[$i],
                    $total_marks[$i],
                    $percentage,
                    $grade,
                    $remarks[$i],
                    $term,
                    $teacher_name
                );

                if (!$stmt->execute()) {
                    $all_success = false;
                    break;
                }
            }
        }

        $stmt->close();

        if ($all_success) {
            $success_message = "Progress report uploaded successfully!";
        } else {
            $error_message = "Error uploading progress report. Please try again.";
        }
    }
}

$conn->close();
?>

<!-- ✅ Content-only module (no header/sidebar/body tags) -->
<div class="dashboard-card shadow-sm bg-white rounded p-4">
    <h2 class="text-center mb-4 text-primary fw-bold">📊 Upload Progress Report</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✓ Success!</strong> <?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>✗ Error!</strong> <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Select Student *</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Choose Student --</option>
                    <?php
                    if ($students_result && $students_result->num_rows > 0) {
                        while($row = $students_result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']} (Class: {$row['class']}, Roll: {$row['roll_no']})</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Term/Semester *</label>
                <select name="term" class="form-select" required>
                    <option value="">-- Select Term --</option>
                    <option value="Term 1">Term 1</option>
                    <option value="Term 2">Term 2</option>
                    <option value="Midterm">Midterm</option>
                    <option value="Final">Final</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Teacher Name *</label>
            <input type="text" name="teacher_name" class="form-control" placeholder="Enter your name" required>
        </div>

        <hr class="my-4">

        <div id="subjects-container">
            <h5 class="mb-3 text-success">Subject-wise Grades</h5>

            <div class="subject-row bg-light p-3 rounded mb-3 border-start border-4 border-success">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject[]" class="form-control" placeholder="e.g., Mathematics" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Marks Obtained</label>
                        <input type="number" name="marks_obtained[]" class="form-control" placeholder="75" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total Marks</label>
                        <input type="number" name="total_marks[]" class="form-control" placeholder="100" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="remarks[]" class="form-control" placeholder="Good performance">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-subject" style="display:none;">×</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-success mb-3" id="add-subject-btn">+ Add Another Subject</button>

        <button type="submit" name="submit_report" class="btn btn-primary w-100">📤 Upload Progress Report</button>
    </form>
</div>

<script>
document.getElementById('add-subject-btn').addEventListener('click', function() {
    const container = document.getElementById('subjects-container');
    const newRow = document.createElement('div');
    newRow.className = 'subject-row bg-light p-3 rounded mb-3 border-start border-4 border-success';
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject[]" class="form-control" placeholder="e.g., English" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Marks Obtained</label>
                <input type="number" name="marks_obtained[]" class="form-control" placeholder="75" min="0" step="0.01" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Total Marks</label>
                <input type="number" name="total_marks[]" class="form-control" placeholder="100" min="0" step="0.01" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Remarks</label>
                <input type="text" name="remarks[]" class="form-control" placeholder="Good performance">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-subject">×</button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
});

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-subject')) {
        e.target.closest('.subject-row').remove();
    }
});
</script>
