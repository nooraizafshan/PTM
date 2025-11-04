<?php
$current = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<aside class="sidebar">
    <div class="logo">
        <div class="logo-icon">
            <i class="fas fa-user-friends"></i>
        </div>
        <div>
            <div class="logo-text">EduConnect</div>
            <div class="logo-subtitle">Parent Portal</div>
        </div>
    </div>

    <nav class="main-menu">
        <a href="parent_dashboard.php?page=dashboard" class="menu-item <?php echo $current == 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i><span>Dashboard</span>
        </a>

        <a href="parent_dashboard.php?page=view_progress.php" class="menu-item <?php echo $current == 'progress-report' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i><span>Progress Report</span>
        </a>

        <a href="parent_dashboard.php?page=attendance" class="menu-item <?php echo $current == 'attendance' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-check"></i><span>Attendance</span>
        </a>

        <a href="parent_dashboard.php?page=view_meetings" class="menu-item <?php echo $current == 'meetings' ? 'active' : ''; ?>">
            <i class="fas fa-video"></i><span>Meetings</span>
        </a>

        <a href="parent_dashboard.php?page=transport" class="menu-item <?php echo $current == 'transport' ? 'active' : ''; ?>">
            <i class="fas fa-bus"></i><span>Transport</span>
        </a>

        <a href="parent_dashboard.php?page=profile" class="menu-item <?php echo $current == 'profile' ? 'active' : ''; ?>">
            <i class="fas fa-user-circle"></i><span>Profile</span>
        </a>

        <a href="../modules/auth/logout.php" class="menu-item" style="margin-top: 20px; color: #dc3545;">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
        </a>
    </nav>
</aside>
