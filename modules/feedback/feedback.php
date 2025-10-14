<?php
// You can handle form submission here if needed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = $_POST['student'];
    $subject = $_POST['subject'];
    $feedback = $_POST['feedback'];

    // Example success message (you can later connect DB or mail)
    echo "<script>alert('Feedback sent successfully to parent of $student!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Feedback to Parents</title>
  <style>
    /* Global page styles */
 

    /* Container and card layout */
    .container {
      width: 400px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 25px 30px;
      text-align: center;
    }

    h1 {
      font-size: 22px;
      color: #3a4ca8;
      margin-bottom: 20px;
      text-transform: uppercase;
    }

    /* Card form */
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

    select,
    textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    select:focus,
    textarea:focus {
      border-color: #3a4ca8;
      outline: none;
      box-shadow: 0 0 5px rgba(58, 76, 168, 0.2);
    }

    /* Button styles */
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

    /* Responsive design */
    @media (max-width: 480px) {
      .container {
        width: 90%;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="">
    <h1>Send Feedback to Parents</h1>
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
          <label for="subject">Subject</label>
          <select id="subject" name="subject" required>
            <option value="">Select Subject</option>
            <option value="Math">Mathematics</option>
            <option value="Science">Science</option>
            <option value="English">English</option>
          </select>
        </div>

        <div class="form-group">
          <label for="feedback">Feedback Message</label>
          <textarea id="feedback" name="feedback" rows="5" placeholder="Write feedback for parent..." required></textarea>
        </div>

        <button type="submit" class="btn">Send Feedback</button>
      </form>
    </div>
  </div>
</body>
</html>
