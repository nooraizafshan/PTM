<?php
session_start();
include '../../config/db.php';
$conn = dbConnect();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $remember = isset($_POST['remember']);

    // Validation
    if (empty($email) || empty($password) || empty($role)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // ✅ Admin Manual Login (no DB check)
        if ($role === 'admin') {
            $admin_email = 'admin@educonnect.com';
            $admin_password = 'admin123'; // you can change it anytime

            if ($email === $admin_email && $password === $admin_password) {
                $_SESSION['user_id'] = '0';
                $_SESSION['username'] = 'Admin User';
                $_SESSION['email'] = $admin_email;
                $_SESSION['role'] = 'admin';
                $_SESSION['login_time'] = time();

                if ($remember) {
                    setcookie('remember_token', bin2hex(random_bytes(32)), time() + (30 * 24 * 60 * 60), '/');
                }

                header("Location: ../../admin_dashboard.php");
                exit();
            } else {
                $error = "Invalid admin credentials!";
            }

        } else {
            // ✅ For Parent or Teacher, still use DB
            $email = mysqli_real_escape_string($conn, $email);
            $query = "SELECT * FROM users WHERE email='$email' AND role='$role'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['login_time'] = time();

                    if ($remember) {
                        setcookie('remember_token', bin2hex(random_bytes(32)), time() + (30 * 24 * 60 * 60), '/');
                    }

                    // Redirect by role
                    if ($role == 'parent') {
                        header("Location: ../../parent_dashboard.php");
                    } elseif ($role == 'teacher') {
                        header("Location: ../../teacher_dashboard.php");
                    } else {
                        $error = "Invalid role selected!";
                    }
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                $error = "No user found with this email and role.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduConnect</title>
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
            padding: 20px;
        }

        .login-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 950px;
            width: 100%;
            min-height: 600px;
        }

        /* Left Side Illustration */
        .login-left {
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
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            position: relative;
        }

        .illustration i {
            font-size: 80px;
            color: white;
            opacity: 0.9;
        }

        .login-left h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .login-left p {
            font-size: 15px;
            margin-bottom: 30px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .features {
            list-style: none;
            text-align: left;
            width: 100%;
        }

        .features li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.15);
            padding: 12px 15px;
            border-radius: 8px;
        }

        .features li i {
            margin-right: 12px;
            font-size: 16px;
        }

        /* Right Side Form */
        .login-right {
            padding: 60px 50px;
            width: 55%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-header {
            text-align: center;
            margin-bottom: 30px;
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

        .login-right h3 {
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
            margin-bottom: 30px;
        }

        /* Alert Messages */
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

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 600;
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

        .form-control, .form-select {
            width: 100%;
            padding: 14px 15px 14px 45px;
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

        /* Form Row */
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
            width: 18px;
            height: 18px;
            accent-color: #00b894;
            cursor: pointer;
        }

        .checkbox-wrapper label {
            font-size: 14px;
            color: #636e72;
            margin: 0;
            cursor: pointer;
        }

        .forgot-password {
            color: #00b894;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Buttons */
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
            margin-bottom: 15px;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 184, 148, 0.4);
        }

        /* Links */
        .signup-link {
            text-align: center;
            font-size: 14px;
            color: #636e72;
            margin-top: 20px;
        }

        .signup-link a {
            color: #00b894;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
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

        /* Role Selection Styling */
        .role-icon {
            display: inline-block;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-left, .login-right {
                width: 100%;
                padding: 40px 30px;
            }
            
            .login-left {
                min-height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side -->
        <div class="login-left">
            <div class="illustration">
                <i class="fas fa-users-cog"></i>
            </div>
            <h2>Welcome to EduConnect</h2>
            <p>Seamless communication platform connecting parents and teachers for better student development</p>
            <ul class="features">
                <li><i class="fas fa-chart-line"></i> Track academic progress in real-time</li>
                <li><i class="fas fa-comments"></i> Direct communication channels</li>
                <li><i class="fas fa-calendar-check"></i> Easy meeting scheduling</li>
                <li><i class="fas fa-bell"></i> Instant notifications & updates</li>
            </ul>
        </div>
        <!-- Right Side -->
        <div class="login-right">
            <div class="logo-header">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Login to Your Account</h3>
                <p class="subtitle">Access your dashboard</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm">
                <!-- Role Selection -->
                <div class="form-group">
                    <label for="role"><i class="fas fa-user-tag"></i> Login As</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user-circle"></i>
                        <select class="form-select" name="role" id="role" required>
                            <option value="">-- Select Your Role --</option>
                            <option value="parent" <?php echo (isset($_POST['role']) && $_POST['role'] == 'parent') ? 'selected' : ''; ?>>
                                Parent
                            </option>
                            <option value="teacher" <?php echo (isset($_POST['role']) && $_POST['role'] == 'teacher') ? 'selected' : ''; ?>>
                                Teacher
                            </option>
                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>

                        </select>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               class="form-control" 
                               name="email" 
                               id="email"
                               placeholder="Enter your email" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               required>
                    </div>
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <div class="input-wrapper password-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               class="form-control" 
                               name="password" 
                               id="password"
                               placeholder="Enter your password" 
                               required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="form-row">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember" value="1">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to Dashboard
                </button>
                
                <!-- Signup Link -->
                <div class="signup-link">
                    Don't have an account? <a href="signup.php">Create Account</a>
                </div>

                <!-- Back to Home -->
                <div class="back-home">
                    <a href="../../index.php">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Form Validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const role = document.getElementById('role').value;
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const loginBtn = document.getElementById('loginBtn');
            
            if (!role) {
                e.preventDefault();
                alert('Please select your role (Parent or Teacher)');
                return;
            }
            
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
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            loginBtn.disabled = true;
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
                    alert.style.transition = 'opacity 0.3s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

       
        // Dynamic role change effect
       document.getElementById('role').addEventListener('change', function() {
    const illustration = document.querySelector('.illustration i');
    const leftPanel = document.querySelector('.login-left');

    if (this.value === 'parent') {
        illustration.className = 'fas fa-user-friends';
    } 
    else if (this.value === 'teacher') {
        illustration.className = 'fas fa-chalkboard-teacher';
    } 
    else if (this.value === 'admin') {
        illustration.className = 'fas fa-user-shield';
    } 
    else {
        illustration.className = 'fas fa-users-cog';
    }
});
</script>
</body>
</html>