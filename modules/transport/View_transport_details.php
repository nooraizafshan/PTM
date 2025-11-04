<?php
// Include DB connection
require_once __DIR__ . '/../../config/db.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login (optional)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../modules/auth/login.php");
    exit();
}

// Connect to database
$conn = dbConnect();
if (!$conn || $conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch transport data
$query = "SELECT id, route, bus_no, driver_name, driver_contact, pickup_point, pickup_time 
          FROM transport_management
          ORDER BY route ASC";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Transport Details | EduConnect</title>
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f3f6fd;
  margin: 0;
  padding: 20px;
}
.container {
  max-width: 1000px;
  margin: 40px auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.1);
  padding: 25px;
}
h1 {
  text-align: center;
  color: #3a4ca8;
  margin-bottom: 25px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 12px 14px;
  border-bottom: 1px solid #eee;
  text-align: left;
}
th {
  background: #3a4ca8;
  color: #fff;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: .5px;
}
tr:nth-child(even) {
  background: #f9f9f9;
}
tr:hover {
  background: #eef2ff;
}
.no-data {
  text-align: center;
  padding: 25px;
  color: #888;
  font-size: 15px;
}
.footer {
  text-align: center;
  margin-top: 25px;
  color: #777;
  font-size: 13px;
}
</style>
</head>
<body>
<div class="container">
  <h1>🚌 Transport Details</h1>

  <?php if ($result && $result->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Route</th>
        <th>Bus No</th>
        <th>Driver</th>
        <th>Contact</th>
        <th>Pickup Point</th>
        <th>Pickup Time</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']); ?></td>
        <td><?= htmlspecialchars($row['route']); ?></td>
        <td><?= htmlspecialchars($row['bus_no']); ?></td>
        <td><?= htmlspecialchars($row['driver_name']); ?></td>
        <td><?= htmlspecialchars($row['driver_contact']); ?></td>
        <td><?= htmlspecialchars($row['pickup_point']); ?></td>
        <td><?= htmlspecialchars($row['pickup_time']); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <div class="no-data">No transport records found.</div>
  <?php endif; ?>

  <div class="footer">
    © <?= date('Y'); ?> EduConnect — All rights reserved.
  </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
