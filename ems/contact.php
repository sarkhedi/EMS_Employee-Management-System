<?php
session_start();

$success = '';
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Name, Email, and Message are required fields!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
    } else {
        // Here you would typically save to database or send email
        // For demo purposes, we'll just show success message
        $success = "Thank you for contacting us! We'll get back to you soon.";
        
        // Clear form data on success
        $name = $email = $phone = $subject = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Employee Management System</title>
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
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        /* Page Container */
        .page-container {
            padding-top: 100px;
            position: relative;
            z-index: 100;
            min-height: 100vh;
        }

        /* Hero Section */
        .contact-hero {
            padding: 80px 0;
            text-align: center;
        }

        .hero-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 60px 40px;
            margin: 0 20px;
            animation: slideUp 1s ease-out;
            position: relative;
            overflow: hidden;
        }

        .hero-content::before {
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

        .hero-title {
            color: white;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        /* Main Content */
        .main-content {
            padding: 80px 0;
            position: relative;
            z-index: 100;
        }

        .content-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 50px 40px;
            margin-bottom: 40px;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }

        .content-container.animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Contact Form */
        .contact-form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            color: white;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 15px 20px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            width: 100%;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            outline: none;
            color: white;
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            border: none;
            border-radius: 12px;
            padding: 15px 40px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .btn-submit:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
            color: white;
        }

        .btn-submit:active {
            transform: translateY(-1px) scale(1.02);
        }

        /* Contact Info Cards */
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px 25px;
            text-align: center;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: all 0.8s ease;
        }

        .info-card:hover::before {
            left: 100%;
        }

        .info-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .info-icon {
            font-size: 2.5rem;
            color: #ff6b6b;
            margin-bottom: 20px;
            animation: iconPulse 2s infinite;
        }

        .info-title {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .info-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Alert Messages */
        .alert {
            border-radius: 12px;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        /* Map Section */
        .map-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px;
            margin-top: 40px;
            text-align: center;
        }

        .map-placeholder {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 80px 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
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

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .hero-content, .contact-form, .content-container { padding: 30px 25px; margin: 0 15px; }
            .contact-info { grid-template-columns: 1fr; }
        }

        @media (max-width: 576px) {
            .hero-title { font-size: 1.8rem; }
            .contact-info { gap: 20px; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-users"></i> EMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
                        <a class="nav-link active" href="contact.php">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-container">
        <!-- Hero Section -->
        <section class="contact-hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        <i class="fas fa-envelope"></i> Contact Us
                    </h1>
                    <p class="hero-subtitle">
                        Get in touch with our team. We're here to help and answer any questions you might have.
                    </p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="main-content">
            <div class="container">
                <!-- Contact Info Cards -->
                <div class="contact-info">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="info-title">Visit Our Office</h3>
                        <p class="info-text">
                            123 Business Street<br>
                            Technology Park<br>
                            Ahmedabad, Gujarat 380001
                        </p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3 class="info-title">Call Us</h3>
                        <p class="info-text">
                            Phone: +91 98765 43210<br>
                            Toll Free: 1800-123-4567<br>
                            Support: +91 79-1234-5678
                        </p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="info-title">Email Us</h3>
                        <p class="info-text">
                            General: info@ems.com<br>
                            Support: support@ems.com<br>
                            Sales: sales@ems.com
                        </p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="info-title">Working Hours</h3>
                        <p class="info-text">
                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 9:00 AM - 2:00 PM<br>
                            Sunday: Closed
                        </p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="content-container">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="contact-form">
                                <h2 style="color: white; text-align: center; margin-bottom: 30px; font-weight: 600;">
                                    Send us a Message
                                </h2>
                                
                                <?php if ($success): ?>
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> <?= $success ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($error): ?>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" id="contactForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Full Name *</label>
                                                <input type="text" 
                                                       name="name" 
                                                       class="form-control" 
                                                       placeholder="Enter your full name"
                                                       value="<?= htmlspecialchars($name ?? '') ?>" 
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email Address *</label>
                                                <input type="email" 
                                                       name="email" 
                                                       class="form-control" 
                                                       placeholder="Enter your email"
                                                       value="<?= htmlspecialchars($email ?? '') ?>" 
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <input type="tel" 
                                                       name="phone" 
                                                       class="form-control" 
                                                       placeholder="Enter your phone number"
                                                       value="<?= htmlspecialchars($phone ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Subject</label>
                                                <input type="text" 
                                                       name="subject" 
                                                       class="form-control" 
                                                       placeholder="Enter message subject"
                                                       value="<?= htmlspecialchars($subject ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Message *</label>
                                        <textarea name="message" 
                                                  class="form-control" 
                                                  placeholder="Enter your message here..." 
                                                  required><?= htmlspecialchars($message ?? '') ?></textarea>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn-submit">
                                            <i class="fas fa-paper-plane"></i> Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Section
                <div class="content-container">
                    <div class="map-section">
                        <h3 style="color: white; margin-bottom: 25px; font-weight: 600;">Find Us on Map</h3>
                        <div class="map-placeholder">
                            <i class="fas fa-map-marked-alt fa-3x mb-3"></i><br>
                            Interactive Map Coming Soon<br>
                            <small>123 Business Street, Technology Park, Ahmedabad</small>
                        </div>
                    </div>
                </div> -->
            </div>
        </section>
    </div>

    <!-- Footer (Same as other pages) -->
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
                            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
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
                <p>&copy; 2025 Employee Management System. All rights reserved. | Designed with MU</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
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
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.content-container').forEach(el => {
            observer.observe(el);
        });

        // Form Enhancement
        document.getElementById('contactForm').addEventListener('submit', function() {
            const submitBtn = this.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;
            
            // Re-enable after form submission (for demo purposes)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Enhanced Form Validation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && this.value.trim() === '') {
                    this.style.borderColor = 'rgba(220, 53, 69, 0.5)';
                } else if (this.type === 'email' && this.value && !this.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    this.style.borderColor = 'rgba(220, 53, 69, 0.5)';
                } else {
                    this.style.borderColor = 'rgba(40, 167, 69, 0.5)';
                }
            });

            input.addEventListener('focus', function() {
                this.style.borderColor = 'rgba(255, 255, 255, 0.5)';
            });
        });

        // Loading Animation
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
