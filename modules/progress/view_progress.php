<?php
// view_progress.php - Parent's Progress Report View Page
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

// Fetch all students for dropdown
$students_query = "SELECT id, name, class, roll_no FROM students ORDER BY class, roll_no";
$students_result = $conn->query($students_query);

// Initialize variables
$selected_student = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$selected_term = isset($_GET['term']) ? $_GET['term'] : '';
$student_info = null;
$reports = [];
$overall_percentage = 0;

// If student is selected, fetch their reports
if (!empty($selected_student)) {
    // Get student info
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $selected_student);
    $stmt->execute();
    $student_info = $stmt->fetch();
    $stmt->close();
    
    // Build query based on filter
    if (!empty($selected_term)) {
        $stmt = $conn->prepare("SELECT * FROM progress_reports WHERE student_id = ? AND term = ? ORDER BY date_created DESC");
        $stmt->bind_param("is", $selected_student, $selected_term);
    } else {
        $stmt = $conn->prepare("SELECT * FROM progress_reports WHERE student_id = ? ORDER BY date_created DESC");
        $stmt->bind_param("i", $selected_student);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total_percentage = 0;
    $count = 0;
    
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
        $total_percentage += $row['percentage'];
        $count++;
    }
    
    if ($count > 0) {
        $overall_percentage = round($total_percentage / $count, 2);
    }
    
    $stmt->close();
}

$conn->close();

// Function to get performance color
function getPerformanceColor($percentage) {
    if ($percentage >= 80) return '#00b894'; // Green
    elseif ($percentage >= 60) return '#fdcb6e'; // Yellow
    else return '#d63031'; // Red
}

// Function to get performance label
function getPerformanceLabel($percentage) {
    if ($percentage >= 80) return '🟢 Excellent';
    elseif ($percentage >= 60) return '🟡 Average';
    else return '🔴 Needs Improvement';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Progress Report - EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 50%, #81c784 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            max-width: 1000px;
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
        
        .filter-section {
            background: linear-gradient(135deg, #a8e6cf, #7fcdcd);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
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
        
        .btn-filter {
            background: #00b894;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
        }
        
        .btn-filter:hover {
            background: #00a884;
            color: white;
        }
        
        .student-card {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .student-card h3 {
            margin: 0;
            font-weight: 700;
        }
        
        .student-card p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        
        .overall-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            border: 3px solid #00b894;
        }
        
        .overall-percentage {
            font-size: 48px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .progress-bar-custom {
            height: 30px;
            border-radius: 15px;
            background: #e0e0e0;
            overflow: hidden;
            margin-top: 15px;
        }
        
        .progress-fill {
            height: 100%;
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .subject-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }
        
        .subject-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .subject-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .subject-name {
            font-size: 20px;
            font-weight: 700;
            color: #2d3436;
        }
        
        .grade-badge {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 18px;
        }
        
        .marks-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .remarks {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            font-style: italic;
            color: #636e72;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #636e72;
        }
        
        .no-data i {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .performance-indicator {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1>📈 Student Progress Reports</h1>
            <p>Parent Portal - EduConnect School Management System</p>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label" style="color: white;">Select Student</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">-- Choose Student --</option>
                            <?php 
                            if ($students_result && $students_result->num_rows > 0) {
                                while($row = $students_result->fetch_assoc()) {
                                    $selected = ($row['id'] == $selected_student) ? 'selected' : '';
                                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . " (Class: " . $row['class'] . ")</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label" style="color: white;">Filter by Term (Optional)</label>
                        <select name="term" class="form-select">
                            <option value="">-- All Terms --</option>
                            <option value="Term 1" <?php echo ($selected_term == 'Term 1') ? 'selected' : ''; ?>>Term 1</option>
                            <option value="Term 2" <?php echo ($selected_term == 'Term 2') ? 'selected' : ''; ?>>Term 2</option>
                            <option value="Midterm" <?php echo ($selected_term == 'Midterm') ? 'selected' : ''; ?>>Midterm</option>
                            <option value="Final" <?php echo ($selected_term == 'Final') ? 'selected' : ''; ?>>Final</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-filter">🔍 View Reports</button>
                    </div>
                </div>
            </form>
        </div>
        
        <?php if ($student_info): ?>
            <!-- Student Info Card -->
            <div class="student-card">
                <h3>👤 <?php echo htmlspecialchars($student_info['name']); ?></h3>
                <p>Class: <?php echo htmlspecialchars($student_info['class']); ?> | Roll No: <?php echo htmlspecialchars($student_info['roll_no']); ?></p>
            </div>
            
            <?php if (count($reports) > 0): ?>
                <!-- Overall Performance Card -->
                <div class="overall-card">
                    <h4 style="color: #2d3436; margin-bottom: 15px;">Overall Performance</h4>
                    <div class="overall-percentage" style="color: <?php echo getPerformanceColor($overall_percentage); ?>">
                        <?php echo $overall_percentage; ?>%
                    </div>
                    <span class="performance-indicator" style="background: <?php echo getPerformanceColor($overall_percentage); ?>; color: white;">
                        <?php echo getPerformanceLabel($overall_percentage); ?>
                    </span>
                    
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: <?php echo $overall_percentage; ?>%; background: <?php echo getPerformanceColor($overall_percentage); ?>;">
                            <?php echo $overall_percentage; ?>%
                        </div>
                    </div>
                </div>
                
                <!-- Subject-wise Reports -->
                <h4 style="color: #2d3436; margin-bottom: 20px;">📚 Subject-wise Performance</h4>
                
                <?php foreach ($reports as $report): ?>
                    <div class="subject-card" style="border-left: 4px solid <?php echo getPerformanceColor($report['percentage']); ?>">
                        <div class="subject-header">
                            <span class="subject-name"><?php echo htmlspecialchars($report['subject']); ?></span>
                            <span class="grade-badge"><?php echo htmlspecialchars($report['grade']); ?></span>
                        </div>
                        
                        <div class="marks-info">
                            <div>
                                <strong>Marks:</strong> <?php echo $report['marks_obtained']; ?> / <?php echo $report['total_marks']; ?>
                            </div>
                            <div>
                                <strong>Percentage:</strong> 
                                <span style="color: <?php echo getPerformanceColor($report['percentage']); ?>; font-weight: 700;">
                                    <?php echo $report['percentage']; ?>%
                                </span>
                            </div>
                        </div>
                        
                        <div class="progress-bar-custom" style="height: 8px; margin-top: 12px;">
                            <div class="progress-fill" style="width: <?php echo $report['percentage']; ?>%; background: <?php echo getPerformanceColor($report['percentage']); ?>;"></div>
                        </div>
                        
                        <?php if (!empty($report['remarks'])): ?>
                            <div class="remarks">
                                <strong>Teacher's Remarks:</strong> <?php echo htmlspecialchars($report['remarks']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div style="margin-top: 12px; font-size: 13px; color: #636e72;">
                            <strong>Term:</strong> <?php echo htmlspecialchars($report['term']); ?> | 
                            <strong>Teacher:</strong> <?php echo htmlspecialchars($report['teacher_name']); ?> | 
                            <strong>Date:</strong> <?php echo date('F j, Y', strtotime($report['date_created'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            <?php else: ?>
                <div class="no-data">
                    <div style="font-size: 64px; margin-bottom: 20px;">📋</div>
                    <h4 style="color: #636e72;">No Progress Reports Found</h4>
                    <p>No reports have been uploaded for this student yet.</p>
                </div>
            <?php endif; ?>
            
        <?php elseif (!empty($selected_student)): ?>
            <div class="no-data">
                <div style="font-size: 64px; margin-bottom: 20px;">⚠️</div>
                <h4 style="color: #636e72;">Student Not Found</h4>
                <p>Please select a valid student from the dropdown.</p>
            </div>
        <?php else: ?>
            <div class="no-data">
                <div style="font-size: 64px; margin-bottom: 20px;">🔍</div>
                <h4 style="color: #636e72;">Select a Student</h4>
                <p>Please select a student from the dropdown above to view their progress reports.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>