<?php
// Get current page for active state
$current = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<aside class="sidebar">
    <div class="logo">
        <div class="logo-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div>
            <div class="logo-text">EduConnect</div>
            <div class="logo-subtitle">Teacher Portal</div>
        </div>
    </div>
    <nav class="main-menu">
        <div class="menu-title">Main Menu</div>
        <a href="teacher_dashboard.php?page=dashboard" class="menu-item <?php echo $current == 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
      
        <div class="menu-title" style="margin-top: 20px;">Academic</div>
        <a href="teacher_dashboard.php?page=mark-attendance" class="menu-item <?php echo $current == 'mark-attendance' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-check"></i>
            <span>Mark Attendance</span>
        </a>
       
        <a href="teacher_dashboard.php?page=generate-progress" class="menu-item <?php echo $current == 'generate-progress' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            <span>Progress Reports</span>
        </a>
        <div class="menu-title" style="margin-top: 20px;">Communication</div>
        
        <a href="teacher_dashboard.php?page=meetings" class="menu-item <?php echo $current == 'meetings' ? 'active' : ''; ?>">
            <i class="fas fa-video"></i>
            <span>Meetings</span>
        </a>
        <a href="teacher_dashboard.php?page=feedback" class="menu-item <?php echo $current == 'feedback' ? 'active' : ''; ?>">
            <i class="fas fa-comment-alt"></i>
            <span>Feedback</span>
        </a>
        <a href="../index.php" class="menu-item" style="margin-top: 20px; color: #dc3545;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside>