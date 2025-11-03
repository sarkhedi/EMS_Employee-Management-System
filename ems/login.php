<?php
session_start();
require 'config/db.php';

$error = '';
$success = '';
$selected_role = isset($_POST['role']) ? $_POST['role'] : 'admin';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'] ?? 'admin';
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        try {
            // Prepare and execute query to get user with specific username and role
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
            $stmt->execute([$username, $role]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Check approval status for employees only
                if ($role === 'employee' && isset($user['is_approved'])) {
                    if ($user['is_approved'] == 0) {
                        $error = "Your account is pending admin approval. Please wait for approval.";
                    } elseif ($user['is_approved'] == 2) {
                        $error = "Your account has been rejected by admin. Please contact administrator.";
                    }
                }

                // Proceed if no error
                if (empty($error)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['username'] = $user['username'];

                    // Remember me cookie
                    if ($remember) {
                        setcookie('remember_user', $username, time() + (86400 * 30), "/"); // 30 days
                    } else {
                        setcookie('remember_user', '', time() - 3600, "/"); // clear cookie
                    }

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: admin/dashboard.php");
                        exit();
                    } elseif ($user['role'] === 'employee') {
                        header("Location: employee/dashboard.php");
                        exit();
                    }
                }
            } else {
                $error = "Invalid " . ($role === 'admin' ? 'admin' : 'employee') . " credentials!";
            }
        } catch (PDOException $e) {
            $error = "Database connection error: " . $e->getMessage();
        }
    }
}

// Retrieve username from cookie if remember me was checked
$remembered_user = $_COOKIE['remember_user'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Employee Management System</title>
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
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.welcome-section {
    text-align: center;
    color: white;
    margin-bottom: 40px;
    animation: fadeInDown 1s ease-out;
}
.brand-logo {
    font-size: 5rem;
    background: linear-gradient(45deg, #fff, #e0e0e0);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 0 20px rgba(255,255,255,0.3);
    animation: logoFloat 3s ease-in-out infinite;
    margin-bottom: 20px;
}
.welcome-title {
    font-size: 3.2rem;
    font-weight: 700;
    margin-bottom: 12px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
    line-height: 1.2;
}
.welcome-subtitle {
    font-size: 1.4rem;
    font-weight: 400;
    opacity: 0.9;
    line-height: 1.4;
}
.login-form-container {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    animation: fadeInUp 1s ease-out;
}
.login-header {
    text-align: center;
    margin-bottom: 30px;
}
.login-header h2 {
    color: white;
    font-weight: 600;
    font-size: 1.8rem;
    margin-bottom: 8px;
}
.login-header p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    font-weight: 400;
}
.role-selector {
    display: flex;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 6px;
    margin-bottom: 25px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.role-option {
    flex: 1;
    text-align: center;
    padding: 12px 20px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 600;
    font-size: 0.9rem;
    position: relative;
    overflow: hidden;
}
.role-option::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    transition: all 0.4s ease;
    z-index: -1;
}
.role-option.active {
    color: white;
    transform: scale(1.02);
    box-shadow: 0 6px 20px rgba(103, 126, 234, 0.4);
}
.role-option.active::before {
    left: 0;
}
.role-option:hover:not(.active) {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}
.form-group {
    position: relative;
    margin-bottom: 25px;
}
.form-label {
    color: white;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
    font-size: 1rem;
}
.form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 15px 20px 15px 50px;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    font-weight: 500;
}
.form-control:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.6);
    box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
    outline: none;
    color: white;
}
.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
    font-weight: 400;
}
.input-icon {
    position: absolute;
    top: 65%;
    left: 18px;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.2rem;
    transition: all 0.3s ease;
}
.form-control:focus + .input-icon {
    color: white;
}
.btn-login {
    background: linear-gradient(135deg, #ff6b6b, #ffa500);
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    width: 100%;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    margin: 15px 0;
}
.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
}
.btn-login:disabled {
    background: linear-gradient(135deg, #a0aec0, #718096);
    cursor: not-allowed;
    transform: none;
}
.remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    font-size: 0.9rem;
}
.custom-checkbox {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}
.custom-checkbox input {
    margin-right: 8px;
    transform: scale(1.2);
    accent-color: #667eea;
}
.forgot-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}
.forgot-link:hover {
    color: white;
    text-decoration: underline;
}
.alert {
    border-radius: 12px;
    margin-bottom: 20px;
    border: none;
    font-size: 0.9rem;
    padding: 12px 15px;
    font-weight: 500;
}
.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #ff6b6b;
    border-left: 4px solid #ff6b6b;
}
.alert-success {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border-left: 4px solid #28a745;
}
.back-home {
    text-align: center;
    margin-top: 20px;
}
.back-home a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}
.back-home a:hover {
    color: white;
    text-decoration: underline;
}
.role-indicator {
    text-align: center;
    margin-bottom: 15px;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes logoFloat {
    0%, 100% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(-10px) scale(1.05); }
}
@media (max-width: 768px) {
    .welcome-title { font-size: 2.5rem; }
    .welcome-subtitle { font-size: 1.2rem; }
    .brand-logo { font-size: 4rem; }
    .login-form-container { padding: 30px 25px; }
    body { padding: 15px; }
}
@media (max-width: 576px) {
    .welcome-title { font-size: 2rem; }
    .welcome-subtitle { font-size: 1rem; }
    .brand-logo { font-size: 3.5rem; }
    .login-form-container { padding: 25px 20px; }
    .role-selector { flex-direction: column; gap: 8px; }
}
</style>
</head>
<body>
    <div class="welcome-section">
        <div class="brand-logo">
            <i class="fas fa-users"></i>
        </div>
        <h1 class="welcome-title">Welcome to EMS</h1>
        <p class="welcome-subtitle">Advanced Employee Management System for Modern Businesses</p>
    </div>

    <div class="login-form-container">
        <div class="login-header">
            <h2><i class="fas fa-sign-in-alt me-2"></i>Welcome Back</h2>
            <p>Sign in to access your account</p>
        </div>

        <div class="role-selector">
            <div class="role-option <?= $selected_role == 'admin' ? 'active' : '' ?>" onclick="setRole('admin')">
                <i class="fas fa-crown me-2"></i>Admin
            </div>
            <div class="role-option <?= $selected_role == 'employee' ? 'active' : '' ?>" onclick="setRole('employee')">
                <i class="fas fa-user me-2"></i>Employee
            </div>
        </div>

        <div class="role-indicator" id="roleIndicator">
            Logging in as: <strong><?= $selected_role == 'admin' ? 'Administrator' : 'Employee' ?></strong>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
            <input type="hidden" name="role" id="roleInput" value="<?= htmlspecialchars($selected_role) ?>">

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username" value="<?= htmlspecialchars($remembered_user) ?>" required autocomplete="username">
                <i class="fas fa-user input-icon"></i>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
                <i class="fas fa-lock input-icon"></i>
            </div>

            <div class="remember-forgot">
                <label class="custom-checkbox">
                    <input type="checkbox" name="remember" <?= $remembered_user ? 'checked' : '' ?>>
                    Remember me
                </label>
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In to Account
            </button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function setRole(role) {
            document.getElementById('roleInput').value = role;
            document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
            event.target.classList.add('active');
            
            const roleIndicator = document.getElementById('roleIndicator');
            roleIndicator.innerHTML = 'Logging in as: <strong>' + (role === 'admin' ? 'Administrator' : 'Employee') + '</strong>';
            
            const usernameInput = document.querySelector('input[name="username"]');
            const passwordInput = document.querySelector('input[name="password"]');
            
            if (role === 'admin') {
                usernameInput.placeholder = "Enter admin username";
                passwordInput.placeholder = "Enter admin password";
            } else {
                usernameInput.placeholder = "Enter employee username";
                passwordInput.placeholder = "Enter employee password";
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const currentRole = document.getElementById('roleInput').value || 'admin';
            
            document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
            if (currentRole === 'admin') {
                document.querySelector('.role-option:first-child').classList.add('active');
            } else {
                document.querySelector('.role-option:last-child').classList.add('active');
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const currentRole = document.getElementById('roleInput').value;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In as ' + (currentRole === 'admin' ? 'Admin' : 'Employee') + '...';
            btn.disabled = true;
        });

        // If you use JS redirect, add this block
        <?php if (isset($redirect)): ?>
            setTimeout(function() {
                window.location.href = '<?= $redirect ?>';
            }, 1500);
        <?php endif; ?>
    </script>
</body>
</html>
