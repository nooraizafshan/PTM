<?php
// Sample attendance data (replace with database query)
$attendance_records = [
    [
        'date' => '2025-01-10',
        'total_students' => 30,
        'present' => 28,
        'absent' => 2,
        'leave' => 0,
        'percentage' => 93.3
    ],
    [
        'date' => '2025-01-09',
        'total_students' => 30,
        'present' => 27,
        'absent' => 2,
        'leave' => 1,
        'percentage' => 90.0
    ],
    [
        'date' => '2025-01-08',
        'total_students' => 30,
        'present' => 29,
        'absent' => 1,
        'leave' => 0,
        'percentage' => 96.7
    ],
];

// Student-wise attendance
$student_attendance = [
    [
        'roll_no' => 'STU001',
        'name' => 'Ahmed Ali',
        'total_days' => 20,
        'present' => 19,
        'absent' => 1,
        'leave' => 0,
        'percentage' => 95
    ],
    [
        'roll_no' => 'STU002',
        'name' => 'Fatima Khan',
        'total_days' => 20,
        'present' => 18,
        'absent' => 2,
        'leave' => 0,
        'percentage' => 90
    ],
    [
        'roll_no' => 'STU003',
        'name' => 'Hassan Raza',
        'total_days' => 20,
        'present' => 20,
        'absent' => 0,
        'leave' => 0,
        'percentage' => 100
    ],
];
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
        background: linear-gradient(135deg, #9c27b0, #e91e63);
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }

    .stat-icon.blue { background: #4285f4; }
    .stat-icon.green { background: #34a853; }
    .stat-icon.red { background: #ea4335; }
    .stat-icon.orange { background: #fbbc04; }

    .stat-content h3 {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .stat-content p {
        font-size: 13px;
        color: #6c757d;
    }

    .filters {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filters label {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
    }

    .form-control {
        padding: 10px 16px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
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

    .btn-export {
        background: #28a745;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .report-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .card-header {
        padding: 20px 24px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table thead {
        background: #f8f9fa;
    }

    .report-table th {
        padding: 16px 24px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
    }

    .report-table td {
        padding: 16px 24px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 14px;
    }

    .report-table tbody tr:hover {
        background: #f8f9fa;
    }

    .percentage-bar {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .progress-bar {
        width: 80px;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s;
    }

    .progress-fill.high { background: #28a745; }
    .progress-fill.medium { background: #ffc107; }
    .progress-fill.low { background: #dc3545; }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-danger { background: #f8d7da; color: #721c24; }

    .tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
    }

    .tab-btn {
        padding: 12px 24px;
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: #6c757d;
    }

    .tab-btn.active {
        background: #4285f4;
        color: white;
        border-color: #4285f4;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .filters {
            flex-direction: column;
            align-items: stretch;
        }

        .filters > * {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <div class="page-title">
        <i class="fas fa-chart-bar"></i>
        <div>
            <h2>View Attendance</h2>
            <p>Attendance reports and analytics</p>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <button class="btn btn-export" onclick="exportData()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>30</h3>
            <p>Total Students</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>28</h3>
            <p>Avg. Present</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-content">
            <h3>2</h3>
            <p>Avg. Absent</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-percentage"></i>
        </div>
        <div class="stat-content">
            <h3>93.3%</h3>
            <p>Attendance Rate</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filters">
    <div>
        <label>From Date:</label>
        <input type="date" class="form-control" value="2025-01-01">
    </div>
    <div>
        <label>To Date:</label>
        <input type="date" class="form-control" value="2025-01-10">
    </div>
    <div>
        <label>Class:</label>
        <select class="form-control">
            <option>All Classes</option>
            <option>Class 5-A</option>
            <option>Class 5-B</option>
            <option>Class 5-C</option>
        </select>
    </div>
    <button class="btn btn-primary" style="margin-top: 24px;">
        <i class="fas fa-filter"></i> Apply Filter
    </button>
</div>

<!-- Tabs -->
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('daily')">
        <i class="fas fa-calendar-day"></i> Daily Report
    </button>
    <button class="tab-btn" onclick="switchTab('student')">
        <i class="fas fa-user-graduate"></i> Student-wise
    </button>
</div>

<!-- Daily Report Tab -->
<div class="tab-content active" id="daily-tab">
    <div class="report-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt"></i>
                Daily Attendance Report
            </h3>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total Students</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Leave</th>
                    <th>Attendance %</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
         <?php foreach ($attendance_records as $record): ?>
                <tr>
                    <td><strong><?php echo date('M d, Y', strtotime($record['date'])); ?></strong></td>
                    <td><?php echo $record['total_students']; ?></td>
                    <td style="color: #28a745; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> <?php echo $record['present']; ?>
                    </td>
                    <td style="color: #dc3545; font-weight: 600;">
                        <i class="fas fa-times-circle"></i> <?php echo $record['absent']; ?>
                    </td>
                    <td style="color: #ffc107; font-weight: 600;">
                        <i class="fas fa-calendar-times"></i> <?php echo $record['leave']; ?>
                    </td>
                    <td>
                        <div class="percentage-bar">
                            <div class="progress-bar">
                                <div class="progress-fill <?php 
                                    echo $record['percentage'] >= 90 ? 'high' : 
                                        ($record['percentage'] >= 75 ? 'medium' : 'low'); 
                                ?>" 
                                style="width: <?php echo $record['percentage']; ?>%"></div>
                            </div>
                            <span style="font-weight: 600;"><?php echo number_format($record['percentage'], 1); ?>%</span>
                        </div>
                    </td>
                    <td>
                        <?php if ($record['percentage'] >= 90): ?>
                            <span class="badge badge-success">Excellent</span>
                        <?php elseif ($record['percentage'] >= 75): ?>
                            <span class="badge badge-warning">Good</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Poor</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Student-wise Report Tab -->
<div class="tab-content" id="student-tab">
    <div class="report-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i>
                Student-wise Attendance Report
            </h3>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>Total Days</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Leave</th>
                    <th>Attendance %</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($student_attendance as $student): 
                    $initials = strtoupper(substr($student['name'], 0, 2));
                ?>
                <tr>
                    <td><strong><?php echo $student['roll_no']; ?></strong></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #4285f4, #34a853); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                                <?php echo $initials; ?>
                            </div>
                            <span style="font-weight: 600;"><?php echo $student['name']; ?></span>
                        </div>
                    </td>
                    <td><?php echo $student['total_days']; ?></td>
                    <td style="color: #28a745; font-weight: 600;"><?php echo $student['present']; ?></td>
                    <td style="color: #dc3545; font-weight: 600;"><?php echo $student['absent']; ?></td>
                    <td style="color: #ffc107; font-weight: 600;"><?php echo $student['leave']; ?></td>
                    <td>
                        <div class="percentage-bar">
                            <div class="progress-bar">
                                <div class="progress-fill <?php 
                                    echo $student['percentage'] >= 90 ? 'high' : 
                                        ($student['percentage'] >= 75 ? 'medium' : 'low'); 
                                ?>" 
                                style="width: <?php echo $student['percentage']; ?>%"></div>
                            </div>
                            <span style="font-weight: 600;"><?php echo $student['percentage']; ?>%</span>
                        </div>
                    </td>
                    <td>
                        <?php if ($student['percentage'] >= 90): ?>
                            <span class="badge badge-success">Excellent</span>
                        <?php elseif ($student['percentage'] >= 75): ?>
                            <span class="badge badge-warning">Average</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Low</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn" style="background: #e3f2fd; color: #1976d2; padding: 6px 12px; font-size: 12px;" 
                                onclick="viewDetails('<?php echo $student['roll_no']; ?>')">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Tab switching
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked button
    event.target.closest('.tab-btn').classList.add('active');
}

// View student details
function viewDetails(rollNo) {
    alert('Opening detailed attendance report for student: ' + rollNo);
    // In real implementation: 
    // window.location.href = 'student_attendance_detail.php?roll_no=' + rollNo;
}

// Export to Excel
function exportData() {
    // Get active tab
    const activeTab = document.querySelector('.tab-content.active');
    const table = activeTab.querySelector('table');
    
    // Convert table to CSV
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(col => {
            // Clean text content
            let text = col.textContent.trim();
            text = text.replace(/\s+/g, ' '); // Replace multiple spaces with single space
            rowData.push('"' + text + '"');
        });
        csv.push(rowData.join(','));
    });
    
    // Create blob and download
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'attendance_report_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    
    alert('Attendance report exported successfully!');
}

// Print specific tab
window.addEventListener('beforeprint', function() {
    // Hide non-active tabs before printing
    document.querySelectorAll('.tab-content:not(.active)').forEach(tab => {
        tab.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    // Restore display after printing
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = '';
    });
});

// Auto-update stats based on selected date range
function updateStats() {
    // This would typically make an AJAX call to fetch updated data
    console.log('Updating statistics...');
}

// Initialize tooltips or other UI elements
document.addEventListener('DOMContentLoaded', function() {
    console.log('Attendance view page loaded successfully');
    
    // Add smooth scroll for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Filter functionality
document.querySelector('.filters .btn-primary').addEventListener('click', function() {
    const fromDate = document.querySelector('.filters input[type="date"]:first-of-type').value;
    const toDate = document.querySelector('.filters input[type="date"]:last-of-type').value;
    const selectedClass = document.querySelector('.filters select').value;
    
    console.log('Filtering data:', { fromDate, toDate, selectedClass });
    
    // In real implementation, this would make an AJAX call to fetch filtered data
    alert(`Filtering attendance from ${fromDate} to ${toDate} for ${selectedClass}`);
    
    // Simulate loading
    const btn = this;
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        alert('Data filtered successfully!');
    }, 1500);
});

// Highlight rows with low attendance
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('#student-tab tbody tr');
    rows.forEach(row => {
        const percentage = parseInt(row.querySelector('.percentage-bar span').textContent);
        if (percentage < 75) {
            row.style.backgroundColor = '#fff5f5';
        }
    });
});
</script>