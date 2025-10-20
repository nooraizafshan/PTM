<?php
require_once __DIR__ . '/../../config/db.php'; // ✅ include DB connection

$conn = dbConnect(); // your DB connection function

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $route = $_POST['route'];
    $bus_no = $_POST['bus_no'];
    $driver = $_POST['driver'];
    $driver_contact = $_POST['driver_contact'];
    $pickup = $_POST['pickup'];
    $pickup_time = $_POST['pickup_time'];

    $stmt = $conn->prepare("
        INSERT INTO transport_management (route, bus_no, driver_name, driver_contact, pickup_point, pickup_time)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssss", $route, $bus_no, $driver, $driver_contact, $pickup, $pickup_time);

    if ($stmt->execute()) {
        echo "<script>alert('Transport details saved successfully!');</script>";
    } else {
        echo "<script>alert('Error saving transport details: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// ✅ Fetch all transport records
$result = $conn->query("SELECT * FROM transport_management ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Student Transportation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f6fa;
    }
    h1 {
      font-size: 22px;
      color: #3a4ca8;
      margin-bottom: 20px;
      text-transform: uppercase;
      text-align: center;
    }
    .container {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      width: 400px;
      margin: 40px auto;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      color: #333;
    }
    input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
    }
    input:focus {
      border-color: #3a4ca8;
      outline: none;
      box-shadow: 0 0 5px rgba(58, 76, 168, 0.2);
    }
    .btn {
      width: 100%;
      background: #3a4ca8;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background: #2d3a8b;
    }

    /* ✅ Table Styles */
    table {
      width: 90%;
      margin: 30px auto;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background: #3a4ca8;
      color: white;
      text-transform: uppercase;
      font-size: 13px;
    }
    tr:hover {
      background: #f1f2f6;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Manage Transport</h1>
    <form action="" method="post">

      <div class="form-group">
        <label for="route">Route</label>
        <input type="text" id="route" name="route" placeholder="Enter route (e.g. City Center to UOG)" required>
      </div>

      <div class="form-group">
        <label for="bus_no">Bus Number</label>
        <input type="text" id="bus_no" name="bus_no" placeholder="Enter bus number" required>
      </div>

      <div class="form-group">
        <label for="driver">Driver Name</label>
        <input type="text" id="driver" name="driver" placeholder="Enter driver name" required>
      </div>

      <div class="form-group">
        <label for="driver_contact">Driver Contact</label>
        <input type="text" id="driver_contact" name="driver_contact" placeholder="Enter driver contact (e.g. 03001234567)">
      </div>

      <div class="form-group">
        <label for="pickup">Pickup Point</label>
        <input type="text" id="pickup" name="pickup" placeholder="Enter pickup location" required>
      </div>

      <div class="form-group">
        <label for="pickup_time">Pickup Time</label>
        <input type="time" id="pickup_time" name="pickup_time">
      </div>

      <button type="submit" class="btn">Save Transport Details</button>
    </form>
  </div>

  <!-- ✅ Display Transport Records Table -->
  <h1>Transport Details</h1>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Route</th>
        <th>Bus No</th>
        <th>Driver</th>
        <th>Contact</th>
        <th>Pickup</th>
        <th>Time</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['route']) ?></td>
            <td><?= htmlspecialchars($row['bus_no']) ?></td>
            <td><?= htmlspecialchars($row['driver_name']) ?></td>
            <td><?= htmlspecialchars($row['driver_contact']) ?></td>
            <td><?= htmlspecialchars($row['pickup_point']) ?></td>
            <td><?= htmlspecialchars($row['pickup_time']) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align:center;">No transport records found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
