<?php
// modules/progress/generate_progress.php
// Generates and displays student progress reports with styled UI

// --- Include Database Connection ---
if (!isset($conn) || !($conn instanceof mysqli)) {
    $db_path = __DIR__ . '/../../config/db.php';
    if (file_exists($db_path)) {
        include_once $db_path;
        if (function_exists('dbConnect')) {
            $conn = dbConnect();
        }
    }
}

// --- Ensure Connection Exists ---
if (!isset($conn) || !($conn instanceof mysqli)) {
    echo '<div class="alert alert-danger" style="background:#ff7675;color:white;padding:12px;border-radius:8px;">❌ Database connection not available. Please check config/db.php</div>';
    return;
}

// --- Fetch Students for Dropdown ---
$students = [];
$res = $conn->query("SELECT student_id, student_name, class, roll_number FROM students ORDER BY class, roll_number");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $students[] = $r;
    }
    $res->free();
}

// --- Handle Filters ---
$filter_student = isset($_GET['student_id']) && $_GET['student_id'] !== '' ? intval($_GET['student_id']) : null;
$filter_term    = isset($_GET['term']) && $_GET['term'] !== '' ? $conn->real_escape_string($_GET['term']) : null;

// --- Build SQL Query ---
$sql = "
    SELECT 
        pr.*, 
        s.student_name AS student_name, 
        s.class AS student_class, 
        s.roll_number
    FROM progress_reports pr
    LEFT JOIN students s ON pr.student_id = s.student_id
";
$conditions = [];
if ($filter_student) {
    $conditions[] = "pr.student_id = " . $filter_student;
}
if ($filter_term) {
    $conditions[] = "pr.term = '" . $filter_term . "'";
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY s.class, s.roll_number, pr.subject";

// --- Execute Query ---
$result = $conn->query($sql);
?>

<!-- 🌿 Custom CSS -->
<style>
    .progress-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        padding: 24px;
        margin: 10px auto;
    }
    .progress-header {
        background: linear-gradient(90deg, #a8e6cf, #7fcdcd, #81c784);
        color: #004d40;
        padding: 14px 20px;
        border-radius: 12px;
        font-weight: bold;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    .progress-form {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-end;
        margin-bottom: 20px;
    }
    .progress-form label {
        font-weight: 500;
        color: #00695c;
    }
    .progress-form select, .progress-form input {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 8px 10px;
    }
    .btn-primary {
        background: #00b894;
        border: none;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        transition: background 0.3s;
    }
    .btn-primary:hover {
        background: #00cec9;
    }
    .btn-outline-secondary {
        border: 1px solid #00b894;
        color: #00b894;
        background: transparent;
        padding: 8px 14px;
        border-radius: 6px;
    }
    .btn-outline-secondary:hover {
        background: #a8e6cf;
        color: #00695c;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th {
        background: #a8e6cf;
        color: #004d40;
        padding: 10px;
        border-bottom: 2px solid #7fcdcd;
    }
    td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }
    tr:hover {
        background: #f1fef6;
    }
    .alert {
        padding: 10px;
        border-radius: 8px;
        margin: 10px 0;
    }
    .alert-info { background: #e0f7fa; color: #006064; }
    .alert-warning { background: #fff3cd; color: #856404; }
</style>

<div class="progress-card">
    <div class="progress-header">📁 Generated Progress Reports</div>

    <form method="get" class="progress-form">
        <input type="hidden" name="page" value="generate-progress">

        <div>
            <label class="form-label">Student</label><br>
            <select name="student_id" class="form-select">
                <option value="">-- All students --</option>
                <?php foreach ($students as $st): ?>
                    <option value="<?= (int)$st['student_id'] ?>" <?= ($filter_student && $filter_student == $st['student_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($st['student_name'] . " (Class: {$st['class']}, Roll: {$st['roll_number']})") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label">Term</label><br>
            <select name="term" class="form-select">
                <option value="">-- All terms --</option>
                <option value="Term 1" <?= ($filter_term === 'Term 1') ? 'selected' : '' ?>>Term 1</option>
                <option value="Term 2" <?= ($filter_term === 'Term 2') ? 'selected' : '' ?>>Term 2</option>
                <option value="Midterm" <?= ($filter_term === 'Midterm') ? 'selected' : '' ?>>Midterm</option>
                <option value="Final" <?= ($filter_term === 'Final') ? 'selected' : '' ?>>Final</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn-primary">Apply Filters</button>
        </div>

        <div style="margin-left:auto;">
            <a href="teacher_dashboard.php?page=generate-progress" class="btn-outline-secondary">Reset</a>
        </div>
    </form>

    <?php if (!$result): ?>
        <div class="alert alert-warning">⚠️ Error running query: <?= htmlspecialchars($conn->error) ?></div>
    <?php else: ?>
        <?php if ($result->num_rows === 0): ?>
            <div class="alert alert-info">ℹ️ No progress reports found for the selected filters.</div>
        <?php else: ?>
            <div style="overflow:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Class / Roll</th>
                            <th>Term</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Total</th>
                            <th>%</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                            <th>Teacher</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_name'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars(($row['student_class'] ?? '-') . ' / ' . ($row['roll_number'] ?? '-')) ?></td>
                                <td><?= htmlspecialchars($row['term']) ?></td>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td><?= htmlspecialchars($row['marks_obtained']) ?></td>
                                <td><?= htmlspecialchars($row['total_marks']) ?></td>
                                <td><?= is_numeric($row['percentage']) ? number_format($row['percentage'], 2) : htmlspecialchars($row['percentage']) ?></td>
                                <td><?= htmlspecialchars($row['grade']) ?></td>
                                <td><?= htmlspecialchars($row['remarks']) ?></td>
                                <td><?= htmlspecialchars($row['teacher_name']) ?></td>
                                <td><?= htmlspecialchars($row['created_at'] ?? '-') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php $result->free(); ?>
    <?php endif; ?>
</div>

<?php
// Optional: Close DB connection if not reused
// $conn->close();
?>
