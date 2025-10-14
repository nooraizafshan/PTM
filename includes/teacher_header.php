<?php
$page_titles = [
    'dashboard' => ['Dashboard', 'Welcome back to your dashboard'],
    'students' => ['Student Management', 'Manage and monitor your students'],
    'mark-attendance' => ['Mark Attendance', 'Record student attendance for today'],
    'view-attendance' => ['View Attendance', 'View attendance records and reports'],
    'generate-report' => ['Progress Reports', 'Generate and manage student progress reports'],
    'meetings' => ['Meetings', 'Schedule and manage parent-teacher meetings'],
    'feedback' => ['Feedback', 'Send feedback to parents'],
    'profile' => ['My Profile', 'View and edit your profile']
];

$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$page_info = isset($page_titles[$current_page]) ? $page_titles[$current_page] : ['Dashboard', 'Welcome'];

$teacher_name = $_SESSION['username'] ?? 'Teacher';
$name_parts = explode(' ', $teacher_name);
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
            <div class="notification-badge">3</div>
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?php echo $initials; ?></div>
            <div class="user-info">
                <h4><?php echo htmlspecialchars($teacher_name); ?></h4>
                <p><?php echo htmlspecialchars($_SESSION['subject'] ?? 'Teacher'); ?></p>
            </div>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</header>
