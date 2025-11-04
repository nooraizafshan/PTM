<?php
// Parent Dashboard Header Component
// Usage: include 'includes/parent_header.php';

// $page_titles = [
//     'dashboard' => ['Parent Dashboard', 'Monitor your child\'s academic journey'],
//     'child-profile' => ['Child Profile', 'View detailed student information'],
//     'progress-report' => ['Progress Report', 'Academic performance and grades'],
//     'attendance' => ['Attendance', 'View attendance records and history'],
//     'meetings' => ['Meetings', 'Schedule and manage parent-teacher meetings'],
//     'feedback' => ['Feedback', 'Teacher feedback and communications'],
//     'transport' => ['Transportation', 'Bus routes and schedule information'],
//     'parent-profile' => ['My Profile', 'View and edit your profile']
// ];
$page_titles = [
    'dashboard' => ['Dashboard', 'Welcome back to your dashboard'],
    'students' => ['Student Management', 'Manage and monitor your students'],
    'mark-attendance' => ['Mark Attendance', 'Record student attendance for today'],
    'view-attendance' => ['View Attendance', 'View attendance records and reports'],
    'generate-progress' => ['Progress Reports', 'Generate and manage student progress reports'],  // ✅ Ye change karo
    'meetings' => ['Meetings', 'Schedule and manage parent-teacher meetings'],
    'feedback' => ['Feedback', 'Send feedback to parents'],
    'profile' => ['My Profile', 'View and edit your profile']
];

$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$page_info = isset($page_titles[$current_page]) ? $page_titles[$current_page] : ['Dashboard', 'Welcome'];

$parent_name = $_SESSION['username'] ?? 'Parent';
$name_parts = explode(' ', $parent_name);
$initials = '';
foreach ($name_parts as $part) {
    if (!empty($part)) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
}

// Get notification count (you can fetch from database)
$notification_count = 4;
?>

<header class="header">
    <div class="header-left">
        <h1><?php echo htmlspecialchars($page_info[0]); ?></h1>
        <p><?php echo htmlspecialchars($page_info[1]); ?></p>
    </div>
    <div class="header-right">
        <div class="notification-icon" onclick="toggleNotifications()">
            <i class="fas fa-bell"></i>
            <?php if ($notification_count > 0): ?>
            <div class="notification-badge"><?php echo $notification_count; ?></div>
            <?php endif; ?>
        </div>
        <div class="user-profile" onclick="toggleProfileMenu()">
            <div class="user-avatar"><?php echo $initials; ?></div>
            <div class="user-info">
                <h4><?php echo htmlspecialchars($parent_name); ?></h4>
                <p>Parent</p>
            </div>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</header>

<!-- Notification Dropdown (Hidden by default) -->
<div id="notificationDropdown" class="notification-dropdown" style="display: none;">
    <div class="notification-header">
        <h4>Notifications</h4>
        <a href="#" onclick="markAllRead()">Mark all as read</a>
    </div>
    <div class="notification-list">
        <div class="notification-item unread">
            <div class="notification-icon-wrapper green">
                <i class="fas fa-comment-alt"></i>
            </div>
            <div class="notification-content">
                <p class="notification-title">New Teacher Feedback</p>
                <p class="notification-text">Excellent performance in Mathematics</p>
                <span class="notification-time">2 hours ago</span>
            </div>
        </div>
        <div class="notification-item">
            <div class="notification-icon-wrapper blue">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="notification-content">
                <p class="notification-title">Meeting Reminder</p>
                <p class="notification-text">Parent-teacher meeting tomorrow at 3 PM</p>
                <span class="notification-time">5 hours ago</span>
            </div>
        </div>
        <div class="notification-item">
            <div class="notification-icon-wrapper orange">
                <i class="fas fa-bus"></i>
            </div>
            <div class="notification-content">
                <p class="notification-title">Transport Update</p>
                <p class="notification-text">Bus route modified temporarily</p>
                <span class="notification-time">1 day ago</span>
            </div>
        </div>
    </div>
    <div class="notification-footer">
        <a href="parent_dashboard.php?page=notifications">View All Notifications</a>
    </div>
</div>

<!-- Profile Dropdown (Hidden by default) -->
<div id="profileDropdown" class="profile-dropdown" style="display: none;">
    <div class="profile-menu-header">
        <div class="profile-menu-avatar"><?php echo $initials; ?></div>
        <div>
            <h4><?php echo htmlspecialchars($parent_name); ?></h4>
            <p>Parent Account</p>
        </div>
    </div>
    <div class="profile-menu-divider"></div>
    <a href="parent_dashboard.php?page=parent-profile" class="profile-menu-item">
        <i class="fas fa-user"></i>
        <span>My Profile</span>
    </a>
    <a href="parent_dashboard.php?page=settings" class="profile-menu-item">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
    </a>
    <a href="parent_dashboard.php?page=help" class="profile-menu-item">
        <i class="fas fa-question-circle"></i>
        <span>Help & Support</span>
    </a>
    <div class="profile-menu-divider"></div>
    <a href="../modules/auth/logout.php" class="profile-menu-item logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>

<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    const profileDropdown = document.getElementById('profileDropdown');
    profileDropdown.style.display = 'none';
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function toggleProfileMenu() {
    const dropdown = document.getElementById('profileDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    notificationDropdown.style.display = 'none';
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function markAllRead() {
    document.querySelectorAll('.notification-item').forEach(item => {
        item.classList.remove('unread');
    });
    document.querySelector('.notification-badge').style.display = 'none';
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.notification-icon') && !event.target.closest('.notification-dropdown')) {
        document.getElementById('notificationDropdown').style.display = 'none';
    }
    if (!event.target.closest('.user-profile') && !event.target.closest('.profile-dropdown')) {
        document.getElementById('profileDropdown').style.display = 'none';
    }
});
</script>