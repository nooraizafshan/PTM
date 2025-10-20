<?php
// Get current page for active state
$current = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<aside class="sidebar">
    <div class="logo">
        <div class="logo-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <div>
            <div class="logo-text">EduConnect</div>
            <div class="logo-subtitle">Admin Portal</div>
        </div>
    </div>

    <nav class="main-menu">

        <a href="admin_dashboard.php?page=dashboard" class="menu-item <?php echo $current == 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

     
        <a href="admin_dashboard.php?page=transport" class="menu-item <?php echo $current == 'transport' ? 'active' : ''; ?>">
            <i class="fas fa-bus"></i>
            <span>Transport</span>
        </a>

      
       

     

        <a href="../index.php" class="menu-item" style="margin-top: 20px; color: #dc3545;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside>
