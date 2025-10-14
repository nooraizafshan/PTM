<?php
// Handle form submission (You can later connect to DB)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = $_POST['student'];
    $route = $_POST['route'];
    $bus_no = $_POST['bus_no'];
    $driver = $_POST['driver'];
    $pickup = $_POST['pickup'];

    echo "<script>alert('Transport details saved successfully for $student!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Student Transportation</title>
  <style>
   

    

    h1 {
      font-size: 22px;
      color: #3a4ca8;
      margin-bottom: 20px;
      text-transform: uppercase;
    }

    .card {
      text-align: left;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 6px;
      color: #333;
    }

    input,
    select {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    input:focus,
    select:focus {
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

    @media (max-width: 480px) {
      .container {
        width: 90%;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Manage Student Transport</h1>
    <div class="card">
      <form action="" method="post">
        <div class="form-group">
          <label for="student">Student Name</label>
          <select id="student" name="student" required>
            <option value="">Select Student</option>
            <option value="Ali Khan">Ali Khan</option>
            <option value="Sara Ahmed">Sara Ahmed</option>
            <option value="Hassan Raza">Hassan Raza</option>
          </select>
        </div>

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
          <label for="pickup">Pickup Point</label>
          <input type="text" id="pickup" name="pickup" placeholder="Enter pickup location" required>
        </div>

        <button type="submit" class="btn">Save Transport Details</button>
      </form>
    </div>
  </div>
</body>
</html>
