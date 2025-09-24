<?php
session_start();

// Check if user is logged in and has teacher role
// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
//     header('Location: login.php');
//     exit();
// }

// Configuration
$site_title = "Teacher Dashboard - EduConnect";
$current_page = 'students';

// Sample data (replace with database queries)
$teacher_info = [
    'name' => 'Sarah Johnson',
    'role' => 'Grade 5 Teacher',
    'avatar' => 'https://via.placeholder.com/40x40?text=SJ'
];

$stats = [
    'total_students' => 156,
    'present_today' => 142,
    'absent_today' => 14,
    'avg_attendance' => 91
];

// Sample student data (replace with database query)
$students = [
    [
        'id' => 'STU001',
        'name' => 'Alex Johnson',
        'email' => 'alex.johnson@email.com',
        'avatar' => 'https://via.placeholder.com/40x40?text=AJ',
        'class' => '5A',
        'section' => '5A',
        'attendance' => 95,
        'status' => 'Present'
    ],
    [
        'id' => 'STU002',
        'name' => 'Emma Smith',
        'email' => 'emma.smith@email.com',
        'avatar' => 'https://via.placeholder.com/40x40?text=ES',
        'class' => '5A',
        'section' => '5A',
        'attendance' => 88,
        'status' => 'Absent'
    ],
    [
        'id' => 'STU003',
        'name' => 'Michael Brown',
        'email' => 'michael.brown@email.com',
        'avatar' => 'https://via.placeholder.com/40x40?text=MB',
        'class' => '5B',
        'section' => '5B',
        'attendance' => 92,
        'status' => 'Present'
    ],
    [
        'id' => 'STU004',
        'name' => 'Sophia Davis',
        'email' => 'sophia.davis@email.com',
        'avatar' => 'https://via.placeholder.com/40x40?text=SD',
        'class' => '5A',
        'section' => '5A',
        'attendance' => 97,
        'status' => 'Present'
    ],
    [
        'id' => 'STU005',
        'name' => 'James Wilson',
        'email' => 'james.wilson@email.com',
        'avatar' => 'https://via.placeholder.com/40x40?text=JW',
        'class' => '5B',
        'section' => '5B',
        'attendance' => 85,
        'status' => 'Present'
    ]
];

// Pagination
$students_per_page = 10;
$total_students = count($students);
$current_page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_pages = ceil($total_students / $students_per_page);
$offset = ($current_page_num - 1) * $students_per_page;
$current_students = array_slice($students, $offset, $students_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: #4285f4;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .logo-text {
            font-weight: 600;
            font-size: 16px;
            color: #2c3e50;
        }

        .logo-subtitle {
            font-size: 12px;
            color: #6c757d;
        }

        .main-menu {
            padding: 20px 0;
            flex: 1;
        }

        .menu-title {
            padding: 0 20px 10px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 0.5px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }

        .menu-item:hover {
            background: #f8f9fa;
            color: #2c3e50;
        }

        .menu-item.active {
            background: #e3f2fd;
            color: #1976d2;
            border-right: 3px solid #1976d2;
        }

        .menu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: white;
            padding: 16px 24px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .header-left p {
            font-size: 14px;
            color: #6c757d;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .notification-icon {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .notification-icon:hover {
            background: #e9ecef;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: white;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
        }

        .user-info p {
            font-size: 12px;
            color: #6c757d;
        }

        /* Content Area */
        .content {
            padding: 24px;
            flex: 1;
        }

        /* Controls */
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        .search-box input:focus {
            outline: none;
            border-color: #4285f4;
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
        }

        .filter-controls {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .select-control {
            position: relative;
        }

        .select-control select {
            padding: 10px 36px 10px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
            appearance: none;
        }

        .select-control::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 10px;
            pointer-events: none;
        }

        .btn-primary {
            background: #4285f4;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #3367d6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
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
        .stat-icon.purple { background: #9c27b0; }

        .stat-content h3 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .stat-content p {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }

        /* Student List */
        .student-section {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e9ecef;
        }

        .section-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .section-header p {
            font-size: 14px;
            color: #6c757d;
        }

        .table-container {
            overflow-x: auto;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-table th {
            background: #f8f9fa;
            padding: 16px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e9ecef;
        }

        .student-table td {
            padding: 16px 24px;
            border-bottom: 1px solid #f8f9fa;
        }

        .student-table tr:hover {
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
            object-fit: cover;
        }

        .student-details h4 {
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .student-details p {
            font-size: 12px;
            color: #6c757d;
        }

        .student-id {
            font-size: 13px;
            font-weight: 500;
            color: #2c3e50;
        }

        .class-badge {
            padding: 4px 8px;
            background: #e3f2fd;
            color: #1976d2;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .attendance-bar {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .progress-bar {
            width: 60px;
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
        }

        .progress-fill.high { background: #34a853; }
        .progress-fill.medium { background: #fbbc04; }
        .progress-fill.low { background: #ea4335; }

        .attendance-text {
            font-size: 13px;
            font-weight: 500;
            color: #2c3e50;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-present {
            background: #e8f5e8;
            color: #2d5a2d;
        }

        .status-absent {
            background: #ffeaea;
            color: #c53030;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-sm:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }

        .btn-view {
            color: #4285f4;
            border-color: #4285f4;
        }

        .btn-report {
            color: #34a853;
            border-color: #34a853;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-top: 1px solid #e9ecef;
        }

        .showing-info {
            font-size: 14px;
            color: #6c757d;
        }

        .page-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .page-btn:hover {
            background: #f8f9fa;
        }

        .page-btn.active {
            background: #4285f4;
            color: white;
            border-color: #4285f4;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            
            .logo-text, .logo-subtitle, .menu-title {
                display: none;
            }
            
            .menu-item span {
                display: none;
            }
            
            .controls {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }
            
            .filter-controls {
                justify-content: space-between;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header-right .user-info {
                display: none;
            }
            
            .student-table {
                font-size: 12px;
            }
            
            .student-table th,
            .student-table td {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="logo-text">EduConnect</div>
                    <div class="logo-subtitle">Teacher Portal</div>
                </div>
            </div>
            
            <nav class="main-menu">
                <div class="menu-title">Main Menu</div>
                <a href="dashboard.php" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="students.php" class="menu-item active">
                    <i class="fas fa-users"></i>
                    <span>Students</span>
                </a>
                <a href="progress-reports.php" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Progress Reports</span>
                </a>
                <a href="attendance.php" class="menu-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>
                <a href="meetings.php" class="menu-item">
                    <i class="fas fa-video"></i>
                    <span>Meetings</span>
                </a>
                <a href="feedback.php" class="menu-item">
                    <i class="fas fa-comment-alt"></i>
                    <span>Feedback</span>
                </a>
                <a href="transport.php" class="menu-item">
                    <i class="fas fa-bus"></i>
                    <span>Transport</span>
                </a>
                <a href="profile.php" class="menu-item">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h1>Student Management</h1>
                    <p>Manage and monitor your students</p>
                </div>
                <div class="header-right">
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <div class="notification-badge">3</div>
                    </div>
                    <div class="user-profile">
                        <img src="<?php echo $teacher_info['avatar']; ?>" alt="Profile" class="user-avatar">
                        <div class="user-info">
                            <h4><?php echo htmlspecialchars($teacher_info['name']); ?></h4>
                            <p><?php echo htmlspecialchars($teacher_info['role']); ?></p>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <!-- Controls -->
                <div class="controls">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search students by name or ID..." id="searchInput">
                    </div>
                    <div class="filter-controls">
                        <div class="select-control">
                            <select id="classFilter">
                                <option value="">All Classes</option>
                                <option value="5A">Class 5A</option>
                                <option value="5B">Class 5B</option>
                                <option value="5C">Class 5C</option>
                            </select>
                        </div>
                        <div class="select-control">
                            <select id="sectionFilter">
                                <option value="">All Sections</option>
                                <option value="A">Section A</option>
                                <option value="B">Section B</option>
                                <option value="C">Section C</option>
                            </select>
                        </div>
                        <button class="btn-primary" onclick="addStudent()">
                            <i class="fas fa-plus"></i>
                            Add Student
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['total_students']; ?></h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['present_today']; ?></h3>
                            <p>Present Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['absent_today']; ?></h3>
                            <p>Absent Today</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['avg_attendance']; ?>%</h3>
                            <p>Avg Attendance</p>
                        </div>
                    </div>
                </div>

                <!-- Student List -->
                <div class="student-section">
                    <div class="section-header">
                        <h2>Student List</h2>
                        <p>Manage your students and track their progress</p>
                    </div>
                    
                    <div class="table-container">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>ID</th>
                                    <th>Class/Section</th>
                                    <th>Attendance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($current_students as $student): ?>
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <img src="<?php echo $student['avatar']; ?>" alt="<?php echo htmlspecialchars($student['name']); ?>" class="student-avatar">
                                            <div class="student-details">
                                                <h4><?php echo htmlspecialchars($student['name']); ?></h4>
                                                <p><?php echo htmlspecialchars($student['email']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="student-id"><?php echo htmlspecialchars($student['id']); ?></span>
                                    </td>
                                    <td>
                                        <span class="class-badge"><?php echo htmlspecialchars($student['section']); ?></span>
                                    </td>
                                    <td>
                                        <div class="attendance-bar">
                                            <div class="progress-bar">
                                                <div class="progress-fill <?php echo $student['attendance'] >= 90 ? 'high' : ($student['attendance'] >= 75 ? 'medium' : 'low'); ?>" 
                                                     style="width: <?php echo $student['attendance']; ?>%"></div>
                                            </div>
                                            <span class="attendance-text"><?php echo $student['attendance']; ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($student['status']); ?>">
                                            <?php echo htmlspecialchars($student['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-sm btn-view" onclick="viewProfile('<?php echo $student['id']; ?>')">
                                                View Profile
                                            </button>
                                            <button class="btn-sm btn-report" onclick="generateReport('<?php echo $student['id']; ?>')">
                                                Generate Report
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        <div class="showing-info">
                            Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $students_per_page, $total_students); ?> of <?php echo $total_students; ?> students
                        </div>
                        <div class="page-controls">
                            <button class="page-btn" <?php echo $current_page_num <= 1 ? 'disabled' : ''; ?> onclick="goToPage(<?php echo $current_page_num - 1; ?>)">
                                Previous
                            </button>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i <= 5 || $i > $total_pages - 5 || abs($i - $current_page_num) <= 2): ?>
                                <button class="page-btn <?php echo $i == $current_page_num ? 'active' : ''; ?>" onclick="goToPage(<?php echo $i; ?>)">
                                    <?php echo $i; ?>
                                </button>
                                <?php elseif ($i == 6 && $current_page_num > 8): ?>
                                <span>...</span>
                                <?php elseif ($i == $total_pages - 5 && $current_page_num < $total_pages - 7): ?>
                                <span>...</span>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <button class="page-btn" <?php echo $current_page_num >= $total_pages ? 'disabled' : ''; ?> onclick="goToPage(<?php echo $current_page_num + 1; ?>)">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.student-table tbody tr');
            
            rows.forEach(row => {
                const studentName = row.querySelector('.student-details h4').textContent.toLowerCase();
                const studentId = row.querySelector('.student-id').textContent.toLowerCase();
                const studentEmail = row.querySelector('.student-details p').textContent.toLowerCase();
                
                if (studentName.includes(searchTerm) || 
                    studentId.includes(searchTerm) || 
                    studentEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Filter functionality
        document.getElementById('classFilter').addEventListener('change', filterStudents);
        document.getElementById('sectionFilter').addEventListener('change', filterStudents);

        function filterStudents() {
            const classFilter = document.getElementById('classFilter').value;
            const sectionFilter = document.getElementById('sectionFilter').value;
            const rows = document.querySelectorAll('.student-table tbody tr');
            
            rows.forEach(row => {
                const classSection = row.querySelector('.class-badge').textContent;
                let showRow = true;
                
                if (classFilter && !classSection.includes(classFilter)) {
                    showRow = false;
                }
                
                if (sectionFilter && !classSection.includes(sectionFilter)) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }

        // Action functions
        function addStudent() {
            alert('Add Student functionality would open a modal or redirect to add student form');
            // In real implementation, this would open a modal or redirect
        }

        function viewProfile(studentId) {
            alert('Viewing profile for student ID: ' + studentId);
            // In real implementation: window.location.href = 'student-profile.php?id=' + studentId;
        }

        function generateReport(studentId) {
            alert('Generating report for student ID: ' + studentId);
            // In real implementation: window.open('generate-report.php?id=' + studentId, '_blank');
        }

        function goToPage(page) {
            if (page < 1 || page > <?php echo $total_pages; ?>) return;
            window.location.href = '?page=' + page;
        }

        // Sidebar collapse for mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Real-time updates (simulated)
        function updateStats() {
            // In real implementation, this would fetch updated stats via AJAX
            console.log('Stats updated');
        }

        // Auto-refresh stats every 30 seconds
        setInterval(updateStats, 30000);

        // Notification dropdown
        document.querySelector('.notification-icon').addEventListener('click', function() {
            alert('Notifications:\n• New parent message from Emma Smith\n• Attendance report ready\n• Meeting scheduled for tomorrow');
            // In real implementation, this would show a dropdown with notifications
        });

        // User profile dropdown
        document.querySelector('.user-profile').addEventListener('click', function() {
            const dropdown = `
                <div class="profile-dropdown">
                    <a href="profile.php">My Profile</a>
                    <a href="settings.php">Settings</a>
                    <a href="help.php">Help & Support</a>
                    <div class="divider"></div>
                    <a href="logout.php">Sign Out</a>
                </div>
            `;
            // In real implementation, this would show a dropdown menu
            console.log('Profile dropdown would appear here');
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
            
            // Escape to clear search
            if (e.key === 'Escape') {
                document.getElementById('searchInput').value = '';
                document.getElementById('searchInput').dispatchEvent(new Event('input'));
            }
        });

        // Table row hover effects
        document.querySelectorAll('.student-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Print functionality
        function printStudentList() {
            window.print();
        }

        // Export functionality
        function exportToCSV() {
            const rows = document.querySelectorAll('.student-table tr');
            let csv = [];
            
            rows.forEach(row => {
                const cols = row.querySelectorAll('td, th');
                const csvRow = [];
                cols.forEach(col => {
                    csvRow.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
                });
                csv.push(csvRow.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'student_list.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Initialize tooltips and other UI enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states
            document.querySelectorAll('.btn-primary, .btn-sm').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!this.classList.contains('loading')) {
                        const originalText = this.innerHTML;
                        this.classList.add('loading');
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                        
                        setTimeout(() => {
                            this.classList.remove('loading');
                            this.innerHTML = originalText;
                        }, 1000);
                    }
                });
            });
            
            // Smooth scroll for any anchor links
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
    </script>
</body>
</html>