<?php
// upload_progress.php - Teacher's Progress Report Upload Page
// Database Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educonnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
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
    
    // Get subjects, marks, and remarks from form
    $subjects = $_POST['subject'];
    $marks_obtained = $_POST['marks_obtained'];
    $total_marks = $_POST['total_marks'];
    $remarks = $_POST['remarks'];
    
    // Validate inputs
    if (empty($student_id) || empty($term) || empty($teacher_name)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Prepare statement for insertion
        $stmt = $conn->prepare("INSERT INTO progress_reports (student_id, subject, marks_obtained, total_marks, percentage, grade, remarks, term, teacher_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $all_success = true;
        
        // Loop through each subject and insert
        for ($i = 0; $i < count($subjects); $i++) {
            if (!empty($subjects[$i]) && !empty($marks_obtained[$i]) && !empty($total_marks[$i])) {
                // Calculate percentage
                $percentage = ($marks_obtained[$i] / $total_marks[$i]) * 100;
                
                // Determine grade
                $grade = '';
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Progress Report - EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 50%, #81c784 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #00b894;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #636e72;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #dfe6e9;
            padding: 12px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #00b894;
            box-shadow: 0 0 0 0.2rem rgba(0, 184, 148, 0.25);
        }
        
        .subject-row {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #00b894;
        }
        
        .btn-add-subject {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .btn-add-subject:hover {
            background: linear-gradient(135deg, #00a884, #00bdb9);
            color: white;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border: none;
            padding: 15px 50px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #00a884, #00bdb9);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
        }
        
        .btn-remove {
            background: #d63031;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1>📊 Upload Progress Report</h1>
            <p>Teacher Portal - EduConnect School Management System</p>
        </div>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✓ Success!</strong> <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>✗ Error!</strong> <?php echo $error_message; ?>
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
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . " (Class: " . $row['class'] . ", Roll: " . $row['roll_no'] . ")</option>";
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
                <h5 class="mb-3" style="color: #00b894;">Subject-wise Grades</h5>
                
                <div class="subject-row">
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
                            <button type="button" class="btn btn-remove remove-subject" style="display: none;">×</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn btn-add-subject" id="add-subject-btn">+ Add Another Subject</button>
            
            <button type="submit" name="submit_report" class="btn btn-submit">📤 Upload Progress Report</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add subject functionality
        document.getElementById('add-subject-btn').addEventListener('click', function() {
            const container = document.getElementById('subjects-container');
            const newRow = document.createElement('div');
            newRow.className = 'subject-row';
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
                        <button type="button" class="btn btn-remove remove-subject">×</button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            
            // Add remove functionality to new button
            newRow.querySelector('.remove-subject').addEventListener('click', function() {
                newRow.remove();
            });
        });
        
        // Remove subject functionality for dynamically added subjects
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-subject')) {
                e.target.closest('.subject-row').remove();
            }
        });
    </script>
</body>
</html>