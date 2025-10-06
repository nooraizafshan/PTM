<?php
session_start();

// Check if user is logged in and has parent role
// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'parent') {
//     header('Location: login.php');
//     exit();
// }

// Configuration
$site_title = "Parent Dashboard - EduConnect";
$current_page = 'dashboard';

// Sample data (replace with database queries)
$parent_info = [
    'name' => 'Ahmad Ali',
    'role' => 'Parent',
    'avatar' => 'https://via.placeholder.com/40x40?text=AA'
];

// Child information
$child_info = [
    'name' => 'Fatima Ahmad',
    'id' => 'STU001',
    'class' => '5A',
    'roll_number' => '15',
    'avatar' => 'https://via.placeholder.com/60x60?text=FA',
    'teacher' => 'Sarah Johnson'
];

// Stats for parent view
$stats = [
    'attendance_percentage' => 95,
    'present_days' => 142,
    'absent_days' => 8,
    'total_days' => 150
];

// Recent activities/updates
$recent_updates = [
    [
        'type' => 'feedback',
        'title' => 'Teacher Feedback',
        'message' => 'Fatima showed excellent performance in Mathematics quiz.',
        'date' => '2024-01-20',
        'icon' => 'fas fa-comment-alt',
        'color' => 'green'
    ],
    [
        'type' => 'attendance',
        'title' => 'Attendance Alert',
        'message' => 'Your child was marked present today at 8:15 AM.',
        'date' => '2024-01-20',
        'icon' => 'fas fa-check-circle',
        'color' => 'blue'
    ],
    [
        'type' => 'meeting',
        'title' => 'Meeting Scheduled',
        'message' => 'Parent-teacher meeting scheduled for Jan 25, 2024 at 3:00 PM.',
        'date' => '2024-01-18',
        'icon' => 'fas fa-calendar',
        'color' => 'purple'
    ],
    [
        'type' => 'transport',
        'title' => 'Transport Update',
        'message' => 'Bus will arrive 10 minutes late due to traffic.',
        'date' => '2024-01-17',
        'icon' => 'fas fa-bus',
        'color' => 'orange'
    ]
];

// Quick actions
$quick_actions = [
    [
        'title' => 'View Progress Report',
        'description' => 'Check detailed academic performance',
        'icon' => 'fas fa-chart-bar',
        'color' => 'blue',
        'link' => 'progress-report.php'
    ],
    [
        'title' => 'Attendance Report',
        'description' => 'View attendance history and trends',
        'icon' => 'fas fa-calendar-check',
        'color' => 'green',
        'link' => 'attendance.php'
    ],
    [
        'title' => 'Schedule Meeting',
        'description' => 'Request meeting with teacher',
        'icon' => 'fas fa-video',
        'color' => 'purple',
        'link' => 'meetings.php'
    ],
    [
        'title' => 'Transportation',
        'description' => 'View bus schedule and route',
        'icon' => 'fas fa-bus',
        'color' => 'orange',
        'link' => 'transport.php'
    ]
];

// Upcoming events
$upcoming_events = [
    [
        'title' => 'Parent-Teacher Meeting',
        'date' => '2024-01-25',
        'time' => '3:00 PM',
        'location' => 'Classroom 5A'
    ],
    [
        'title' => 'Science Fair',
        'date' => '2024-01-30',
        'time' => '10:00 AM',
        'location' => 'School Auditorium'
    ],
    [
        'title' => 'Sports Day',
        'date' => '2024-02-05',
        'time' => '9:00 AM',
        'location' => 'School Ground'
    ]
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

        /* Child Info Card */
        .child-info-card {
            background: linear-gradient(135deg, #4285f4 0%, #34a853 100%);
            border-radius: 16px;
            padding: 24px;
            color: white;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .child-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .child-details h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .child-meta {
            display: flex;
            gap: 20px;
            font-size: 14px;
            opacity: 0.9;
        }

        .child-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Stats Grid */
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

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e9ecef;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .section-header p {
            font-size: 14px;
            color: #6c757d;
        }

        .actions-grid {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .action-card {
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: inherit;
        }

        .action-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .action-icon.blue { background: #4285f4; }
        .action-icon.green { background: #34a853; }
        .action-icon.purple { background: #9c27b0; }
        .action-icon.orange { background: #ff9800; }

        .action-card h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #2c3e50;
        }

        .action-card p {
            font-size: 12px;
            color: #6c757d;
        }

        /* Recent Updates */
        .recent-updates {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .update-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .update-item:hover {
            background: #f8f9fa;
        }

        .update-item:last-child {
            border-bottom: none;
        }

        .update-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }

        .update-icon.green { background: #34a853; }
        .update-icon.blue { background: #4285f4; }
        .update-icon.purple { background: #9c27b0; }
        .update-icon.orange { background: #ff9800; }

        .update-content {
            flex: 1;
        }

        .update-content h5 {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .update-content p {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .update-date {
            font-size: 11px;
            color: #adb5bd;
        }

        /* Upcoming Events */
        .upcoming-section {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            margin-top: 24px;
        }

        .event-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-item:last-child {
            border-bottom: none;
        }

        .event-info h5 {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .event-meta {
            font-size: 12px;
            color: #6c757d;
        }

        .event-date {
            text-align: right;
            font-size: 12px;
            color: #4285f4;
            font-weight: 500;
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
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header-right .user-info {
                display: none;
            }
            
            .child-info-card {
                flex-direction: column;
                text-align: center;
            }
            
            .child-meta {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        .progress-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            margin-left: auto;
        }

        .progress-high {
            background: conic-gradient(#34a853 0deg 342deg, #e9ecef 342deg 360deg);
            color: #34a853;
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
                <a href="dashboard.php" class="menu-item active">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="child-profile.php" class="menu-item">
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
                    <h1>Parent Dashboard</h1>
                    <p>Monitor your child's academic journey</p>
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
                <!-- Child Info Card -->
                <div class="child-info-card">
                    <img src="<?php echo $child_info['avatar']; ?>" alt="<?php echo htmlspecialchars($child_info['name']); ?>" class="child-avatar">
                    <div class="child-details">
                        <h2><?php echo htmlspecialchars($child_info['name']); ?></h2>
                        <div class="child-meta">
                            <span><i class="fas fa-id-card"></i> ID: <?php echo htmlspecialchars($child_info['id']); ?></span>
                            <span><i class="fas fa-school"></i> Class: <?php echo htmlspecialchars($child_info['class']); ?></span>
                            <span><i class="fas fa-hashtag"></i> Roll: <?php echo htmlspecialchars($child_info['roll_number']); ?></span>
                            <span><i class="fas fa-chalkboard-teacher"></i> Teacher: <?php echo htmlspecialchars($child_info['teacher']); ?></span>
                        </div>
                    </div>
                    <div class="progress-circle progress-high">
                        <?php echo $stats['attendance_percentage']; ?>%
                    </div>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['attendance_percentage']; ?>%</h3>
                            <p>Attendance Rate</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['present_days']; ?></h3>
                            <p>Days Present</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['absent_days']; ?></h3>
                            <p>Days Absent</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $stats['total_days']; ?></h3>
                            <p>Total School Days</p>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <div class="section-header">
                            <h3>Quick Actions</h3>
                            <p>Access important features quickly</p>
                        </div>
                        <div class="actions-grid">
                            <?php foreach ($quick_actions as $action): ?>
                            <a href="<?php echo $action['link']; ?>" class="action-card">
                                <div class="action-icon <?php echo $action['color']; ?>">
                                    <i class="<?php echo $action['icon']; ?>"></i>
                                </div>
                                <h4><?php echo htmlspecialchars($action['title']); ?></h4>
                                <p><?php echo htmlspecialchars($action['description']); ?></p>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Recent Updates -->
                    <div class="recent-updates">
                        <div class="section-header">
                            <h3>Recent Updates</h3>
                            <p>Latest notifications and activities</p>
                        </div>
                        <?php foreach ($recent_updates as $update): ?>
                        <div class="update-item">
                            <div class="update-icon <?php echo $update['color']; ?>">
                                <i class="<?php echo $update['icon']; ?>"></i>
                            </div>
                            <div class="update-content">
                                <h5><?php echo htmlspecialchars($update['title']); ?></h5>
                                <p><?php echo htmlspecialchars($update['message']); ?></p>
                                <div class="update-date"><?php echo date('M j, Y', strtotime($update['date'])); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="upcoming-section">
                    <div class="section-header">
                        <h3>Upcoming Events</h3>
                        <p>Don't miss these important dates</p>
                    </div>
                    <?php foreach ($upcoming_events as $event): ?>
                    <div class="event-item">
                        <div class="event-info">
                            <h5><?php echo htmlspecialchars($event['title']); ?></h5>
                            <div class="event-meta">
                                <i class="fas fa-clock"></i> <?php echo htmlspecialchars($event['time']); ?> • 
                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                            </div>
                        </div>
                        <div class="event-date">
                            <?php echo date('M j', strtotime($event['date'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Real-time updates simulation
        function updateNotifications() {
            const badge = document.querySelector('.notification-badge');
            const count = parseInt(badge.textContent);
            // Simulate new notification
            if (Math.random() > 0.8) {
                badge.textContent = count + 1;
            }
        }

        // Update notifications every 30 seconds
        setInterval(updateNotifications, 30000);

        // Notification dropdown
        document.querySelector('.notification-icon').addEventListener('click', function() {
            alert('Recent Notifications:\n• New teacher feedback received\n• Attendance marked for today\n• Meeting reminder for tomorrow\n• Transport schedule updated');
        });

        // User profile dropdown
        document.querySelector('.user-profile').addEventListener('click', function() {
            console.log('Profile dropdown would appear here');
        });

        // Quick action handlers
        function openProgressReport() {
            window.location.href = 'progress-report.php';
        }

        function openAttendance() {
            window.location.href = 'attendance.php';
        }

        function scheduleMeeting() {
            window.location.href = 'meetings.php';
        }

        function viewTransport() {
            window.location.href = 'transport.php';
        }

        // Add loading states to action cards
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!this.classList.contains('loading')) {
                    this.classList.add('loading');
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    icon.className = 'fas fa-spinner fa-spin';
                    
                    setTimeout(() => {
                        this.classList.remove('loading');
                        icon.className = originalClass;
                    }, 1000);
                }
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + D for dashboard
            if (e.altKey && e.key === 'd') {
                e.preventDefault();
                window.location.href = 'dashboard.php';
            }
            
            // Alt + P for progress report
            if (e.altKey && e.key === 'p') {
                e.preventDefault();
                window.location.href = 'progress-report.php';
            }
            
            // Alt + A for attendance
            if (e.altKey && e.key === 'a') {
                e.preventDefault();
                window.location.href = 'attendance.php';
            }
            
            // Alt + M for meetings
            if (e.altKey && e.key === 'm') {
                e.preventDefault();
                window.location.href = 'meetings.php';
            }
        });

        // Auto-refresh dashboard data
        function refreshDashboardData() {
            // In real implementation, this would fetch updated data via AJAX
            console.log('Dashboard data refreshed');
            
            // Simulate data refresh with animation
            document.querySelectorAll('.stat-content h3').forEach(stat => {
                stat.style.opacity = '0.5';
                setTimeout(() => {
                    stat.style.opacity = '1';
                }, 200);
            });
        }

        // Refresh data every 60 seconds
        setInterval(refreshDashboardData, 60000);

        // Smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stats on load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animate action cards
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 500 + index * 100);
            });

            // Animate recent updates
            const updateItems = document.querySelectorAll('.update-item');
            updateItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, 800 + index * 100);
            });
        });

        // Interactive features
        function showChildProgress() {
            const progressData = {
                mathematics: 92,
                english: 88,
                science: 95,
                socialStudies: 85,
                urdu: 90
            };
            
            let progressText = 'Subject-wise Progress:\\n';
            for (const [subject, score] of Object.entries(progressData)) {
                progressText += `• ${subject.charAt(0).toUpperCase() + subject.slice(1)}: ${score}%\\n`;
            }
            
            alert(progressText);
        }

        // Emergency contact feature
        function emergencyContact() {
            alert('Emergency Contact:\\nSchool Office: +92-42-1234567\\nTeacher: +92-300-1234567\\nTransport: +92-301-1234567');
        }

        // Print dashboard
        function printDashboard() {
            window.print();
        }

        // Export attendance data
        function exportAttendanceData() {
            const csvData = `Date,Status,Time\\n2024-01-20,Present,8:15 AM\\n2024-01-19,Present,8:10 AM\\n2024-01-18,Absent,-\\n2024-01-17,Present,8:20 AM`;
            
            const blob = new Blob([csvData], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'attendance_data.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // PWA-like features
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                // In a real app, you would register a service worker here
                console.log('PWA features would be initialized here');
            });
        }

        // Dark mode toggle (future enhancement)
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        }

        // Load dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }

        // Connection status indicator
        function updateConnectionStatus() {
            const isOnline = navigator.onLine;
            const statusIndicator = document.createElement('div');
            statusIndicator.className = 'connection-status';
            statusIndicator.textContent = isOnline ? 'Online' : 'Offline';
            statusIndicator.style.cssText = `
                position: fixed;
                top: 10px;
                right: 10px;
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 12px;
                font-weight: 500;
                color: white;
                background: ${isOnline ? '#34a853' : '#ea4335'};
                z-index: 1000;
                transition: all 0.3s ease;
            `;
            
            // Remove existing status indicator
            const existing = document.querySelector('.connection-status');
            if (existing) existing.remove();
            
            document.body.appendChild(statusIndicator);
            
            // Auto-hide after 3 seconds if online
            if (isOnline) {
                setTimeout(() => statusIndicator.remove(), 3000);
            }
        }

        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);

        // Initialize connection status
        updateConnectionStatus();

        // Voice announcement feature (accessibility)
        function announceUpdate(message) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(message);
                utterance.rate = 0.8;
                utterance.volume = 0.3;
                speechSynthesis.speak(utterance);
            }
        }

        // Geolocation for transport tracking

        function trackBusLocation() {
            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        console.log(`Bus tracking: ${lat}, ${lon}`);
                        // In real implementation, this would update bus location
                    },
                    function(error) {
                        console.log('Location access denied or unavailable');
                    }
                );
            }
        }

        // Initialize bus tracking if transport page is active
        if (window.location.pathname.includes('transport')) {
            trackBusLocation();
        }
        // Custom context menu for quick actions
        document.addEventListener('contextmenu', function(e) {
            if (e.target.closest('.action-card')) {
                e.preventDefault();
                const contextMenu = document.createElement('div');
                contextMenu.innerHTML = `
                    <div style="
                        position: fixed;
                        background: white;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        padding: 8px 0;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        z-index: 1000;
                        left: ${e.pageX}px;
                        top: ${e.pageY}px;
                    ">
                        <div style="padding: 8px 16px; cursor: pointer;" onclick="this.parentElement.parentElement.remove()">Open in New Tab</div>
                        <div style="padding: 8px 16px; cursor: pointer;" onclick="this.parentElement.parentElement.remove()">Add to Favorites</div>
                        <div style="padding: 8px 16px; cursor: pointer;" onclick="this.parentElement.parentElement.remove()">Share</div>
                    </div>
                `;
                document.body.appendChild(contextMenu);
                // Remove context menu when clicking elsewhere
                setTimeout(() => {
                    document.addEventListener('click', function removeMenu() {
                        contextMenu.remove();
                        document.removeEventListener('click', removeMenu);
                    });
                }, 100);
            }
        });
        // Enhanced search functionality
        function initializeSearch() {
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search dashboard...';
            searchInput.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 20px;
                background: white;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                z-index: 999;
                width: 200px;
                display: none;
            `;
            document.body.appendChild(searchInput);
            // Show search with Ctrl/Cmd + K
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.style.display = 'block';
                    searchInput.focus();
                }
                
                if (e.key === 'Escape') {
                    searchInput.style.display = 'none';
                    searchInput.value = '';
                }
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                document.querySelectorAll('.action-card, .update-item, .event-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.opacity = text.includes(term) || term === '' ? '1' : '0.3';
                });
            });
        }

        // Initialize enhanced search
        initializeSearch();

        // Performance monitoring
        function monitorPerformance() {
            if ('performance' in window) {
                const navigation = performance.getEntriesByType('navigation')[0];
                if (navigation) {
                    console.log(`Page load time: ${navigation.loadEventEnd - navigation.loadEventStart}ms`);
                }
            }
        }

        // Monitor performance after page load
        window.addEventListener('load', monitorPerformance);

        // Auto-save user preferences
        function saveUserPreferences() {
            const preferences = {
                theme: document.body.classList.contains('dark-mode') ? 'dark' : 'light',
                notifications: document.querySelector('.notification-badge').textContent,
                lastVisit: new Date().toISOString()
            };
            localStorage.setItem('parentDashboardPrefs', JSON.stringify(preferences));
        }

        // Load user preferences
        function loadUserPreferences() {
            const saved = localStorage.getItem('parentDashboardPrefs');
            if (saved) {
                const preferences = JSON.parse(saved);
                if (preferences.theme === 'dark') {
                    document.body.classList.add('dark-mode');
                }
                console.log(`Last visit: ${preferences.lastVisit}`);
            }
        }

        // Initialize preferences
        loadUserPreferences();

        // Save preferences before page unload
        window.addEventListener('beforeunload', saveUserPreferences);

        // Touch gestures for mobile
        let touchStartX = 0;
        let touchStartY = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', function(e) {
            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;
            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;

            // Swipe right to open menu (on mobile)
            if (deltaX > 100 && Math.abs(deltaY) < 50 && window.innerWidth < 768) {
                document.querySelector('.sidebar').classList.add('mobile-open');
            }

            // Swipe left to close menu
            if (deltaX < -100 && Math.abs(deltaY) < 50 && window.innerWidth < 768) {
                document.querySelector('.sidebar').classList.remove('mobile-open');
            }
        });

        // Add mobile menu styles
        const mobileStyles = document.createElement('style');
        mobileStyles.textContent = `
            @media (max-width: 768px) {
                .sidebar.mobile-open {
                    position: fixed;
                    left: 0;
                    width: 260px;
                    z-index: 1000;
                    box-shadow: 2px 0 8px rgba(0,0,0,0.15);
                }
                .sidebar.mobile-open::before {
                    content: '';
                    position: fixed;
                    top: 0;
                    left: 260px;
                    right: 0;
                    bottom: 0;
                    background: rgba(0,0,0,0.5);
                    z-index: -1;
                }
            }
        `;
        document.head.appendChild(mobileStyles);

        // Lazy loading for images
        function initializeLazyLoading() {
            const images = document.querySelectorAll('img');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        }

        // Initialize lazy loading if Intersection Observer is supported
        if ('IntersectionObserver' in window) {
            initializeLazyLoading();
        }

        // Analytics tracking (placeholder)
        function trackUserAction(action, data = {}) {
            console.log(`Action: ${action}`, data);
            // In real implementation, this would send data to analytics service
        }

        // Track clicks on action cards
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function() {
                trackUserAction('action_card_click', {
                    title: this.querySelector('h4').textContent,
                    timestamp: new Date().toISOString()
                });
            });
        });

        // Error boundary for JavaScript errors
        window.addEventListener('error', function(e) {
            console.error('Dashboard error:', e.error);
            // In real implementation, this would report errors to monitoring service
            
            // Show user-friendly error message
            const errorNotification = document.createElement('div');
            errorNotification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #dc3545;
                color: white;
                padding: 12px 16px;
                border-radius: 4px;
                z-index: 1000;
                max-width: 300px;
            `;
            errorNotification.textContent = 'Something went wrong. Please refresh the page.';
            document.body.appendChild(errorNotification);

            setTimeout(() => errorNotification.remove(), 5000);
        });

        // Initialize all features when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Parent Dashboard initialized successfully');
            
            // Show welcome message for first-time users
            if (!localStorage.getItem('parentDashboardVisited')) {
                setTimeout(() => {
                    alert('Welcome to EduConnect Parent Dashboard! Use Ctrl+K to search, and explore the quick actions for easy navigation.');
                    localStorage.setItem('parentDashboardVisited', 'true');
                }, 2000);
            }
        });
    </script>
</body>
</html>