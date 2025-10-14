<?php
// Database connection (if needed)
// include '../../config/db.php';

// Sample class data (replace with database query)
$students = [
    ['id' => 1, 'roll_no' => 'STU001', 'name' => 'Ahmed Ali', 'class' => '5-A'],
    ['id' => 2, 'roll_no' => 'STU002', 'name' => 'Fatima Khan', 'class' => '5-A'],
    ['id' => 3, 'roll_no' => 'STU003', 'name' => 'Hassan Raza', 'class' => '5-A'],
    ['id' => 4, 'roll_no' => 'STU004', 'name' => 'Ayesha Malik', 'class' => '5-A'],
    ['id' => 5, 'roll_no' => 'STU005', 'name' => 'Bilal Ahmed', 'class' => '5-A'],
    ['id' => 6, 'roll_no' => 'STU006', 'name' => 'Zainab Hassan', 'class' => '5-A'],
    ['id' => 7, 'roll_no' => 'STU007', 'name' => 'Usman Khalid', 'class' => '5-A'],
    ['id' => 8, 'roll_no' => 'STU008', 'name' => 'Maryam Ali', 'class' => '5-A'],
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process attendance data
    $attendance_data = $_POST['attendance'] ?? [];
    $date = $_POST['date'] ?? date('Y-m-d');
    
    // Here you would save to database
    // Example: INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)
    
    $success = "Attendance marked successfully for " . count($attendance_data) . " students!";
}
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e9ecef;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #4285f4, #34a853);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .page-title h2 {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .page-title p {
        font-size: 14px;
        color: #6c757d;
    }

    .attendance-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .card-header {
        padding: 20px 24px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #4285f4;
    }

    .filter-controls {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .form-control {
        padding: 10px 16px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #4285f4;
        box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4285f4, #34a853);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attendance-table thead {
        background: #f8f9fa;
    }

    .attendance-table th {
        padding: 16px 24px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
    }

    .attendance-table td {
        padding: 16px 24px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 14px;
        color: #2c3e50;
    }

    .attendance-table tbody tr:hover {
        background: #f8f9fa;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4285f4, #34a853);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .student-details h4 {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 2px;
    }

    .student-details p {
        font-size: 12px;
        color: #6c757d;
    }

    .attendance-toggle {
        display: flex;
        gap: 8px;
    }

    .toggle-btn {
        width: 80px;
        padding: 8px 16px;
        border: 2px solid #e9ecef;
        background: white;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .toggle-btn:hover {
        border-color: #dee2e6;
        background: #f8f9fa;
    }

    .toggle-btn.active-present {
        background: #d4edda;
        border-color: #28a745;
        color: #155724;
    }

    .toggle-btn.active-absent {
        background: #f8d7da;
        border-color: #dc3545;
        color: #721c24;
    }

    .toggle-btn.active-leave {
        background: #fff3cd;
        border-color: #ffc107;
        color: #856404;
    }

    .quick-actions {
        padding: 20px 24px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary {
        display: flex;
        gap: 24px;
        font-size: 14px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .summary-item i {
        font-size: 16px;
    }

    .summary-item.present { color: #28a745; }
    .summary-item.absent { color: #dc3545; }
    .summary-item.leave { color: #ffc107; }

    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-controls {
            width: 100%;
            flex-direction: column;
        }

        .form-control {
            width: 100%;
        }

        .attendance-toggle {
            flex-direction: column;
        }

        .toggle-btn {
            width: 100%;
        }
    }
</style>

<?php if (isset($success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo $success; ?></span>
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
            <h3 class="card-title">
                <i class="fas fa-users"></i>
                Class 5-A Students
            </h3>
            <div class="filter-controls">
                <input type="date" 
                       name="date" 
                       class="form-control" 
                       value="<?php echo date('Y-m-d'); ?>"
                       required>
                <select class="form-control" name="class">
                    <option value="5-A">Class 5-A</option>
                    <option value="5-B">Class 5-B</option>
                    <option value="5-C">Class 5-C</option>
                </select>
            </div>
        </div>

        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th style="text-align: center;">Attendance Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): 
                    $initials = strtoupper(substr($student['name'], 0, 2));
                ?>
                <tr>
                    <td><strong><?php echo $student['roll_no']; ?></strong></td>
                    <td>
                        <div class="student-info">
                            <div class="student-avatar"><?php echo $initials; ?></div>
                            <div class="student-details">
                                <h4><?php echo $student['name']; ?></h4>
                                <p>Student ID: <?php echo $student['roll_no']; ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?php echo $student['class']; ?></td>
                    <td>
                        <div class="attendance-toggle">
                            <input type="hidden" name="student_ids[]" value="<?php echo $student['id']; ?>">
                            <button type="button" 
                                    class="toggle-btn" 
                                    data-status="present"
                                    onclick="setAttendance(this, <?php echo $student['id']; ?>, 'present')">
                                <i class="fas fa-check"></i> Present
                            </button>
                            <button type="button" 
                                    class="toggle-btn" 
                                    data-status="absent"
                                    onclick="setAttendance(this, <?php echo $student['id']; ?>, 'absent')">
                                <i class="fas fa-times"></i> Absent
                            </button>
                            <button type="button" 
                                    class="toggle-btn" 
                                    data-status="leave"
                                    onclick="setAttendance(this, <?php echo $student['id']; ?>, 'leave')">
                                <i class="fas fa-calendar-times"></i> Leave
                            </button>
                            <input type="hidden" 
                                   name="attendance[<?php echo $student['id']; ?>]" 
                                   id="status_<?php echo $student['id']; ?>" 
                                   value="">
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="quick-actions">
            <div class="summary">
                <div class="summary-item present">
                    <i class="fas fa-check-circle"></i>
                    <span>Present: <strong id="presentCount">0</strong></span>
                </div>
                <div class="summary-item absent">
                    <i class="fas fa-times-circle"></i>
                    <span>Absent: <strong id="absentCount">0</strong></span>
                </div>
                <div class="summary-item leave">
                    <i class="fas fa-calendar-times"></i>
                    <span>Leave: <strong id="leaveCount">0</strong></span>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Attendance
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function setAttendance(button, studentId, status) {
    // Remove active class from all buttons in the row
    const row = button.closest('tr');
    const buttons = row.querySelectorAll('.toggle-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active-present', 'active-absent', 'active-leave');
    });
    
    // Add active class to clicked button
    button.classList.add('active-' + status);
    
    // Set hidden input value
    document.getElementById('status_' + studentId).value = status;
    
    // Update summary counts
    updateSummary();
}

function updateSummary() {
    const presentCount = document.querySelectorAll('.active-present').length;
    const absentCount = document.querySelectorAll('.active-absent').length;
    const leaveCount = document.querySelectorAll('.active-leave').length;
    
    document.getElementById('presentCount').textContent = presentCount;
    document.getElementById('absentCount').textContent = absentCount;
    document.getElementById('leaveCount').textContent = leaveCount;
}

function resetForm() {
    const buttons = document.querySelectorAll('.toggle-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active-present', 'active-absent', 'active-leave');
    });
    
    const hiddenInputs = document.querySelectorAll('[id^="status_"]');
    hiddenInputs.forEach(input => {
        input.value = '';
    });
    
    updateSummary();
}

// Form validation
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    const attendanceInputs = document.querySelectorAll('[id^="status_"]');
    let allMarked = true;
    
    attendanceInputs.forEach(input => {
        if (!input.value) {
            allMarked = false;
        }
    });
    
    if (!allMarked) {
        e.preventDefault();
        alert('Please mark attendance for all students before submitting!');
        return false;
    }
    
    return confirm('Are you sure you want to save this attendance record?');
});

// Auto-mark all as present (optional quick action)
function markAllPresent() {
    const buttons = document.querySelectorAll('.toggle-btn[data-status="present"]');
    buttons.forEach(btn => btn.click());
}
</script>