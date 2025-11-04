<?php
// modules/progress/generate_progress.php

// ✅ Include database config file
// require_once '../../config/db.php';
require_once __DIR__ . '/../../config/db.php';

// ✅ Get database connection using your function
$conn = dbConnect();

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

// ✅ DON'T close connection (file is included in dashboard)
// $conn->close();
?>

<style>
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 30px;
        margin-bottom: 20px;
    }

    .dashboard-card h2 {
        color: #1976d2;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 30px;
        text-align: center;
        border-bottom: 3px solid #1976d2;
        padding-bottom: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 14px;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #dce4ec;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        outline: none;
    }

    .subject-row {
        background: #f8fafb;
        border-left: 4px solid #34a853 !important;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .subject-row:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1976d2, #1565c0);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1565c0, #0d47a1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #34a853, #2e7d32);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 168, 83, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        width: 35px;
        height: 35px;
        padding: 0;
        font-size: 20px;
        line-height: 1;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        transform: scale(1.1);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .btn-close {
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
        opacity: 0.5;
        transition: opacity 0.3s;
    }

    .btn-close:hover {
        opacity: 1;
    }

    hr {
        border: none;
        border-top: 2px solid #e9ecef;
        margin: 30px 0;
    }

    #subjects-container h5 {
        color: #34a853;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 20px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-6 {
        padding: 0 10px;
        margin-bottom: 15px;
    }

    .col-md-1 { flex: 0 0 8.33%; max-width: 8.33%; }
    .col-md-2 { flex: 0 0 16.66%; max-width: 16.66%; }
    .col-md-3 { flex: 0 0 25%; max-width: 25%; }
    .col-md-4 { flex: 0 0 33.33%; max-width: 33.33%; }
    .col-md-6 { flex: 0 0 50%; max-width: 50%; }

    .d-flex { display: flex; }
    .align-items-end { align-items: flex-end; }
    .w-100 { width: 100%; }
    .mb-3 { margin-bottom: 20px; }
    .mb-4 { margin-bottom: 30px; }
    .my-4 { margin: 30px 0; }
    .p-3 { padding: 20px; }
    .p-4 { padding: 30px; }
    .text-center { text-align: center; }
    .text-primary { color: #1976d2; }
    .text-success { color: #34a853; }
    .fw-bold { font-weight: 700; }
    .shadow-sm { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .bg-white { background: white; }
    .bg-light { background: #f8fafb; }
    .rounded { border-radius: 12px; }
    .border-start { border-left-style: solid; }
    .border-4 { border-left-width: 4px; }
    .border-success { border-left-color: #34a853; }

    @media (max-width: 768px) {
        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .dashboard-card {
            padding: 20px;
        }

        .dashboard-card h2 {
            font-size: 22px;
        }

        .btn {
            padding: 8px 15px;
            font-size: 13px;
        }
    }
</style>

<!-- ✅ Content-only module (no header/sidebar/body tags) -->
<div class="dashboard-card shadow-sm bg-white rounded p-4">
    <h2 class="text-center mb-4 text-primary fw-bold">📊 Upload Progress Report</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success" role="alert">
            <strong>✓ Success!</strong> <?= $success_message ?>
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <strong>✗ Error!</strong> <?= $error_message ?>
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">&times;</button>
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