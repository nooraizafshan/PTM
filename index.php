<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent-Teacher Communication System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        nav {
            position: fixed;
            width: 100%;
            top: 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 15px 0;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 700;
            color: #00b894;
        }

        .logo i {
            font-size: 28px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #2d3436;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #00b894;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-login {
            padding: 10px 25px;
            background: transparent;
            color: #00b894;
            border: 2px solid #00b894;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #00b894;
            color: white;
        }

        .btn-signup {
            padding: 10px 25px;
            background: #00b894;
            color: white;
            border: 2px solid #00b894;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-signup:hover {
            background: #00a085;
            border-color: #00a085;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
        }

        /* Hero Section */
        .hero-section {
            margin-top: 70px;
            background: linear-gradient(135deg, #a8e6cf 0%, #7fcdcd 50%, #81c784 100%);
            padding: 100px 20px;
            text-align: center;
            color: white;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-content h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-hero-primary {
            background: white;
            color: #00b894;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: #00b894;
        }

        /* Features Section */
        .features-section {
            padding: 80px 20px;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 15px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 18px;
            color: #636e72;
            margin-bottom: 60px;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 184, 148, 0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00b894, #00cec9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .feature-icon i {
            font-size: 35px;
            color: white;
        }

        .feature-card h3 {
            font-size: 22px;
            color: #2d3436;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #636e72;
            line-height: 1.6;
        }

        /* Benefits Section */
        .benefits-section {
            padding: 80px 20px;
            background: white;
        }

        .benefits-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .benefits-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .benefits-image i {
            font-size: 120px;
            color: rgba(255, 255, 255, 0.3);
        }

        .benefits-list {
            list-style: none;
        }

        .benefits-list li {
            display: flex;
            align-items: start;
            gap: 15px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .benefits-list li:hover {
            background: #e8f5e8;
            transform: translateX(10px);
        }

        .benefit-icon {
            width: 40px;
            height: 40px;
            background: #00b894;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .benefit-icon i {
            color: white;
            font-size: 18px;
        }

        .benefit-text h4 {
            color: #2d3436;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .benefit-text p {
            color: #636e72;
            font-size: 14px;
            line-height: 1.5;
        }

        /* CTA Section */
        .cta-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            text-align: center;
            color: white;
        }

        .cta-section h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        /* Footer */
        footer {
            background: #2d3436;
            color: white;
            padding: 40px 20px 20px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 30px;
        }

        .footer-section h3 {
            margin-bottom: 20px;
            color: #00b894;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section a {
            color: #b2bec3;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #00b894;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #636e72;
            color: #b2bec3;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-content h1 {
                font-size: 32px;
            }

            .benefits-container {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>EduConnect</span>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#benefits">Benefits</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                <button class="btn-login" onclick="window.location.href='modules/auth/login.php'">Login</button>
                <button class="btn-signup" onclick="window.location.href='modules/auth/signup.php'">Sign Up</button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section" id="home">
        <div class="hero-content">
            <h1>Connecting Parents & Teachers for Student Success</h1>
            <p>A digital platform for seamless communication, real-time progress tracking, and collaborative student development</p>
            <div class="hero-buttons">
                <button class="btn-hero btn-hero-primary" onclick="window.location.href='modules/auth/signup.php'">Get Started</button>
                <button class="btn-hero btn-hero-secondary" onclick="window.location.href='#features'">Learn More</button>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section" id="features">
        <h2 class="section-title">Our Features</h2>
        <p class="section-subtitle">Everything you need for effective parent-teacher communication</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Progress Reports</h3>
                <p>View detailed academic progress with subject-wise grades, performance trends, and teacher remarks in real-time</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Attendance Tracking</h3>
                <p>Monitor student attendance with daily updates, monthly reports, and percentage calculations</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Meeting Scheduling</h3>
                <p>Schedule parent-teacher meetings easily with calendar integration and automated reminders</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bus"></i>
                </div>
                <h3>Transportation Details</h3>
                <p>Access pickup and drop-off schedules, driver information, and route details for student safety</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>Real-Time Feedback</h3>
                <p>Receive instant updates about student behavior, performance, and classroom activities</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Secure & Private</h3>
                <p>End-to-end encryption ensures all communication remains confidential and secure</p>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="benefits-section" id="benefits">
        <h2 class="section-title">Why Choose EduConnect?</h2>
        <p class="section-subtitle">Benefits that make a difference in student development</p>
        
        <div class="benefits-container">
            <div class="benefits-image">
                <i class="fas fa-users"></i>
            </div>
            <ul class="benefits-list">
                <li>
                    <div class="benefit-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Fast Communication</h4>
                        <p>Instant messaging between parents and teachers eliminates communication delays</p>
                    </div>
                </li>
                <li>
                    <div class="benefit-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Real-Time Updates</h4>
                        <p>Get immediate access to attendance records and academic progress reports</p>
                    </div>
                </li>
                <li>
                    <div class="benefit-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Privacy Protected</h4>
                        <p>Secure platform that maintains confidentiality and prevents unnecessary interference</p>
                    </div>
                </li>
                <li>
                    <div class="benefit-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="benefit-text">
                        <h4>Improved Performance</h4>
                        <p>Regular feedback and timely discussions lead to better student outcomes</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Ready to Transform Education?</h2>
        <p>Join thousands of parents and teachers already using EduConnect</p>
        <button class="btn-hero btn-hero-primary" onclick="window.location.href='modules/auth/signup.php'">Start Your Journey Today</button>
    </div>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About EduConnect</h3>
                <p style="color: #b2bec3; line-height: 1.6;">A comprehensive parent-teacher communication platform designed to support student growth and development through seamless digital collaboration.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#benefits">Benefits</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li><i class="fas fa-envelope"></i> support@educonnect.com</li>
                    <li><i class="fas fa-phone"></i> +92 300 1234567</li>
                    <li><i class="fas fa-map-marker-alt"></i> Lahore, Pakistan</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 EduConnect - Parents-Teacher Communication System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>