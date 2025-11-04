<?php
$page_titles = [
    'dashboard' => ['Dashboard', 'Welcome back to your dashboard'],
    'students' => ['Student Management', 'Manage and monitor your students'],
    'mark-attendance' => ['Mark Attendance', 'Record student attendance for today'],
    'view-attendance' => ['View Attendance', 'View attendance records and reports'],
    'generate-progress' => ['Progress Reports', 'Generate and manage student progress reports'],
    'meetings' => ['Meetings', 'Schedule and manage parent-teacher meetings'],
    'feedback' => ['Feedback', 'Send feedback to parents'],
    'profile' => ['My Profile', 'View and edit your profile']
];

$current_page = $_GET['page'] ?? 'dashboard';
$page_info = $page_titles[$current_page] ?? ['Dashboard', 'Welcome'];

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

<style>
/* ===== HEADER STYLING ===== */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 12px 24px;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-left h1 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #1e3a8a;
    margin: 0;
}

.header-left p {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 3px 0 0;
}

/* ===== HEADER RIGHT ===== */
.header-right {
    display: flex;
    align-items: center;
    gap: 25px;
}

/* Notification Icon */
.notification-icon {
    position: relative;
    font-size: 1.3rem;
    color: #1e3a8a;
    cursor: pointer;
    transition: color 0.3s ease;
}

.notification-icon:hover {
    color: #2563eb;
}

.notification-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ef4444;
    color: white;
    font-size: 0.7rem;
    border-radius: 50%;
    padding: 2px 6px;
}

/* ===== USER PROFILE ===== */
.user-profile {
    display: flex;
    align-items: center;
    background: #f3f4f6;
    border-radius: 50px;
    padding: 6px 12px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.user-profile:hover {
    background: #e5e7eb;
}

.user-avatar {
    width: 42px;
    height: 42px;
    background: #2563eb;
    color: white;
    font-weight: 600;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    margin-right: 10px;
}

.user-info h4 {
    margin: 0;
    font-size: 0.95rem;
    color: #111827;
    line-height: 1.2;
}

.user-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #6b7280;
}
</style>
