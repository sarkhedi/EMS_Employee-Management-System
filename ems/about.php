<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Employee Management System</title>
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

        /* Page Container */
        .page-container {
            padding-top: 100px;
            position: relative;
            z-index: 100;
        }

        /* Hero Section */
        .about-hero {
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

        /* Content Sections */
        .content-section {
            padding: 80px 0;
            position: relative;
            z-index: 100;
        }

        .section-container {
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

        .section-container.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .section-title {
            color: white;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #ff6b6b, #ffa500);
            border-radius: 2px;
        }

        .section-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: center;
            max-width: 800px;
            margin: 0 auto 40px;
        }

        /* Mission & Vision Cards */
        .mission-vision {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .mv-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        .mv-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: all 0.8s ease;
        }

        .mv-card:hover::before {
            left: 100%;
        }

        .mv-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .mv-icon {
            font-size: 3rem;
            color: #ff6b6b;
            margin-bottom: 20px;
            animation: iconPulse 2s infinite;
        }

        .mv-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .mv-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Team Section */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .team-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            transition: all 0.5s ease;
            opacity: 0;
            transform: translateY(30px);
        }

        .team-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .team-card:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .team-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }

        .team-name {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .team-role {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .team-bio {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* Values Section */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 50px;
        }

        .value-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px 25px;
            text-align: center;
            transition: all 0.5s ease;
        }

        .value-item:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.15);
        }

        .value-icon {
            font-size: 2.5rem;
            color: #ff6b6b;
            margin-bottom: 20px;
        }

        .value-title {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .value-desc {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Statistics */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .stat-box {
            text-align: center;
            padding: 25px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ff6b6b;
            margin-bottom: 10px;
            text-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .stat-label {
            color: white;
            font-size: 1rem;
            font-weight: 500;
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
            .section-title { font-size: 1.8rem; }
            .hero-content, .section-container { padding: 40px 25px; margin: 0 15px; }
            .mission-vision, .team-grid, .values-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 576px) {
            .hero-title { font-size: 1.8rem; }
            .section-title { font-size: 1.5rem; }
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
                        <a class="nav-link active" href="about.php">
                            <i class="fas fa-info-circle"></i> About
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
        <section class="about-hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        <i class="fas fa-building"></i> About Our Company
                    </h1>
                    <p class="hero-subtitle">
                        Leading the future of workforce management with innovative technology and exceptional service.
                    </p>
                </div>
            </div>
        </section>

        <!-- Company Story Section -->
        <section class="content-section">
            <div class="container">
                <div class="section-container">
                    <h2 class="section-title">Our Story</h2>
                    <p class="section-text">
                        Founded in 2020, Employee Management System (EMS) was born from a simple vision: to revolutionize how businesses manage their most valuable asset - their people. 
                        We recognized that traditional HR processes were outdated, inefficient, and failed to meet the needs of modern organizations.
                    </p>
                    <p class="section-text">
                        Our journey began when our founders, experienced HR professionals and software engineers, came together to create a solution that would streamline employee management, 
                        reduce administrative burden, and empower organizations to focus on what matters most - their people and growth.
                    </p>

                    <!-- Mission & Vision -->
                    <div class="mission-vision">
                        <div class="mv-card">
                            <div class="mv-icon">
                                <i class="fas fa-target"></i>
                            </div>
                            <h3 class="mv-title">Our Mission</h3>
                            <p class="mv-text">
                                To empower organizations worldwide with intelligent, user-friendly HR management solutions that enhance productivity, 
                                improve employee satisfaction, and drive business success through seamless workforce management.
                            </p>
                        </div>
                        <div class="mv-card">
                            <div class="mv-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="mv-title">Our Vision</h3>
                            <p class="mv-text">
                                To become the global leader in HR technology, creating a world where every organization can effortlessly manage their workforce, 
                                foster growth, and build thriving workplace cultures through innovative digital solutions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
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
                    const delay = entry.target.dataset.delay || 0;
                    setTimeout(() => {
                        entry.target.classList.add('animate');
                    }, delay);
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.section-container, .team-card').forEach(el => {
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
