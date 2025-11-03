<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"><animate attributeName="cy" values="20;80;20" dur="3s" repeatCount="indefinite"/></circle><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.2)"><animate attributeName="cy" values="50;10;50" dur="2s" repeatCount="indefinite"/></circle><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.15)"><animate attributeName="cy" values="30;90;30" dur="4s" repeatCount="indefinite"/></circle></svg>');
            pointer-events: none;
            z-index: 1;
        }

        /* Floating Elements */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }

        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
            color: white;
        }

        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
            font-size: 3rem;
        }

        .floating-element:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 2s;
            font-size: 2.5rem;
        }

        .floating-element:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
            font-size: 2rem;
        }

        .floating-element:nth-child(4) {
            top: 40%;
            right: 30%;
            animation-delay: 1s;
            font-size: 1.5rem;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px !important;
            border-radius: 8px;
            margin: 0 2px;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Special styling for Register button */
        .nav-register {
            background: linear-gradient(135deg, #4facfe, #00f2fe) !important;
            color: white !important;
            font-weight: 600;
            border-radius: 20px !important;
            padding: 8px 20px !important;
            margin-left: 10px;
        }

        .nav-register:hover {
            background: linear-gradient(135deg, #369afe, #00d4fe) !important;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }

        /* Special styling for Login button */
        .nav-login {
            background: linear-gradient(135deg, #ff6b6b, #ffa500) !important;
            color: white !important;
            font-weight: 600;
            border-radius: 20px !important;
            padding: 8px 20px !important;
        }

        .nav-login:hover {
            background: linear-gradient(135deg, #ff5252, #ff9500) !important;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 100;
            padding: 120px 0 60px;
        }

        .hero-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            animation: slideUp 1s ease-out;
            position: relative;
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            margin: 0 20px;
        }

        .hero-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 4s infinite;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            color: white;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            line-height: 1.2;
            animation: titleAnimation 1.5s ease-out;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.3rem;
            font-weight: 400;
            margin-bottom: 40px;
            line-height: 1.6;
            animation: subtitleAnimation 1.8s ease-out;
        }

        .hero-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            margin-bottom: 50px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: descriptionAnimation 2s ease-out;
        }

        /* Statistics Section with Animation */
        .stats-section {
            padding: 80px 0;
            position: relative;
            z-index: 100;
        }

        .stats-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 50px 30px;
            margin-bottom: 80px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }

        .stat-item.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #ff6b6b;
            text-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
            margin-bottom: 10px;
            animation: countUp 2s ease-out;
        }

        .stat-label {
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .btn-modern {
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 15px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-width: 180px;
            justify-content: center;
            animation: buttonPulse 2s ease-in-out infinite;
        }

        .btn-login {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }

        .btn-register {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
        }

        .btn-admin {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
        }

        .btn-employee {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
            color: white;
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.3);
        }

        .btn-logout {
            background: linear-gradient(135deg, #fa709a, #fee140);
            color: white;
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.3);
        }

        .btn-modern:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            color: white;
            animation: none;
        }

        /* Features Section with Enhanced Animation */
        .features-section {
            padding: 80px 0;
            position: relative;
            z-index: 100;
        }

        .section-title {
            text-align: center;
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 60px;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .section-title.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            height: 100%;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(50px) scale(0.9);
        }

        .feature-card.animate {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: all 0.8s ease;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }

        .feature-icon {
            font-size: 3.5rem;
            color: #ff6b6b;
            margin-bottom: 25px;
            text-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
            animation: iconBounce 2s ease-in-out infinite;
        }

        .feature-title {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .feature-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Status Card for Logged In Users */
        .status-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }

        .status-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .user-info {
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Footer */
        .footer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 60px 0 20px;
            position: relative;
            z-index: 100;
        }

        .footer-content {
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-section h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-section ul li a:hover {
            color: white;
            transform: translateX(5px);
            text-shadow: 0 0 10px rgba(255,255,255,0.3);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px) scale(1.1);
            color: #ff6b6b;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 40px;
            padding-top: 25px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .footer-logo {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        @keyframes titleAnimation {
            0% { opacity: 0; transform: translateY(30px) scale(0.9); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes subtitleAnimation {
            0% { opacity: 0; transform: translateX(-30px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        @keyframes descriptionAnimation {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes buttonPulse {
            0%, 100% { box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3); }
            50% { box-shadow: 0 12px 35px rgba(255, 107, 107, 0.5); }
        }

        @keyframes iconBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Scroll Progress Bar */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #ff6b6b, #ffa500);
            z-index: 9999;
            transition: width 0.1s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1.1rem; }
            .hero-container { padding: 40px 25px; margin: 0 15px; }
            .action-buttons { flex-direction: column; align-items: center; }
            .btn-modern { width: 100%; max-width: 280px; }
            .feature-card { margin-bottom: 20px; }
            .stat-number { font-size: 2.5rem; }
            .section-title { font-size: 2rem; }
            
            /* Mobile navigation adjustments */
            .nav-register, .nav-login {
                margin: 5px 0;
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .hero-title { font-size: 2rem; }
            .features-section { padding: 60px 0; }
            .stats-section { padding: 60px 0; }
        }
    </style>
</head>
<body>
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- Floating Elements -->
    <div class="floating-elements">
        <div class="floating-element"><i class="fas fa-users"></i></div>
        <div class="floating-element"><i class="fas fa-calendar-check"></i></div>
        <div class="floating-element"><i class="fas fa-chart-line"></i></div>
        <div class="floating-element"><i class="fas fa-cog"></i></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-users"></i> EMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: 1px solid rgba(255,255,255,0.3);">
                <span style="color: white;"><i class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Logged In User Navigation -->
                        <?php if($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">
                                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="employee/dashboard.php">
                                    <i class="fas fa-user-circle"></i> Employee Dashboard
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="fas fa-user"></i> Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Guest User Navigation with Registration
                        <li class="nav-item">
                            <a class="nav-link nav-register" href="register.php">
                                <i class="fas fa-user-plus"></i> Join Our Team
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link nav-login" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-rocket"></i>
                    Employee Management System
                </h1>
                <p class="hero-subtitle">
                    Smart. Efficient. Powerful.
                </p>
                <p class="hero-description">
                    A comprehensive solution for managing employees, attendance, leaves, and payroll. 
                    Built with modern technology to streamline your HR operations and boost productivity.
                </p>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="status-card fade-in">
                        <div class="status-text">You are logged in as</div>
                        <div class="user-info">
                            <i class="fas fa-<?= $_SESSION['role'] === 'admin' ? 'crown' : 'user' ?>"></i>
                            <?= ucfirst($_SESSION['role']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="action-buttons">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['role'] === 'admin'): ?>
                            <a href="admin/dashboard.php" class="btn-modern btn-admin">
                                <i class="fas fa-tachometer-alt"></i>
                                Admin Dashboard
                            </a>
                        <?php else: ?>
                            <a href="employee/dashboard.php" class="btn-modern btn-employee">
                                <i class="fas fa-user-circle"></i>
                                Employee Dashboard
                            </a>
                        <?php endif; ?>
                        <a href="logout.php" class="btn-modern btn-logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    <?php else: ?>
                        <!-- <a href="register.php" class="btn-modern btn-register">
                            <i class="fas fa-user-plus"></i>
                            Join Our Team
                        </a> -->
                        <a href="login.php" class="btn-modern btn-login">
                            <i class="fas fa-sign-in-alt"></i>
                            Login Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section
    <section class="stats-section">
        <div class="container">
            <div class="stats-container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-item" data-delay="0">
                            <div class="stat-number" data-target="500">0</div>
                            <div class="stat-label">Employees Managed</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-item" data-delay="200">
                            <div class="stat-number" data-target="99">0</div>
                            <div class="stat-label">Attendance Rate %</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-item" data-delay="400">
                            <div class="stat-number" data-target="24">0</div>
                            <div class="stat-label">Departments</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-item" data-delay="600">
                            <div class="stat-number" data-target="95">0</div>
                            <div class="stat-label">Satisfaction Score</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Powerful Features</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card" data-delay="0">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Employee Management</h3>
                        <p class="feature-description">
                            Comprehensive employee database with profile management, role assignments, and organizational hierarchy.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card" data-delay="200">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Attendance Tracking</h3>
                        <p class="feature-description">
                            Real-time attendance monitoring with automated reporting and analytics for better workforce management.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card" data-delay="400">
                        <div class="feature-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <h3 class="feature-title">Leave Management</h3>
                        <p class="feature-description">
                            Streamlined leave application process with approval workflows and balance tracking.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="feature-card" data-delay="600">
                        <div class="feature-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h3 class="feature-title">Payroll System</h3>
                        <p class="feature-description">
                            Automated salary calculations with allowances, deductions, and comprehensive payroll reports.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row footer-content">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <div class="footer-logo">
                            <i class="fas fa-users"></i> EMS
                        </div>
                        <p>Employee Management System - The most comprehensive solution for modern HR management. Streamline your workforce with cutting-edge technology.</p>
                        <div class="social-links">
                            <a href="#" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                            <?php if(!isset($_SESSION['user_id'])): ?>
                            <!-- <li><a href="register.php"><i class="fas fa-user-plus"></i> Join Our Team</a></li> -->
                            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>Features</h5>
                        <ul>
                            <li><a href="#"><i class="fas fa-users"></i> Employee Management</a></li>
                            <li><a href="#"><i class="fas fa-calendar-check"></i> Attendance System</a></li>
                            <li><a href="#"><i class="fas fa-plane"></i> Leave Management</a></li>
                            <li><a href="#"><i class="fas fa-money-bill-wave"></i> Payroll System</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>Contact Info</h5>
                        <ul>
                            <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Business Street, City</a></li>
                            <li><a href="#"><i class="fas fa-phone"></i> +91 98765 43210</a></li>
                            <li><a href="#"><i class="fas fa-envelope"></i> info@ems.com</a></li>
                            <li><a href="#"><i class="fas fa-clock"></i> Mon-Fri: 9AM-6PM</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Employee Management System. All rights reserved. | Designed with MU </p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll Progress Bar
        window.addEventListener('scroll', () => {
            const scrollTop = document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollProgress = (scrollTop / scrollHeight) * 100;
            document.getElementById('scrollProgress').style.width = scrollProgress + '%';
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Intersection Observer for Animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.dataset.delay || 0;
                    setTimeout(() => {
                        entry.target.classList.add('animate');
                    }, delay);
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.stat-item, .feature-card, .section-title').forEach(el => {
            observer.observe(el);
        });

        // Counter Animation
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 20);
        }

        // Trigger counters when visible
        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = parseInt(entry.target.dataset.target);
                    animateCounter(entry.target, target);
                    counterObserver.unobserve(entry.target);
                }
            });
        });

        document.querySelectorAll('.stat-number').forEach(counter => {
            counterObserver.observe(counter);
        });

        // Enhanced Button Hover Effects
        document.querySelectorAll('.btn-modern').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.05)';
                this.style.transition = 'all 0.3s ease';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Feature Card Parallax Effect
        document.addEventListener('mousemove', (e) => {
            const cards = document.querySelectorAll('.feature-card');
            const mouseX = e.clientX;
            const mouseY = e.clientY;

            cards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const cardX = rect.left + rect.width / 2;
                const cardY = rect.top + rect.height / 2;

                const diffX = mouseX - cardX;
                const diffY = mouseY - cardY;

                const distance = Math.sqrt(diffX * diffX + diffY * diffY);
                const maxDistance = 300;

                if (distance < maxDistance) {
                    const strength = (maxDistance - distance) / maxDistance;
                    const moveX = diffX * strength * 0.1;
                    const moveY = diffY * strength * 0.1;

                    card.style.transform = `translate(${moveX}px, ${moveY}px) scale(1.02)`;
                } else {
                    card.style.transform = 'translate(0px, 0px) scale(1)';
                }
            });
        });

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Loading Animation for page load
        window.addEventListener('load', () => {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
</body>
</html>