<?php
session_start();

// Check if user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: modules/auth/login.php");
    exit();
}

// Get page parameter (default: dashboard)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Page mapping
$pages = [
    'dashboard' => '../dashboard/teacher_dashboard.php',
    'mark-attendance' => 'modules/attendance/mark_attendance.php',
    'generate-report' => 'modules/progress/generate_progress.php',
    'meetings' => 'modules/meetings/schedule_meeting.php',
    'feedback' => 'modules/feedback/feedback.php',
   
];

// Get current page file
$current_page_file = isset($pages[$page]) ? $pages[$page] : 'dashboard/teacher_dashboard.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - EduConnect</title>
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

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #4285f4, #34a853);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .logo-text {
            font-weight: 700;
            font-size: 18px;
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

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .header {
            background: white;
            padding: 16px 24px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
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
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .user-profile:hover {
            background: #f8f9fa;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4285f4, #34a853);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
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

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .main-content {
                margin-left: 80px;
            }

            .logo-text, .logo-subtitle, .menu-title {
                display: none;
            }

            .menu-item span {
                display: none;
            }

            .user-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'includes/teacher_sidebar.php'; ?>
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php include 'includes/teacher_header.php'; ?>

            <!-- Dynamic Content Area -->
            <div class="content">
                <?php
                if (file_exists($current_page_file)) {
                    include $current_page_file;
                } else {
                    echo '<div class="alert alert-danger">Page not found!</div>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>