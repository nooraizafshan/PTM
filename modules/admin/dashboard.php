<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #4285f4, #357ae8); }
    .stat-icon.green { background: linear-gradient(135deg, #34a853, #2d9447); }
    .stat-icon.orange { background: linear-gradient(135deg, #fbbc04, #f9ab00); }
    .stat-icon.red { background: linear-gradient(135deg, #ea4335, #d93025); }

    .stat-content h3 {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .stat-content p {
        font-size: 14px;
        color: #6c757d;
    }

    .quick-actions {
        background: white;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        margin-bottom: 30px;
    }

    .quick-actions h2 {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .action-btn {
        padding: 16px 20px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        text-decoration: none;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .action-btn i {
        font-size: 20px;
        color: #4285f4;
    }
</style>

<!-- Admin Dashboard Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>312</h3>
            <p>Total Users</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-content">
            <h3>48</h3>
            <p>Total Teachers</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <h3>264</h3>
            <p>Registered Students</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="stat-content">
            <h3>7</h3>
            <p>Pending Issues</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h2>Admin Quick Actions</h2>
    <div class="action-grid">
        <a href="admin_dashboard.php?page=manage-users" class="action-btn">
            <i class="fas fa-user-cog"></i>
            <span>Manage Users</span>
        </a>
        <a href="admin_dashboard.php?page=manage-teachers" class="action-btn">
            <i class="fas fa-chalkboard"></i>
            <span>Manage Teachers</span>
        </a>
        <a href="admin_dashboard.php?page=system-reports" class="action-btn">
            <i class="fas fa-chart-line"></i>
            <span>System Reports</span>
        </a>
        <a href="admin_dashboard.php?page=settings" class="action-btn">
            <i class="fas fa-cogs"></i>
            <span>System Settings</span>
        </a>
        <a href="admin_dashboard.php?page=notifications" class="action-btn">
            <i class="fas fa-bell"></i>
            <span>Manage Notifications</span>
        </a>
        <a href="admin_dashboard.php?page=backup" class="action-btn">
            <i class="fas fa-database"></i>
            <span>Backup Data</span>
        </a>
    </div>
</div>

<!-- Welcome Section -->
<div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e9ecef;">
    <h2 style="font-size: 18px; font-weight: 600; color: #2c3e50; margin-bottom: 16px;">
        <i class="fas fa-info-circle" style="color: #4285f4; margin-right: 8px;"></i>
        Welcome to EduConnect Admin Dashboard
    </h2>
    <p style="color: #6c757d; line-height: 1.6;">
        This is your control center for managing users, teachers, students, and system operations. 
        Use the sidebar or quick actions to access various administrative tools efficiently.
    </p>
</div>
