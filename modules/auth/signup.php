<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../config/db.php';
$conn = dbConnect(); // ✅ create DB connection

$site_title = "Sign Up - EduConnect";
$error_message = '';
$success_message = '';
$form_data = [];

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error_message = 'Security token mismatch. Please try again.';
    } else {
        // Get role
        $role = $_POST['role'] ?? '';
        
        // Common fields
        $form_data = [
            'role' => $role,
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'phone' => trim($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'terms' => isset($_POST['terms'])
        ];
        
        // Role-specific fields
        if ($role === 'parent') {
            $form_data['child_name'] = trim($_POST['child_name'] ?? '');
            $form_data['child_grade'] = trim($_POST['child_grade'] ?? '');
        } elseif ($role === 'teacher') {
            $form_data['subject'] = trim($_POST['subject'] ?? '');
            $form_data['department'] = trim($_POST['department'] ?? '');
        }
        
        // Validation
        $errors = [];

        if (empty($role) || !in_array($role, ['parent', 'teacher'])) {
            $errors[] = 'Please select a valid role.';
        }

        if (empty($form_data['first_name'])) {
            $errors[] = 'First name is required.';
        }

        if (empty($form_data['last_name'])) {
            $errors[] = 'Last name is required.';
        }

        if (empty($form_data['email']) || !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (empty($form_data['phone']) || !preg_match('/^[\+]?[0-9\s\-\(\)]+$/', $form_data['phone'])) {
            $errors[] = 'Please enter a valid phone number.';
        }

        if (empty($form_data['password'])) {
            $errors[] = 'Password is required.';
        } elseif (strlen($form_data['password']) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        if ($form_data['password'] !== $form_data['confirm_password']) {
            $errors[] = 'Passwords do not match.';
        }

        // Role-specific validation
        if ($role === 'parent') {
            if (empty($form_data['child_name'])) {
                $errors[] = 'Child name is required.';
            }
            if (empty($form_data['child_grade'])) {
                $errors[] = 'Child grade is required.';
            }
        } elseif ($role === 'teacher') {
            if (empty($form_data['subject'])) {
                $errors[] = 'Subject is required.';
            }
            if (empty($form_data['department'])) {
                $errors[] = 'Department is required.';
            }
        }

        if (!$form_data['terms']) {
            $errors[] = 'Please accept the terms and conditions.';
        }

        // Check if email already exists
        if (empty($errors) && emailExists($form_data['email'])) {
            $errors[] = 'An account with this email already exists.';
        }

        if (!empty($errors)) {
            $error_message = implode('<br>', $errors);
        } else {
            // Create user account
            if (createUser($form_data)) {
                $success_message = 'Account created successfully! Redirecting to login...';
                // Clear form data
                $form_data = [];
                // Redirect after 2 seconds
                header("refresh:2;url=login.php");
            } else {
                $error_message = 'An error occurred while creating your account. Please try again.';
            }
        }
    }
}

/* -------------------------
   FUNCTIONS
------------------------- */

function emailExists($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

function createUser($data) {
    global $conn;
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, contact, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $full_name = $data['first_name'] . ' ' . $data['last_name'];
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $full_name, $data['email'], $hashedPassword, $data['role'], $data['phone']);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert into users: " . $stmt->error);
        }

        $user_id = $conn->insert_id;
        $stmt->close();

        // Insert role-specific data
        if ($data['role'] === 'parent') {
            $stmt = $conn->prepare("INSERT INTO parents (user_id, student_name, student_grade) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $data['child_name'], $data['child_grade']);
        } else {
            $stmt = $conn->prepare("INSERT INTO teachers (user_id, subject, department) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $data['subject'], $data['department']);
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert role data: " . $stmt->error);
        }

        $stmt->close();
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log($e->getMessage());
        return false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 50%, #81c784 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
        }

        .signup-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 700px;
            margin: 20px;
        }

        .signup-left {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            padding: 60px 40px;
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .illustration {
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .illustration i {
            font-size: 80px;
            color: white;
            opacity: 0.9;
        }

        .signup-left h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .signup-left p {
            font-size: 15px;
            margin-bottom: 30px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .benefits {
            list-style: none;
            text-align: left;
            width: 100%;
        }

        .benefits li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.15);
            padding: 12px 15px;
            border-radius: 8px;
        }

        .benefits li i {
            margin-right: 12px;
            font-size: 16px;
        }

        .signup-right {
            padding: 40px;
            width: 55%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            max-height: 700px;
        }

        .logo-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #00b894, #00cec9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .logo-icon i {
            color: white;
            font-size: 28px;
        }

        .signup-right h3 {
            font-size: 26px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            color: #2d3436;
        }

        .subtitle {
            text-align: center;
            color: #636e72;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fee;
            color: #c53030;
            border: 1px solid #fed7d7;
        }

        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border: 1px solid #c6f6d5;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 600;
            font-size: 14px;
        }

        .required {
            color: #e74c3c;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #b2bec3;
            font-size: 14px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23636e72' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #00b894;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #b2bec3;
            cursor: pointer;
            font-size: 16px;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #00b894;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 2px;
            width: 18px;
            height: 18px;
            accent-color: #00b894;
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: 13px;
            color: #636e72;
            line-height: 1.4;
            margin: 0;
            cursor: pointer;
        }

        .checkbox-group a {
            color: #00b894;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            margin-bottom: 20px;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 184, 148, 0.4);
        }

        .login-link {
            text-align: center;
            font-size: 14px;
            color: #636e72;
            margin-top: 15px;
        }

        .login-link a {
            color: #00b894;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .back-home {
            text-align: center;
            margin-top: 15px;
        }

        .back-home a {
            color: #636e72;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-home a:hover {
            color: #00b894;
        }

        /* Dynamic fields display */
        .dynamic-fields {
            display: none;
        }

        .dynamic-fields.active {
            display: block;
        }

        @media (max-width: 768px) {
            .signup-container {
                flex-direction: column;
            }
            
            .signup-left, .signup-right {
                width: 100%;
                padding: 30px 25px;
            }
            
            .signup-left {
                min-height: 200px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <!-- Left Side -->
        <div class="signup-left">
            <div class="illustration">
                <i class="fas fa-users-cog" id="leftIcon"></i>
            </div>
            
            <h2 id="leftTitle">Join Our Community!</h2>
            <p id="leftDesc">Create your account to connect with our educational platform</p>
            
            <ul class="benefits">
                <li><i class="fas fa-chart-line"></i> Track progress in real-time</li>
                <li><i class="fas fa-calendar-check"></i> Easy scheduling & management</li>
                <li><i class="fas fa-comments"></i> Seamless communication</li>
                <li><i class="fas fa-bell"></i> Instant notifications</li>
                <li><i class="fas fa-shield-alt"></i> Secure & private platform</li>
            </ul>
        </div>

        <!-- Right Side -->
        <div class="signup-right">
            <div class="logo-header">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Create Your Account</h3>
                <p class="subtitle">Fill in your details to get started</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($success_message); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="signupForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <!-- Role Selection -->
                <div class="form-group">
                    <label for="role"><i class="fas fa-user-tag"></i> Sign Up As <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <i class="fas fa-user-circle"></i>
                        <select class="form-select" name="role" id="role" required>
                            <option value="">-- Select Your Role --</option>
                            <option value="parent" <?php echo ($form_data['role'] ?? '') === 'parent' ? 'selected' : ''; ?>>Parent</option>
                            <option value="teacher" <?php echo ($form_data['role'] ?? '') === 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                        </select>
                    </div>
                </div>

                <!-- Common Fields -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   class="form-control" 
                                   placeholder="Enter first name"
                                   value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   class="form-control" 
                                   placeholder="Enter last name"
                                   value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Enter your email"
                               value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-control" 
                               placeholder="Enter your phone number"
                               value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>"
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <div class="input-wrapper password-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Create password"
                                   required>
                            <i class="fas fa-eye password-toggle" id="togglePassword1"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                        <div class="input-wrapper password-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   class="form-control" 
                                   placeholder="Confirm password"
                                   required>
                            <i class="fas fa-eye password-toggle" id="togglePassword2"></i>
                        </div>
                    </div>
                </div>

                <!-- Parent-specific Fields -->
                <div class="dynamic-fields" id="parentFields">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="child_name">Child Name <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-child"></i>
                                <input type="text" 
                                       id="child_name" 
                                       name="child_name" 
                                       class="form-control" 
                                       placeholder="Enter child's name"
                                       value="<?php echo htmlspecialchars($form_data['child_name'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="child_grade">Grade/Class <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-graduation-cap"></i>
                                <select id="child_grade" name="child_grade" class="form-control">
                                    <option value="">Select grade</option>
                                    <option value="kindergarten">Kindergarten</option>
                                    <option value="grade-1">Grade 1</option>
                                    <option value="grade-2">Grade 2</option>
                                    <option value="grade-3">Grade 3</option>
                                    <option value="grade-4">Grade 4</option>
                                    <option value="grade-5">Grade 5</option>
                                    <option value="grade-6">Grade 6</option>
                                    <option value="grade-7">Grade 7</option>
                                    <option value="grade-8">Grade 8</option>
                                    <option value="grade-9">Grade 9</option>
                                    <option value="grade-10">Grade 10</option>
                                    <option value="grade-11">Grade 11</option>
                                    <option value="grade-12">Grade 12</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teacher-specific Fields -->
                <div class="dynamic-fields" id="teacherFields">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="subject">Subject <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-book"></i>
                                <input type="text" 
                                       id="subject" 
                                       name="subject" 
                                       class="form-control" 
                                       placeholder="e.g., Mathematics, Science"
                                       value="<?php echo htmlspecialchars($form_data['subject'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="department">Department <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-building"></i>
                                <select id="department" name="department" class="form-control">
                                    <option value="">Select department</option>
                                    <option value="science">Science</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="english">English</option>
                                    <option value="social-studies">Social Studies</option>
                                    <option value="arts">Arts</option>
                                    <option value="physical-education">Physical Education</option>
                                    <option value="languages">Languages</option>
                                    <option value="technology">Technology</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" value="1" required>
                    <label for="terms">
                        I agree to the <a href="#" target="_blank">Terms of Service</a> and 
                        <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" id="signupBtn">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>

                <div class="login-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>

                <div class="back-home">
                    <a href="../../index.php">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Role selection handler
        const roleSelect = document.getElementById('role');
        const parentFields = document.getElementById('parentFields');
        const teacherFields = document.getElementById('teacherFields');
        const leftIcon = document.getElementById('leftIcon');
        const leftTitle = document.getElementById('leftTitle');
        const leftDesc = document.getElementById('leftDesc');

        roleSelect.addEventListener('change', function() {
            const role = this.value;
            
            // Hide all dynamic fields first
            parentFields.classList.remove('active');
            teacherFields.classList.remove('active');
            
            // Show relevant fields and update left side
            if (role === 'parent') {
                parentFields.classList.add('active');
                leftIcon.className = 'fas fa-user-friends';
                leftTitle.textContent = 'Welcome Parents!';
                leftDesc.textContent = 'Join us to stay connected with your child\'s educational journey';
                
                // Make parent fields required
                document.getElementById('child_name').required = true;
                document.getElementById('child_grade').required = true;
                document.getElementById('subject').required = false;
                document.getElementById('department').required = false;
                
            } else if (role === 'teacher') {
                teacherFields.classList.add('active');
                leftIcon.className = 'fas fa-chalkboard-teacher';
                leftTitle.textContent = 'Welcome Teachers!';
                leftDesc.textContent = 'Join our platform to connect with students and parents effectively';
                
                // Make teacher fields required
                document.getElementById('subject').required = true;
                document.getElementById('department').required = true;
                document.getElementById('child_name').required = false;
                document.getElementById('child_grade').required = false;
                
            } else {
                leftIcon.className = 'fas fa-users-cog';
                leftTitle.textContent = 'Join Our Community!';
                leftDesc.textContent = 'Create your account to connect with our educational platform';
            }
        });

        // Password toggle
        document.getElementById('togglePassword1').addEventListener('click', function() {
            togglePasswordVisibility('password', this);
        });

        document.getElementById('togglePassword2').addEventListener('click', function() {
            togglePasswordVisibility('confirm_password', this);
        });

        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type =input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const role = document.getElementById('role').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const signupBtn = document.getElementById('signupBtn');
            
            // Check if role is selected
            if (!role) {
                e.preventDefault();
                alert('Please select your role (Parent or Teacher)');
                return false;
            }
            
            // Password match validation
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            // Password strength validation
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
            
            // Role-specific validation
            if (role === 'parent') {
                const childName = document.getElementById('child_name').value.trim();
                const childGrade = document.getElementById('child_grade').value;
                
                if (!childName || !childGrade) {
                    e.preventDefault();
                    alert('Please fill in all child information fields!');
                    return false;
                }
            } else if (role === 'teacher') {
                const subject = document.getElementById('subject').value.trim();
                const department = document.getElementById('department').value;
                
                if (!subject || !department) {
                    e.preventDefault();
                    alert('Please fill in all teacher information fields!');
                    return false;
                }
            }
            
            // Check terms acceptance
            const terms = document.getElementById('terms').checked;
            if (!terms) {
                e.preventDefault();
                alert('Please accept the terms and conditions!');
                return false;
            }
            
            // Show loading state
            signupBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            signupBtn.disabled = true;
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

        // Email validation on blur
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#e74c3c';
                alert('Please enter a valid email address');
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });

        // Phone validation on input
        document.getElementById('phone').addEventListener('input', function() {
            // Allow only numbers, spaces, dashes, parentheses, and plus sign
            this.value = this.value.replace(/[^0-9\s\-\(\)\+]/g, '');
        });
    </script>
</body>
</html>



