<?php
// session_start();
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: auth/login.php");
//     exit();
// }

$page_titles = [
    'dashboard' => ['Admin Dashboard', 'Overview of system activities'],
    'manage-users' => ['User Management', 'Add, update or remove users'],
    'transport' => ['Transport Management', 'Manage routes and schedules'],
    'reports' => ['Reports Overview', 'View system-wide performance reports'],
    'feedback' => ['Feedback Management', 'Review and respond to feedback'],
    'meetings' => ['Meeting Logs', 'Monitor parent-teacher meetings'],
    'profile' => ['My Profile', 'View and edit admin profile']
];

$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$page_info = isset($page_titles[$current_page]) ? $page_titles[$current_page] : ['Dashboard', 'Welcome'];

$admin_name = $_SESSION['username'] ?? 'Admin';
$name_parts = explode(' ', $admin_name);
$initials = '';
foreach ($name_parts as $part) {
    $initials .= strtoupper(substr($part, 0, 1));
}
?>

<header class="header">
    <div class="header-left">
        <h1><?php echo htmlspecialchars($page_info[0]); ?></h1>
        <p><?php echo htmlspecialchars($page_info[1]); ?></p>
    </div>
    <div class="header-right">
        <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <div class="notification-badge">5</div>
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?php echo $initials; ?></div>
            <div class="user-info">
                <h4><?php echo htmlspecialchars($admin_name); ?></h4>
                <p>System Administrator</p>
            </div>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</header> 