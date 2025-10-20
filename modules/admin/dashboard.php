
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
    .stat-icon.purple { background: linear-gradient(135deg, #9c27b0, #8e24aa); }

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

<!-- ✅ Admin Stats Section -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users-cog"></i>
        </div>
        <div class="stat-content">
            <h3>12</h3>
            <p>Total Teachers</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <h3>156</h3>
            <p>Total Students</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-bus"></i>
        </div>
        <div class="stat-content">
            <h3>8</h3>
            <p>Active Transport Routes</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-comments"></i>
        </div>
        <div class="stat-content">
            <h3>25</h3>
            <p>New Feedback Messages</p>
        </div>
    </div>
</div>

<!-- ✅ Admin Quick Actions -->
<div class="quick-actions">
    <h2>Quick Actions</h2>
    <div class="action-grid">
      
        <a href="admin_dashboard.php?page=transport" class="action-btn">
            <i class="fas fa-bus"></i>
            <span>Manage Transport</span>
        </a>
        <a href="admin_dashboard.php?page=reports" class="action-btn">
            <i class="fas fa-chart-line"></i>
            <span>View Reports</span>
        </a>
        <a href="admin_dashboard.php?page=feedback" class="action-btn">
            <i class="fas fa-comments"></i>
            <span>Review Feedback</span>
        </a>
    </div>
</div>

<!-- ✅ Welcome Info Box -->
<div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e9ecef;">
    <h2 style="font-size: 18px; font-weight: 600; color: #2c3e50; margin-bottom: 16px;">
        <i class="fas fa-info-circle" style="color: #4285f4; margin-right: 8px;"></i>
        Welcome to EduConnect Admin Dashboard
    </h2>
    <p style="color: #6c757d; line-height: 1.6;">
        This is your control panel for managing transport, feedback, and reports. 
        Use the sidebar to navigate through administrative tools.
    </p>
</div>
