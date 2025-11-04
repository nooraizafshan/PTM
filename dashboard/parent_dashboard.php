<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Check if user is logged in and is a parent
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'parent') {
    header("Location: ../modules/auth/login.php");
    exit();
}

// ✅ Get requested page (default: dashboard)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// ✅ Page mapping (make sure paths are correct relative to this file)
$pages = [
   'dashboard' => '../modules/parent/dashboard.php', // your dashboard content file
    'view-attendance' => '../modules/attendance/view_attendance.php',
    'view-progress' => '../modules/progress/view_progress.php',
    'meetings' => '../modules/meetings/view_meeting.php',
    'view_feedback' => '../modules/feedback/view_feedback.php',
    'transport' => '../modules/transport/view_transport_details.php',
    'childprofile' => 'childprofile.php',


];

// ✅ Pick correct page file (fallback to dashboard)
$current_page_file = isset($pages[$page]) ? $pages[$page] : $pages['dashboard'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - EduConnect</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8f9fa; color: #2c3e50; }
        .dashboard-container { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background: white; border-right: 1px solid #e9ecef; display: flex; flex-direction: column; position: fixed; height: 100vh; overflow-y: auto; }
        .logo { padding: 20px; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; gap: 12px; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #4285f4, #34a853); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; }
        .logo-text { font-weight: 700; font-size: 18px; color: #2c3e50; }
        .logo-subtitle { font-size: 12px; color: #6c757d; }
        .main-menu { padding: 20px 0; flex: 1; }
        .menu-item { display: flex; align-items: center; padding: 12px 20px; color: #6c757d; text-decoration: none; transition: all 0.2s ease; }
        .menu-item:hover { background: #f8f9fa; color: #2c3e50; }
        .menu-item.active { background: #e3f2fd; color: #1976d2; border-right: 3px solid #1976d2; }
        .menu-item i { width: 20px; margin-right: 12px; font-size: 16px; }

        /* Main Content */
        .main-content { flex: 1; margin-left: 260px; display: flex; flex-direction: column; }
        .header { background: white; padding: 16px 24px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .header-left h1 { font-size: 24px; font-weight: 600; color: #2c3e50; }
        .header-left p { font-size: 14px; color: #6c757d; }
        .content { padding: 24px; flex: 1; }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .main-content { margin-left: 80px; }
            .logo-text, .logo-subtitle, .menu-item span { display: none; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../includes/parent_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <?php include '../includes/parent_header.php'; ?>

            <div class="content">
                <?php
                if (file_exists($current_page_file)) {
                    include $current_page_file;
                } else {
                    echo '<div style="color:red;">⚠️ Page not found!</div>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>
