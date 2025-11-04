<?php
require_once __DIR__ . '/../../config/db.php';

$conn = dbConnect();
if (!$conn) die("Database connection not available.");

$students = [];
$res = $conn->query("SELECT student_name FROM students WHERE status='active' ORDER BY student_name ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $students[] = $row['student_name'];
    }
} else {
    die("Failed to fetch students: " . $conn->error);
}

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = $_POST['student_name'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $feedback = $_POST['feedback'] ?? '';

    if (!$student_name || !$subject || !$feedback) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedbacks (student_name, subject, feedback) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $student_name, $subject, $feedback);
            if ($stmt->execute()) {
                $success = "Feedback sent successfully!";
            } else {
                $error = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Database prepare failed: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Send Feedback</title>
<style>
body {
    font-family: Arial;
    background: #f2f2f2;
    margin: 0;
    padding: 0;
}
.container {
    width: 100%;
    padding: 20px;
    background: #fff;
    box-sizing: border-box;
}
h1 {
    text-align: center;
    color: #3a4ca8;
    margin-bottom: 25px;
    font-size: 28px;
}
.form-group {
    margin-bottom: 15px;
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
}
input, select, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}
input:focus, select:focus, textarea:focus {
    border-color: #3a4ca8;
    outline: none;
    box-shadow: 0 0 5px rgba(58,76,168,0.2);
}
button {
    width: 100%;
    background: #3a4ca8;
    color: #fff;
    padding: 14px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}
button:hover {
    background: #2d3a8b;
}
.success {
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}
.error {
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="container">
    <h1>Send Feedback to Parents</h1>

    <?php if ($success) echo "<div class='success'>$success</div>"; ?>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <div class="form-group">
            <label for="student_name">Student Name</label>
            <select name="student_name" id="student_name" required>
                <option value="">Select Student</option>
                <?php foreach ($students as $name): ?>
                    <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="subject">Subject</label>
            <select name="subject" id="subject" required>
                <option value="">Select Subject</option>
                <option value="Math">Mathematics</option>
                <option value="Science">Science</option>
                <option value="English">English</option>
            </select>
        </div>

        <div class="form-group">
            <label for="feedback">Feedback Message</label>
            <textarea name="feedback" id="feedback" rows="5" placeholder="Write feedback..." required></textarea>
        </div>

        <button type="submit">Send Feedback</button>
    </form>
</div>

</body>
</html>
