<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../../config/db.php';

$site_title = "Parent Signup - EduConnect";

$error_message = '';
$success_message = '';
$form_data = [];

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error_message = 'Security token mismatch. Please try again.';
    } else {
        // Sanitize and validate input
        $form_data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'phone' => trim($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'child_name' => trim($_POST['child_name'] ?? ''),
            'child_grade' => trim($_POST['child_grade'] ?? ''),
            'terms' => isset($_POST['terms'])
        ];
        
        // Validation
        $errors = [];
        
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
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $form_data['password'])) {
            $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number.';
        }
        
        if ($form_data['password'] !== $form_data['confirm_password']) {
            $errors[] = 'Passwords do not match.';
        }
        
        if (empty($form_data['child_name'])) {
            $errors[] = 'Child name is required.';
        }
        
        if (empty($form_data['child_grade'])) {
            $errors[] = 'Child grade is required.';
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
                $success_message = 'Account created successfully! Please check your email to verify your account.';
                // Clear form data on success
                $form_data = [];
            } else {
                $error_message = 'An error occurred while creating your account. Please try again.';
            }
        }
    }
}


function emailExists($email) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT id FROM parents WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

function createUser($data) {
    $conn = dbConnect();
    $stmt = $conn->prepare("INSERT INTO parents (first_name, last_name, email, phone, password, child_name, child_grade) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt->bind_param("sssssss", $data['first_name'], $data['last_name'], $data['email'], $data['phone'], $hashedPassword, $data['child_name'], $data['child_grade']);
    
    $success = $stmt->execute();
    if (!$success) {
        die("Query failed: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    return $success;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
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

        .welcome-illustration {
            width: 180px;
            height: 180px;
            background: #f8f9fa;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .family-icon {
            font-size: 80px;
            color: #00b894;
        }

        .signup-left h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .signup-left p {
            font-size: 16px;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .benefits {
            list-style: none;
            text-align: left;
        }

        .benefits li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .benefits li i {
            margin-right: 15px;
            width: 20px;
            font-size: 16px;
            color: #f2f4f6ff;
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

        .user-plus-icon {
            width: 50px;
            height: 50px;
            background: #00b894;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .user-plus-icon i {
            color: white;
            font-size: 20px;
        }

        .signup-right h3 {
            font-size: 24px;
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
            font-weight: 500;
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

        .form-group.full-width {
            flex: 1 1 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 500;
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

        .form-control {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #00b894;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.1);
        }

        .form-control.error {
            border-color: #e74c3c;
            background: #ffeaea;
        }

        select.form-control {
            padding-left: 40px;
            cursor: pointer;
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
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
        }

        .strength-weak { color: #e74c3c; }
        .strength-medium { color: #f39c12; }
        .strength-strong { color: #27ae60; }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 2px;
            accent-color: #00b894;
        }

        .checkbox-group label {
            font-size: 13px;
            color: #636e72;
            line-height: 1.4;
            margin: 0;
        }

        .checkbox-group a {
            color: #74b9ff;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
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
            background: #00b894;
            color: white;
            margin-bottom: 20px;
        }

        .btn-primary:hover:not(:disabled) {
            background: #00a085;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
            color: #b2bec3;
            font-size: 14px;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: 1;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            z-index: 2;
        }

        .btn-google {
            background: white;
            color: #636e72;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .btn-google:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .google-icon {
            width: 18px;
            height: 18px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%234285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="%2334A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="%23FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="%23EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>') no-repeat center;
            background-size: contain;
        }

        .login-link {
            text-align: center;
            font-size: 14px;
            color: #636e72;
        }

        .login-link a {
            color: #74b9ff;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .signup-container {
                flex-direction: column;
                margin: 10px;
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
        <!-- Left side -->
        <div class="signup-left">
            <div class="welcome-illustration">
                <i class="fas fa-users family-icon"></i>
            </div>
            
            <h2>Join Our Community!</h2>
            <p>Create your parent account to start connecting with your child's education and become part of our learning community.</p>
            
            <ul class="benefits">
                <li><i class="fas fa-graduation-cap"></i> Track academic progress</li>
                <li><i class="fas fa-calendar-alt"></i> Access school calendar</li>
                <li><i class="fas fa-comments"></i> Teacher communication</li>
                <li><i class="fas fa-bell"></i> Real-time notifications</li>
                <li><i class="fas fa-shield-alt"></i> Secure & private</li>
            </ul>
        </div>

        <!-- Right side -->
        <div class="signup-right">
            <div class="user-plus-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            
            <h3>Create Parent Account</h3>
            <p class="subtitle">Fill in your details to get started</p>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="signupForm">
                <!-- CSRF Protection -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <!-- Parent Information -->
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
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
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
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                        </div>
                    </div>
                </div>

                <!-- Child Information -->
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
                                   value="<?php echo htmlspecialchars($form_data['child_name'] ?? ''); ?>"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="child_grade">Grade/Class <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-graduation-cap"></i>
                            <select id="child_grade" name="child_grade" class="form-control" required>
                                <option value="">Select grade</option>
                                <option value="kindergarten" <?php echo ($form_data['child_grade'] ?? '') === 'kindergarten' ? 'selected' : ''; ?>>Kindergarten</option>
                                <option value="grade-1" <?php echo ($form_data['child_grade'] ?? '') === 'grade-1' ? 'selected' : ''; ?>>Grade 1</option>
                                <option value="grade-2" <?php echo ($form_data['child_grade'] ?? '') === 'grade-2' ? 'selected' : ''; ?>>Grade 2</option>
                                <option value="grade-3" <?php echo ($form_data['child_grade'] ?? '') === 'grade-3' ? 'selected' : ''; ?>>Grade 3</option>
                                <option value="grade-4" <?php echo ($form_data['child_grade'] ?? '') === 'grade-4' ? 'selected' : ''; ?>>Grade 4</option>
                                <option value="grade-5" <?php echo ($form_data['child_grade'] ?? '') === 'grade-5' ? 'selected' : ''; ?>>Grade 5</option>
                                <option value="grade-6" <?php echo ($form_data['child_grade'] ?? '') === 'grade-6' ? 'selected' : ''; ?>>Grade 6</option>
                                <option value="grade-7" <?php echo ($form_data['child_grade'] ?? '') === 'grade-7' ? 'selected' : ''; ?>>Grade 7</option>
                                <option value="grade-8" <?php echo ($form_data['child_grade'] ?? '') === 'grade-8' ? 'selected' : ''; ?>>Grade 8</option>
                                <option value="grade-9" <?php echo ($form_data['child_grade'] ?? '') === 'grade-9' ? 'selected' : ''; ?>>Grade 9</option>
                                <option value="grade-10" <?php echo ($form_data['child_grade'] ?? '') === 'grade-10' ? 'selected' : ''; ?>>Grade 10</option>
                                <option value="grade-11" <?php echo ($form_data['child_grade'] ?? '') === 'grade-11' ? 'selected' : ''; ?>>Grade 11</option>
                                <option value="grade-12" <?php echo ($form_data['child_grade'] ?? '') === 'grade-12' ? 'selected' : ''; ?>>Grade 12</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" value="1" <?php echo ($form_data['terms'] ?? false) ? 'checked' : ''; ?> required>
                    <label for="terms">
                        I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and 
                        <a href="privacy.php" target="_blank">Privacy Policy</a>. I understand that my information will be used to facilitate communication between parents and the school.
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" id="signupBtn">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>

                <div class="divider">
                    <span>or</span>
                </div>

                <button type="button" class="btn btn-google" onclick="googleSignup()">
                    <div class="google-icon"></div>
                    Sign up with Google
                </button>

                <div class="login-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>

   <script>
    // Form validation and submission
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        const requiredFields = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'password',
            'confirm_password',
            'child_name',
            'child_grade'
        ];
        const signupBtn = document.getElementById('signupBtn');
        let hasErrors = false;

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('error');
                hasErrors = true;
            } else {
                field.classList.remove('error');
            }
        });

        // Extra check: confirm password match
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            hasErrors = true;
        }

        if (hasErrors) {
            e.preventDefault(); // stop form from submitting
            return false;
        }

        // Disable button to prevent multiple submits
        signupBtn.disabled = true;
        signupBtn.textContent = "Creating Account...";
    });
</script>

</body>
</html>