<?php
session_start();

// Check if user is logged in and has parent role
// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'parent') {
//     header('Location: login.php');
//     exit();
// }

// Configuration
$site_title = "Child Profile - EduConnect";
$current_page = 'child-profile';

// Sample data (replace with database queries)
$parent_info = [
    'name' => 'Ahmad Ali',
    'role' => 'Parent',
    'avatar' => 'https://via.placeholder.com/40x40?text=AA'
];

// Detailed child information
$child_info = [
    'name' => 'Fatima Ahmad',
    'id' => 'STU001',
    'class' => '5A',
    'section' => 'A',
    'roll_number' => '15',
    'avatar' => 'https://via.placeholder.com/120x120?text=FA',
    'teacher' => 'Sarah Johnson',
    'admission_date' => '2019-04-15',
    'date_of_birth' => '2014-08-12',
    'gender' => 'Female',
    'blood_group' => 'B+',
    'address' => '123 Model Town, Lahore',
    'emergency_contact' => '+92-300-1234567',
    'medical_conditions' => 'None',
    'allergies' => 'None known'
];

// Academic performance data
$academic_performance = [
    [
        'subject' => 'Mathematics',
        'current_grade' => 'A',
        'percentage' => 92,
        'teacher_remarks' => 'Excellent problem-solving skills',
        'last_test' => 89,
        'assignments_completed' => 15,
        'total_assignments' => 16
    ],
    [
        'subject' => 'English',
        'current_grade' => 'B+',
        'percentage' => 88,
        'teacher_remarks' => 'Good writing skills, needs work on grammar',
        'last_test' => 85,
        'assignments_completed' => 14,
        'total_assignments' => 15
    ],
    [
        'subject' => 'Science',
        'current_grade' => 'A+',
        'percentage' => 95,
        'teacher_remarks' => 'Outstanding curiosity and understanding',
        'last_test' => 94,
        'assignments_completed' => 18,
        'total_assignments' => 18
    ],
    [
        'subject' => 'Urdu',
        'current_grade' => 'A',
        'percentage' => 90,
        'teacher_remarks' => 'Very good comprehension and vocabulary',
        'last_test' => 88,
        'assignments_completed' => 13,
        'total_assignments' => 14
    ],
    [
        'subject' => 'Social Studies',
        'current_grade' => 'B+',
        'percentage' => 85,
        'teacher_remarks' => 'Shows interest in current events',
        'last_test' => 82,
        'assignments_completed' => 12,
        'total_assignments' => 13
    ]
];

// Attendance data
$attendance_stats = [
    'total_days' => 150,
    'present_days' => 142,
    'absent_days' => 8,
    'late_days' => 3,
    'percentage' => 95,
    'monthly_attendance' => [
        'January' => 95,
        'February' => 92,
        'March' => 98,
        'April' => 96,
        'May' => 94
    ]
];

// Behavior and activities
$behavior_activities = [
    'behavior_score' => 4.5,
    'leadership_activities' => ['Class Monitor', 'Science Club Member'],
    'sports_activities' => ['Basketball', 'Swimming'],
    'achievements' => [
        '1st Place - Math Olympiad 2024',
        'Best Student Award - February 2024',
        'Science Fair Winner - 2023'
    ],
    'disciplinary_actions' => []
];

// Recent feedback from teachers
$teacher_feedback = [
    [
        'date' => '2024-01-18',
        'teacher' => 'Sarah Johnson',
        'subject' => 'Mathematics',
        'type' => 'Positive',
        'comment' => 'Fatima solved a complex problem in a creative way. Excellent analytical thinking!'
    ],
    [
        'date' => '2024-01-15',
        'teacher' => 'Mike Wilson',
        'subject' => 'Science',
        'type' => 'Positive',
        'comment' => 'Great participation in the experiment. Shows genuine curiosity about scientific concepts.'
    ],
    [
        'date' => '2024-01-12',
        'teacher' => 'Sarah Johnson',
        'subject' => 'General',
        'type' => 'Suggestion',
        'comment' => 'Could improve time management during group activities.'
    ]
];

// Health and medical information
$health_info = [
    'height' => '4\'8"',
    'weight' => '35 kg',
    'bmi' => 'Normal',
    'vision' => '20/20',
    'vaccinations' => 'Up to date',
    'last_checkup' => '2024-01-10',
    'fitness_level' => 'Good',
    'dietary_restrictions' => 'None'
];
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

        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 32px;
            color: white;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .child-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }

        .profile-details h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .profile-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            opacity: 0.9;
        }

        .meta-item i {
            width: 16px;
        }

        .profile-actions {
            margin-left: auto;
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        /* Profile Content Grid */
        .profile-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        /* Card Styles */
        .profile-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-header .card-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
        }

        .card-icon.blue { background: #4285f4; }
        .card-icon.green { background: #34a853; }
        .card-icon.purple { background: #9c27b0; }
        .card-icon.orange { background: #ff9800; }
        .card-icon.red { background: #ea4335; }

        .card-body {
            padding: 24px;
        }

        /* Academic Performance */
        .subject-item {
            padding: 16px 0;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .subject-item:last-child {
            border-bottom: none;
        }

        .subject-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .subject-info p {
            font-size: 12px;
            color: #6c757d;
        }

        .subject-score {
            text-align: right;
        }

        .grade {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
        }

        .percentage {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
        }

        .progress-bar {
            width: 100px;
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 2px;
        }

        .progress-fill.excellent { background: #34a853; }
        .progress-fill.good { background: #4285f4; }
        .progress-fill.average { background: #fbbc04; }
        .progress-fill.poor { background: #ea4335; }

        /* Attendance Chart */
        .attendance-overview {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .attendance-stat {
            text-align: center;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .attendance-stat h4 {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .attendance-stat p {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
        }

        .attendance-chart {
            height: 200px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: end;
            padding: 20px;
            gap: 8px;
        }

        .chart-bar {
            flex: 1;
            background: #4285f4;
            border-radius: 4px 4px 0 0;
            min-height: 20px;
            position: relative;
            display: flex;
            align-items: end;
            justify-content: center;
            padding-bottom: 8px;
        }

        .chart-label {
            position: absolute;
            bottom: -20px;
            font-size: 10px;
            color: #6c757d;
            font-weight: 500;
        }

        /* Behavior Score */
        .behavior-score {
            text-align: center;
            padding: 20px;
        }

        .score-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(#34a853 0deg 324deg, #e9ecef 324deg 360deg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #34a853;
            font-size: 20px;
            font-weight: 700;
        }

        .activities-list {
            margin-top: 20px;
        }

        .activity-tag {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            margin: 4px;
        }

        .achievements-list {
            margin-top: 20px;
        }

        .achievement-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .achievement-item:last-child {
            border-bottom: none;
        }

        .achievement-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffd700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f57c00;
            font-size: 14px;
        }

        /* Teacher Feedback */
        .feedback-item {
            padding: 16px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .feedback-teacher {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .feedback-date {
            font-size: 12px;
            color: #6c757d;
        }

        .feedback-type {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .feedback-type.positive {
            background: #e8f5e8;
            color: #2d5a2d;
        }

        .feedback-type.suggestion {
            background: #fff3cd;
            color: #856404;
        }

        .feedback-comment {
            font-size: 13px;
            color: #2c3e50;
            line-height: 1.5;
        }

        /* Health Information */
        .health-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .health-item {
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
        }

        .health-item h4 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .health-item p {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
        }

        /* Full Width Cards */
        .full-width-card {
            grid-column: 1 / -1;
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
            
            .profile-content {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            
            .profile-meta {
                grid-template-columns: 1fr;
            }
            
            .profile-actions {
                margin-left: 0;
            }
            
            .header-right .user-info {
                display: none;
            }
            
            .attendance-overview {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .health-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Print Styles */
        @media print {
            .sidebar, .header, .profile-actions {
                display: none;
            }
            
            .main-content {
                margin: 0;
                box-shadow: none;
            }
            
            .profile-card {
                break-inside: avoid;
                margin-bottom: 20px;
            }
        }

        /* Animation Classes */
        .fade-in {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .slide-up {
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.5s forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
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
                    <div class="logo-subtitle">Parent Portal</div>
                </div>
            </div>
            
            <nav class="main-menu">
                <div class="menu-title">Main Menu</div>
                <a href="dashboard.php" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="child-profile.php" class="menu-item active">
                    <i class="fas fa-user"></i>
                    <span>Child Profile</span>
                </a>
                <a href="progress-report.php" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Progress Report</span>
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
                    <span>Transportation</span>
                </a>
                <a href="parent-profile.php" class="menu-item">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h1>Child Profile</h1>
                    <p>Detailed information about <?php echo htmlspecialchars($child_info['name']); ?></p>
                </div>
                <div class="header-right">
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                        <div class="notification-badge">4</div>
                    </div>
                    <div class="user-profile">
                        <img src="<?php echo $parent_info['avatar']; ?>" alt="Profile" class="user-avatar">
                        <div class="user-info">
                            <h4><?php echo htmlspecialchars($parent_info['name']); ?></h4>
                            <p><?php echo htmlspecialchars($parent_info['role']); ?></p>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <!-- Profile Header -->
                <div class="profile-header">
                    <img src="<?php echo $child_info['avatar']; ?>" alt="<?php echo htmlspecialchars($child_info['name']); ?>" class="child-avatar-large">
                    <div class="profile-details">
                        <h1><?php echo htmlspecialchars($child_info['name']); ?></h1>
                        <div class="profile-meta">
                            <div class="meta-item">
                                <i class="fas fa-id-card"></i>
                                <span>ID: <?php echo htmlspecialchars($child_info['id']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-school"></i>
                                <span>Class: <?php echo htmlspecialchars($child_info['class']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-hashtag"></i>
                                <span>Roll: <?php echo htmlspecialchars($child_info['roll_number']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>Teacher: <?php echo htmlspecialchars($child_info['teacher']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>DOB: <?php echo date('M d, Y', strtotime($child_info['date_of_birth'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-tint"></i>
                                <span>Blood Group: <?php echo htmlspecialchars($child_info['blood_group']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="#" class="btn btn-primary" onclick="printProfile()">
                            <i class="fas fa-print"></i>
                            Print Profile
                        </a>
                        <a href="#" class="btn btn-primary" onclick="downloadProfile()">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                </div>

                <!-- Profile Content Grid -->
                <div class="profile-content">
                    <!-- Academic Performance -->
                    <div class="profile-card">
                        <div class="card-header">
                            <h3>
                                <div class="card-icon blue">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                Academic Performance
                            </h3>
                            <a href="progress-report.php" class="btn-link">View Details</a>
                        </div>
                        <div class="card-body">
                            <?php foreach ($academic_performance as $subject): ?>
                            <div class="subject-item">
                                <div class="subject-info">
                                    <h4><?php echo htmlspecialchars($subject['subject']); ?></h4>
                                    <p><?php echo htmlspecialchars($subject['teacher_remarks']); ?></p>
                                </div>
                                <div class="subject-score">
                                    <div class="grade"><?php echo htmlspecialchars($subject['current_grade']); ?></div>
                                    <div class="percentage"><?php echo $subject['percentage']; ?>%</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill <?php echo $subject['percentage'] >= 90 ? 'excellent' : ($subject['percentage'] >= 80 ?