<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Include database configuration
require_once __DIR__ . '/../../config/db.php';

// ✅ Establish database connection
$conn = dbConnect();

// ✅ Verify connection
if (!$conn || $conn->connect_error) {
    die("Database connection not available: " . $conn->connect_error);
}

// ✅ Simulate logged-in parent (replace with session login in production)
$parent_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// ✅ Fetch parent details
$parent_query = "SELECT * FROM users WHERE id = ? AND role = 'parent'";
$stmt = $conn->prepare($parent_query);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$parent_result = $stmt->get_result();
$parent = $parent_result->fetch_assoc();

if (!$parent) {
    die("Parent not found or invalid user type.");
}

$child_name = $parent['child_name'];
$parent_name = $parent['name'];

// ✅ Get filters
$status_filter = $_GET['status'] ?? 'all';
$date_filter = $_GET['date'] ?? '';

// ✅ Build query
$query = "SELECT * FROM ptm_meetings WHERE student_name = ?";
$params = [$child_name];
$types = "s";

if ($status_filter != 'all') {
    $query .= " AND status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if (!empty($date_filter)) {
    $query .= " AND meeting_date = ?";
    $params[] = $date_filter;
    $types .= "s";
}

$query .= " ORDER BY meeting_date DESC, meeting_time DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$meetings_result = $stmt->get_result();

// ✅ Helper for stats
function getCount($conn, $sql, $child_name) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $child_name);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res[array_key_first($res)];
}

$total_count = getCount($conn, "SELECT COUNT(*) as total FROM ptm_meetings WHERE student_name = ?", $child_name);
$scheduled_count = getCount($conn, "SELECT COUNT(*) as scheduled FROM ptm_meetings WHERE student_name = ? AND status = 'scheduled'", $child_name);
$completed_count = getCount($conn, "SELECT COUNT(*) as completed FROM ptm_meetings WHERE student_name = ? AND status = 'completed'", $child_name);
$cancelled_count = getCount($conn, "SELECT COUNT(*) as cancelled FROM ptm_meetings WHERE student_name = ? AND status = 'cancelled'", $child_name);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Parent Meeting Dashboard</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(135deg,#a8e6cf 0%,#7fcdcd 50%,#81c784 100%);min-height:100vh;padding:20px}
.container{max-width:1200px;margin:0 auto}
.header{background:white;padding:25px 30px;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,.1);margin-bottom:30px}
.header h1{color:#00b894;font-size:28px;margin-bottom:10px}
.header p{color:#666;font-size:16px}
.stats-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px}
.stat-card{background:white;padding:20px;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);text-align:center;transition:transform .3s ease}
.stat-card:hover{transform:translateY(-5px)}
.stat-number{font-size:32px;font-weight:bold;color:#00b894;margin-bottom:5px}
.stat-label{color:#666;font-size:14px}
.filters{background:white;padding:20px 30px;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);margin-bottom:25px;display:flex;gap:15px;flex-wrap:wrap;align-items:center}
.filters label{color:#333;font-weight:600;font-size:14px}
.filters select,.filters input[type=date]{padding:10px 15px;border:2px solid #e0e0e0;border-radius:8px;font-size:14px;outline:none;transition:border-color .3s}
.filters select:focus,.filters input[type=date]:focus{border-color:#00b894}
.filter-group{display:flex;flex-direction:column;gap:5px}
.btn{padding:10px 20px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:all .3s ease;text-decoration:none;display:inline-block}
.btn-primary{background:linear-gradient(135deg,#00b894,#00cec9);color:white}
.btn-primary:hover{transform:scale(1.05);box-shadow:0 5px 15px rgba(0,184,148,.3)}
.btn-secondary{background:#e0e0e0;color:#666}
.btn-secondary:hover{background:#d0d0d0}
.meetings-grid{display:grid;gap:20px;margin-bottom:30px;grid-template-columns:repeat(auto-fit,minmax(300px,1fr))}
.meeting-card{background:white;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);padding:25px;transition:all .3s ease;position:relative;overflow:hidden}
.meeting-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:5px;background:linear-gradient(135deg,#00b894,#00cec9)}
.meeting-card:hover{transform:translateY(-5px);box-shadow:0 8px 25px rgba(0,0,0,.12)}
.meeting-header{display:flex;justify-content:space-between;align-items:start;margin-bottom:15px;flex-wrap:wrap;gap:10px}
.meeting-title{font-size:18px;font-weight:600;color:#333;margin-bottom:5px}
.meeting-subtitle{font-size:14px;color:#666}
.status-badge{padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
.status-scheduled{background:#fff3cd;color:#856404}
.status-completed{background:#d4edda;color:#155724}
.status-cancelled{background:#f8d7da;color:#721c24}
.meeting-details{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-top:15px}
.detail-item{display:flex;align-items:center;gap:10px}
.detail-icon{width:40px;height:40px;background:linear-gradient(135deg,#a8e6cf,#7fcdcd);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px}
.detail-text{flex:1}
.detail-label{font-size:12px;color:#999;text-transform:uppercase;letter-spacing:.5px}
.detail-value{font-size:14px;color:#333;font-weight:600;margin-top:2px}
.meeting-remarks{margin-top:15px;padding:15px;background:#f8f9fa;border-radius:8px;border-left:3px solid #00b894}
.meeting-remarks h4{font-size:13px;color:#666;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px}
.meeting-remarks p{font-size:14px;color:#333;line-height:1.6}
.no-meetings{background:white;padding:60px 20px;text-align:center;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08)}
.no-meetings-icon{font-size:64px;margin-bottom:20px;opacity:.3}
.no-meetings h3{color:#666;margin-bottom:10px}
.no-meetings p{color:#999}
.footer{text-align:center;padding:20px;color:white;font-size:14px}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📅 Meeting Dashboard</h1>
        <p>Welcome, <strong><?= htmlspecialchars($parent_name); ?></strong> | Child: <strong><?= htmlspecialchars($child_name); ?></strong></p>
    </div>

    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card"><div class="stat-number"><?= $total_count; ?></div><div class="stat-label">Total Meetings</div></div>
        <div class="stat-card"><div class="stat-number"><?= $scheduled_count; ?></div><div class="stat-label">Scheduled</div></div>
        <div class="stat-card"><div class="stat-number"><?= $completed_count; ?></div><div class="stat-label">Completed</div></div>
        <div class="stat-card"><div class="stat-number"><?= $cancelled_count; ?></div><div class="stat-label">Cancelled</div></div>
    </div>

    <!-- Filters -->
    <form class="filters" method="GET" action="">
        <div class="filter-group">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="all" <?= $status_filter == 'all' ? 'selected' : ''; ?>>All</option>
                <option value="scheduled" <?= $status_filter == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                <option value="completed" <?= $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?= $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" value="<?= htmlspecialchars($date_filter); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Apply Filters</button>
        <a href="?" class="btn btn-secondary">Clear Filters</a>
    </form>

    <!-- Meetings Grid -->
    <div class="meetings-grid">
        <?php if ($meetings_result->num_rows > 0): ?>
            <?php while ($meeting = $meetings_result->fetch_assoc()):
                $status_class = strtolower($meeting['status']);
            ?>
                <div class="meeting-card">
                    <div class="meeting-header">
                        <div>
                            <div class="meeting-title">Meeting with <?= htmlspecialchars($meeting['parent_name']); ?></div>
                            <div class="meeting-subtitle">Child: <?= htmlspecialchars($meeting['student_name']); ?></div>
                        </div>
                        <span class="status-badge status-<?= $status_class; ?>"><?= ucfirst($meeting['status']); ?></span>
                    </div>

                    <div class="meeting-details">
                        <div class="detail-item">
                            <div class="detail-icon">📅</div>
                            <div class="detail-text">
                                <div class="detail-label">Date</div>
                                <div class="detail-value"><?= date('M d, Y', strtotime($meeting['meeting_date'])); ?></div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">🕐</div>
                            <div class="detail-text">
                                <div class="detail-label">Time</div>
                                <div class="detail-value"><?= date('h:i A', strtotime($meeting['meeting_time'])); ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($meeting['remarks'])): ?>
                        <div class="meeting-remarks">
                            <h4>Remarks</h4>
                            <p><?= htmlspecialchars($meeting['remarks']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-meetings">
                <div class="no-meetings-icon">📭</div>
                <h3>No Meetings Found</h3>
                <p>There are no meetings scheduled for your child at this time.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>&copy; 2025 School Meeting System. All rights reserved.</p>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
