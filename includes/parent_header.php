<?php
// =========================
// ✅ Parent Dashboard Header
// =========================

// Define page titles and subtitles for parent dashboard
$page_titles = [
    'dashboard' => ['Parent Dashboard', 'Monitor your child’s academic journey'],
    'view-progress' => ['Progress Report', 'Track your child’s academic performance'],
    'view-attendance' => ['Attendance', 'Check attendance records and patterns'],
    'meetings' => ['Meetings', 'Schedule or view parent-teacher meetings'],
    'feedback' => ['Feedback', 'View teacher feedback and messages'],
    'transport' => ['Transportation', 'View your child’s bus route and timing'],
    'parent-profile' => ['My Profile', 'Manage your parent account'],
    'settings' => ['Settings', 'Customize your account preferences'],
];

// Current page detection
$current_page = $_GET['page'] ?? 'dashboard';
$page_info = $page_titles[$current_page] ?? ['Dashboard', 'Welcome to EduConnect'];

// Parent info
$parent_name = $_SESSION['username'] ?? 'Parent User';
$name_parts = explode(' ', $parent_name);
$initials = '';
foreach ($name_parts as $part) {
    $initials .= strtoupper(substr($part, 0, 1));
}
?>

<header class="header">
    <div class="header-left">
        <h1><?= htmlspecialchars($page_info[0]) ?></h1>
        <p><?= htmlspecialchars($page_info[1]) ?></p>
    </div>

    <div class="header-right">
        <!-- Notifications -->
        <div class="notification-icon" title="Notifications">
            <i class="fas fa-bell"></i>
            <div class="notification-badge">3</div>
        </div>

        <!-- User Profile -->
        <div class="user-profile">
            <div class="user-avatar"><?= $initials ?></div>
            <div class="user-info">
                <h4><?= htmlspecialchars($parent_name) ?></h4>
                <p>Parent</p>
            </div>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</header>

<!-- ========================= -->
<!-- ✅ STYLES -->
<!-- ========================= -->
<style>
/* ===== HEADER CONTAINER ===== */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 14px 28px;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 1000;
}

/* ===== HEADER LEFT ===== */
.header-left h1 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e40af;
    margin: 0;
}

.header-left p {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 4px 0 0;
}

/* ===== HEADER RIGHT ===== */
.header-right {
    display: flex;
    align-items: center;
    gap: 24px;
}

/* ===== NOTIFICATION ICON ===== */
.notification-icon {
    position: relative;
    font-size: 1.4rem;
    color: #1e40af;
    cursor: pointer;
    transition: color 0.3s ease;
}

.notification-icon:hover {
    color: #2563eb;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -6px;
    background: #ef4444;
    color: white;
    font-size: 0.7rem;
    border-radius: 50%;
    padding: 2px 6px;
}

/* ===== USER PROFILE BOX ===== */
.user-profile {
    display: flex;
    align-items: center;
    background: #f3f4f6;
    border-radius: 40px;
    padding: 6px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-profile:hover {
    background: #e5e7eb;
}

.user-avatar {
    width: 42px;
    height: 42px;
    background: #2563eb;
    color: #fff;
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

/* Chevron */
.user-profile i {
    color: #6b7280;
    margin-left: 6px;
    font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .header-right {
        width: 100%;
        justify-content: space-between;
    }
}
</style>
