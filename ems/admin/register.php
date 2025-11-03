<?php
session_start();
require 'config/db.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);

    // Validation
    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        $error = "Full name, email, username, and password are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
    } else {
        try {
            // Check if username or email already exists in users table
            $check_users = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check_users->execute([$username, $email]);
            
            // Check if username or email already exists in registrations table
            $check_registrations = $pdo->prepare("SELECT id FROM user_registrations WHERE username = ? OR email = ?");
            $check_registrations->execute([$username, $email]);
            
            if ($check_users->rowCount() > 0) {
                $error = "Username or email already exists in the system!";
            } elseif ($check_registrations->rowCount() > 0) {
                $error = "You have already applied with this username or email!";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert registration
                $stmt = $pdo->prepare("INSERT INTO user_registrations (full_name, email, phone, username, password, department, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$full_name, $email, $phone, $username, $hashed_password, $department, $position]);
                
                $success = "Registration submitted successfully! Your application is pending admin approval. You will be notified once approved.";
                
                // Clear form
                $full_name = $email = $phone = $username = $password = $confirm_password = $department = $position = '';
            }
        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Employee Management System</title>
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
            padding: 20px 0;
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
        }

        .register-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .register-header h1 {
            color: white;
            font-weight: 600;
            font-size: 2.2rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .register-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 300;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
            z-index: 1;
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
            padding: 12px 15px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            outline: none;
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-register {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            border-radius: 12px;
            padding: 15px;
            width: 100%;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
            color: white;
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: none;
            z-index: 1;
            position: relative;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            z-index: 1;
            position: relative;
        }

        .login-link a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: white;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .password-strength {
            font-size: 0.8rem;
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.7);
        }

        .strength-weak { color: #f87171; }
        .strength-medium { color: #fbbf24; }
        .strength-strong { color: #4ade80; }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .register-container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            .register-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1><i class="fas fa-user-plus"></i> Join Our Team</h1>
            <p>Apply for Employee Position</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="registerForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" 
                               name="full_name" 
                               class="form-control" 
                               placeholder="Enter your full name"
                               value="<?= htmlspecialchars($full_name ?? '') ?>" 
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
                        <label class="form-label">Username *</label>
                        <input type="text" 
                               name="username" 
                               class="form-control" 
                               placeholder="Choose a username"
                               value="<?= htmlspecialchars($username ?? '') ?>" 
                               required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Create a password"
                               id="password"
                               required>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" 
                               name="confirm_password" 
                               class="form-control" 
                               placeholder="Confirm your password"
                               id="confirmPassword"
                               required>
                        <div class="password-match" id="passwordMatch"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-control">
                            <option value="">Select Department</option>
                            <option value="IT">Information Technology</option>
                            <option value="HR">Human Resources</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Sales">Sales</option>
                            <option value="Operations">Operations</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Position</label>
                        <input type="text" 
                               name="position" 
                               class="form-control" 
                               placeholder="Desired position"
                               value="<?= htmlspecialchars($position ?? '') ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-register" id="registerBtn">
                <i class="fas fa-paper-plane"></i> Submit Application
            </button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
            <p><a href="index.php"><i class="fas fa-home"></i> Back to Home</a></p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.textContent = '';
                return;
            }
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    strengthDiv.textContent = 'Weak password';
                    strengthDiv.className = 'password-strength strength-weak';
                    break;
                case 2:
                case 3:
                    strengthDiv.textContent = 'Medium password';
                    strengthDiv.className = 'password-strength strength-medium';
                    break;
                case 4:
                case 5:
                    strengthDiv.textContent = 'Strong password';
                    strengthDiv.className = 'password-strength strength-strong';
                    break;
            }
        });

        // Password match checker
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchDiv.textContent = '';
                return;
            }
            
            if (password === confirmPassword) {
                matchDiv.textContent = 'Passwords match';
                matchDiv.className = 'password-strength strength-strong';
                matchDiv.style.fontSize = '0.8rem';
            } else {
                matchDiv.textContent = 'Passwords do not match';
                matchDiv.className = 'password-strength strength-weak';
                matchDiv.style.fontSize = '0.8rem';
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return;
            }
        });
    </script>
</body>
</html>
