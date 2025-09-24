<?php
session_start();

// Configuration
$site_title = "Parent Login - EduConnect";
$db_config = [
    'host' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database'
];

// Initialize variables
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember']);
    
    // Validation
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Database connection and authentication would go here
        // For demo purposes, we'll use dummy credentials
        if (authenticateUser($email, $password)) {
            $_SESSION['user_id'] = getUserId($email);
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'parent';
            $_SESSION['login_time'] = time();
            
            if ($remember_me) {
                // Set remember me cookie (expires in 30 days)
                setcookie('remember_token', generateRememberToken(), time() + (30 * 24 * 60 * 60), '/');
            }
            
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = 'Invalid email or password. Please try again.';
        }
    }
}

// Dummy authentication function (replace with real database logic)
function authenticateUser($email, $password) {
    // This should connect to your database and verify credentials
    // For demo: admin@example.com / password123
    return ($email === 'admin@example.com' && $password === 'password123');
}

function getUserId($email) {
    // This should fetch user ID from database
    return 1;
}

function generateRememberToken() {
    return bin2hex(random_bytes(32));
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
            padding: 20px;
        }

        .login-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            min-height: 600px;
        }

        .login-left {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            padding: 60px 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .parent-illustration {
            width: 200px;
            height: 200px;
            background: #f8f9fa;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .parent-figure {
            position: relative;
        }

        .parent-head {
            width: 40px;
            height: 40px;
            background: #fdbcb4;
            border-radius: 50%;
            position: relative;
            margin: 0 auto 5px;
        }

        .parent-hair {
            width: 35px;
            height: 20px;
            background: #8b4513;
            border-radius: 20px 20px 0 0;
            position: absolute;
            top: -10px;
            left: 2.5px;
        }

        .parent-body {
            width: 50px;
            height: 60px;
            background: #4a90e2;
            border-radius: 25px 25px 0 0;
            position: relative;
        }

        .child-figure {
            position: absolute;
            right: -20px;
            top: 20px;
        }

        .child-head {
            width: 25px;
            height: 25px;
            background: #fdbcb4;
            border-radius: 50%;
            position: relative;
            margin-bottom: 3px;
        }

        .child-hair {
            width: 20px;
            height: 12px;
            background: #654321;
            border-radius: 10px 10px 0 0;
            position: absolute;
            top: -6px;
            left: 2.5px;
        }

        .child-body {
            width: 30px;
            height: 35px;
            background: #ff6b6b;
            border-radius: 15px 15px 0 0;
        }

        .book {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 30px;
            background: #74b9ff;
            border-radius: 3px;
        }

        .login-left h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .login-left p {
            font-size: 16px;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .features {
            list-style: none;
            text-align: left;
        }

        .features li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .features li i {
            margin-right: 15px;
            width: 20px;
            font-size: 16px;
        }

        .login-right {
            padding: 60px 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .user-icon {
            width: 50px;
            height: 50px;
            background: #00b894;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .user-icon i {
            color: white;
            font-size: 20px;
        }

        .login-right h3 {
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
            margin-bottom: 35px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 500;
            font-size: 14px;
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
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
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

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        .checkbox-wrapper input[type="checkbox"] {
            margin-right: 8px;
            accent-color: #00b894;
        }

        .checkbox-wrapper label {
            font-size: 14px;
            color: #636e72;
            margin: 0;
        }

        .forgot-password {
            color: #74b9ff;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
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
            margin-bottom: 25px;
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

        .signup-link {
            text-align: center;
            font-size: 14px;
            color: #636e72;
        }

        .signup-link a {
            color: #74b9ff;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .access-portal {
            text-align: center;
            margin-top: 20px;
        }

        .portal-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #e8f5e8;
            color: #00b894;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .portal-link:hover {
            background: #d4edda;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 10px;
            }
            
            .login-left, .login-right {
                width: 100%;
                padding: 40px 30px;
            }
            
            .login-left {
                min-height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left side -->
        <div class="login-left">
            <div class="parent-illustration">
                <div class="parent-figure">
                    <div class="parent-head">
                        <div class="parent-hair"></div>
                    </div>
                    <div class="parent-body"></div>
                </div>
                <div class="child-figure">
                    <div class="child-head">
                        <div class="child-hair"></div>
                    </div>
                    <div class="child-body"></div>
                </div>
                <div class="book"></div>
            </div>
            
            <h2>Welcome Back, Parents!</h2>
            <p>Stay connected with your child's educational journey. Access real-time updates, communicate with teachers, and support your child's growth.</p>
            
            <ul class="features">
                <li><i class="fas fa-comments"></i> Direct teacher communication</li>
                <li><i class="fas fa-chart-line"></i> Track academic progress</li>
                <li><i class="fas fa-bell"></i> Instant notifications</li>
            </ul>
        </div>

        <!-- Right side -->
        <div class="login-right">
            <div class="user-icon">
                <i class="fas fa-user"></i>
            </div>
            
            <h3>Parent Login</h3>
            <p class="subtitle">Access your parent dashboard</p>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="loginForm">
                <!-- CSRF Protection -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Enter your email"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper password-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Enter your password"
                               required>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="form-row">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember" value="1">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to Dashboard
                </button>

                <div class="divider">
                    <span>or</span>
                </div>

                <button type="button" class="btn btn-google" onclick="googleLogin()">
                    <div class="google-icon"></div>
                    Continue with Google
                </button>

                <div class="signup-link">
                    Don't have an account? <a href="signup.php">Create Account</a>
                </div>
            </form>

            <div class="access-portal">
                <a href="parent-portal.php" class="portal-link">
                    <i class="fas fa-external-link-alt"></i>
                    Parent Access Portal
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function googleLogin() {
            // Implement Google OAuth login
            alert('Google login functionality would be implemented here');
            // window.location.href = 'google-auth.php';
        }

        // Form validation and submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const loginBtn = document.getElementById('loginBtn');
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return;
            }
            
            // Show loading state
            const originalText = loginBtn.innerHTML;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            loginBtn.disabled = true;
            
            // Allow form to submit normally
            // The loading state will be reset on page reload
        });

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>