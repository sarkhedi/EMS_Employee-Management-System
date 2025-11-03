<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);
    $salary = trim($_POST['salary']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($full_name) || empty($email) || empty($department) || empty($position) || empty($username) || empty($password)) {
        $error = "Please fill all required fields.";
    } else {
        try {
            $check_email = $pdo->prepare("SELECT id FROM employees WHERE email = ?");
            $check_email->execute([$email]);
            $check_username = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $check_username->execute([$username]);

            if ($check_email->rowCount() > 0) {
                $error = "Email already exists. Please use a different email.";
            } elseif ($check_username->rowCount() > 0) {
                $error = "Username already exists. Please choose a different username.";
            } else {
                $pdo->beginTransaction();
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $user_stmt = $pdo->prepare("INSERT INTO users (username, password, role, is_approved, created_at) VALUES (?, ?, 'employee', 1, NOW())");
                $user_stmt->execute([$username, $hashed_password]);
                $user_id = $pdo->lastInsertId();
                $emp_stmt = $pdo->prepare("INSERT INTO employees (user_id, full_name, email, phone, department, position, salary, join_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), NOW())");
                $emp_stmt->execute([$user_id, $full_name, $email, $phone, $department, $position, $salary]);
                $pdo->commit();
                $success = "Employee created successfully!<br>
                            <strong>Username:</strong> <code>$username</code><br>
                            <strong>Password:</strong> <code>$password</code><br>
                            <small>Please provide these credentials to the employee for first login.</small>";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
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
<title>Add Employee | EMS Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: white;
    margin: 0;
}

.navbar {
    background: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 15px 0;
}

.navbar-brand, .nav-link {
    color: white !important;
    font-weight: 500;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.form-glass {
    background: rgba(255, 255, 255, 0.14);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.11);
    padding: 30px;
    margin: 30px auto 0 auto;
    max-width: 850px;   /* THIS IS THE ONLY CHANGE – FORM NOW 850px WIDE */
    color: white;
}

.form-title {
    text-align: center;
    font-size: 20px;
    color: white;
    font-weight: 600;
    margin-bottom: 20px;
}

.form-label {
    color: white;
    font-weight: 500;
    margin-bottom: 3px;
    font-size: 14px;
}

.input-group {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.7);
    font-size: 14px;
    z-index: 2;
}

.form-control, .form-select {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.28);
    color: white;
    border-radius: 6px;
    font-size: 14px;
    padding: 8px 8px 8px 32px;
    margin-bottom: 15px;
    transition: all .2s;
}

.form-control:focus, .form-select:focus {
    background: rgba(255,255,255,0.19);
    border-color: #987de6;
    color: white;
    box-shadow: 0 0 8px rgba(118,75,162,0.16);
}

.form-control::placeholder {
    color: rgba(255,255,255,0.65);
    font-size: 13px;
}

.form-select option {
    background: #333;
    color: white;
}

.btn-primary {
    background: linear-gradient(135deg, #ff6b6b, #ffa500);
    border: none;
    border-radius: 6px;
    padding: 10px 0;
    font-weight: 600;
    font-size: 14px;
    width: 100%;
    color: white;
    box-shadow: 0 3px 8px rgba(255,107,107,0.14);
    transition: all .16s;
    margin-top: 5px;
}

.btn-primary:hover, .btn-primary:active {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(255,107,107,0.26);
    color: white;
}

.alert {
    border-radius: 8px;
    font-size: 14px;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: none;
    font-weight: 500;
}

.alert-success {
    background: rgba(40,167,69,0.2);
    border: 2px solid rgba(40,167,69,0.5);
    color: #28a745;
}

.alert-danger {
    background: rgba(220,53,69,0.2);
    border: 2px solid rgba(220,53,69,0.5);
    color: #dc3545;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><i class="fas fa-users"></i> EMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="employees.php"><i class="fas fa-users"></i> Employees</a></li>
                <li class="nav-item"><a class="nav-link" href="employees_add.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li> -->
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="form-glass">
        <h2 class="form-title"><i class="fas fa-user-plus me-2"></i>Add New Employee</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
            </div>
            <div class="text-center mt-3">
                <a href="employees_add.php" class="btn btn-outline-light me-2">Add Another Employee</a>
                <a href="employees.php" class="btn btn-outline-light">View All Employees</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Enter full name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Department *</label>
                            <select name="department" class="form-select" required>
                                <option value="">Select Department</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Finance">Finance</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Operations">Operations</option>
                                <option value="Sales">Sales</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Position *</label>
                            <input type="text" name="position" class="form-control" placeholder="Enter position" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Enter phone number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Salary *</label>
                            <input type="number" name="salary" class="form-control" placeholder="Enter salary" required step="0.01">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Username *</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Employee Account
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
