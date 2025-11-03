<?php
session_start();
require '../config/db.php';

// Check admin authentication
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$success = '';
$error = '';

// Handle form submission - FIXED VERSION
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    
    if (!empty($employee_id) && !empty($date) && !empty($status)) {
        try {
            // Check if attendance already exists for this date
            $check_stmt = $pdo->prepare("SELECT id FROM attendance WHERE employee_id = ? AND date = ?");
            $check_stmt->execute([$employee_id, $date]);
            
            if ($check_stmt->rowCount() > 0) {
                // Update existing attendance - REMOVED updated_at
                $update_stmt = $pdo->prepare("UPDATE attendance SET status = ? WHERE employee_id = ? AND date = ?");
                $update_stmt->execute([$status, $employee_id, $date]);
                $success = "Attendance updated successfully!";
            } else {
                // Insert new attendance - REMOVED created_at
                $insert_stmt = $pdo->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?)");
                $insert_stmt->execute([$employee_id, $date, $status]);
                $success = "Attendance saved successfully!";
            }
        } catch (PDOException $e) {
            $error = "Error saving attendance: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all required fields.";
    }
}

// Fetch all employees for dropdown
try {
    $emp_stmt = $pdo->prepare("SELECT id, full_name FROM employees ORDER BY full_name");
    $emp_stmt->execute();
    $employees = $emp_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching employees: " . $e->getMessage();
}

// Calculate attendance percentage for each employee
try {
    $attendance_stmt = $pdo->prepare("
        SELECT 
            e.id,
            e.full_name,
            COUNT(a.id) as total_days,
            SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) as present_days,
            SUM(CASE WHEN a.status = 'Absent' THEN 1 ELSE 0 END) as absent_days,
            SUM(CASE WHEN a.status = 'Leave' THEN 1 ELSE 0 END) as leave_days,
            ROUND(
                (SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) * 100.0) / 
                NULLIF(COUNT(a.id), 0), 2
            ) as attendance_percentage
        FROM employees e
        LEFT JOIN attendance a ON e.id = a.employee_id
        GROUP BY e.id, e.full_name
        ORDER BY e.full_name
    ");
    $attendance_stmt->execute();
    $attendance_data = $attendance_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $attendance_data = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Management System - Attendance</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
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

.container-main {
    display: flex;
    gap: 30px;
    margin: 30px auto;
    max-width: 1200px;
}

.form-glass {
    background: rgba(255, 255, 255, 0.14);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.11);
    padding: 25px 20px;
    max-width: 380px;
    min-width: 350px;
    color: white;
    height: fit-content;
}

.attendance-stats {
    background: rgba(255, 255, 255, 0.14);
    backdrop-filter: blur(18px);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.11);
    padding: 25px;
    flex: 1;
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
    background: linear-gradient(135deg, #ff6b6b 35%, #ffa500 100%);
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    padding: 8px 0;
    width: 100%;
    box-shadow: 0 3px 8px rgba(255,107,107,0.14);
    transition: all .16s;
    margin-top: 5px;
}

.btn-primary:hover, .btn-primary:active {
    transform: translateY(-1px) scale(1.01);
    box-shadow: 0 5px 15px rgba(255,107,107,0.26);
}

.status-present { color: #28a745 !important; }
.status-absent { color: #dc3545 !important; }
.status-leave { color: #ffc107 !important; }

/* Enhanced Attendance Stats Table */
.attendance-table {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.attendance-table th {
    background: rgba(0, 0, 0, 0.4);
    color: #ffffff;
    font-weight: 700;
    padding: 15px 12px;
    font-size: 14px;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.attendance-table td {
    color: #f8f9fa;
    font-weight: 500;
    padding: 12px;
    border-color: rgba(255, 255, 255, 0.15);
    font-size: 14px;
    background: rgba(255, 255, 255, 0.05);
}

.attendance-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.01);
    transition: all 0.3s ease;
}

/* Enhanced Employee Name Styling */
.employee-name {
    color: #ffffff;
    font-weight: 700;
    font-size: 15px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

/* Enhanced Badge Styling */
.badge {
    font-size: 12px;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 15px;
    text-shadow: none;
}

.badge.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    color: white;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.badge.bg-danger {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
    color: white;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.badge.bg-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
    color: #000;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.badge.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
    color: white;
    box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
}

/* Enhanced Percentage Display */
.percentage-display {
    display: flex;
    align-items: center;
    gap: 10px;
}

.percentage-text {
    color: #ffffff;
    font-weight: 700;
    font-size: 16px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    min-width: 50px;
}

.percentage-bar {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    height: 10px;
    overflow: hidden;
    flex: 1;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.percentage-fill {
    height: 100%;
    border-radius: 10px;
    transition: width 0.5s ease;
    box-shadow: inset 0 1px 3px rgba(255,255,255,0.3);
}

.percentage-excellent { 
    background: linear-gradient(135deg, #28a745, #20c997);
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
}

.percentage-good { 
    background: linear-gradient(135deg, #17a2b8, #138496);
    box-shadow: 0 0 10px rgba(23, 162, 184, 0.5);
}

.percentage-average { 
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
}

.percentage-poor { 
    background: linear-gradient(135deg, #dc3545, #c82333);
    box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
}

/* Alert Styling */
.alert {
    border-radius: 8px;
    font-size: 14px;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: none;
    font-weight: 500;
}

.alert-success {
    background: rgba(40, 167, 69, 0.2);
    border: 2px solid rgba(40, 167, 69, 0.5);
    color: #28a745;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    border: 2px solid rgba(220, 53, 69, 0.5);
    color: #dc3545;
}

/* Empty State Styling */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 2px dashed rgba(255, 255, 255, 0.3);
}

.empty-state h5 {
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-state p {
    color: rgba(255, 255, 255, 0.7);
}

@media (max-width: 768px) {
    .container-main {
        flex-direction: column;
        gap: 20px;
    }
    .form-glass {
        max-width: 100%;
        min-width: auto;
    }
}
</style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><i class="fas fa-users"></i> EMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="employees.php"><i class="fas fa-users"></i> Employees</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li class="nav-item"><a class="nav-link" href="leaves.php"><i class="fas fa-plane"></i> Leaves</a></li>
                <li class="nav-item"><a class="nav-link" href="payroll.php"><i class="fas fa-money-bill-wave"></i> Payroll</a></li>
                <li class="nav-item"><span class="nav-link">Welcome, admin</span></li> -->
                <!-- <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul> -->
        </div>
    </div>
</nav>

<div class="container">
    <div class="container-main">
        <!-- Attendance Form -->
        <div class="form-glass">
            <h2 class="form-title"><i class="fas fa-calendar-check me-2"></i>Mark Attendance</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?= $success ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Employee</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="input-group">
                        <i class="fas fa-check-circle input-icon"></i>
                        <select name="status" class="form-select" required>
                            <option value="">Select Status</option>
                            <option value="Present" class="status-present">Present</option>
                            <option value="Absent" class="status-absent">Absent</option>
                            <option value="Leave" class="status-leave">Leave</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Save Attendance
                </button>
            </form>
        </div>

        <!-- Enhanced Attendance Statistics -->
        <div class="attendance-stats">
            <h2 class="form-title"><i class="fas fa-chart-bar me-2"></i>Attendance Statistics</h2>
            
            <?php if (empty($attendance_data)): ?>
                <div class="empty-state">
                    <i class="fas fa-chart-bar fa-3x mb-3" style="opacity: 0.3;"></i>
                    <h5>No Attendance Data</h5>
                    <p>Start marking attendance to see statistics.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table attendance-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Employee</th>
                                <th><i class="fas fa-check me-2"></i>Present</th>
                                <th><i class="fas fa-times me-2"></i>Absent</th>
                                <th><i class="fas fa-plane me-2"></i>Leave</th>
                                <th><i class="fas fa-calendar me-2"></i>Total</th>
                                <th><i class="fas fa-percentage me-2"></i>Attendance %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance_data as $data): ?>
                                <?php 
                                $percentage = $data['attendance_percentage'] ?: 0;
                                $percentage_class = '';
                                if ($percentage >= 90) $percentage_class = 'percentage-excellent';
                                elseif ($percentage >= 75) $percentage_class = 'percentage-good';
                                elseif ($percentage >= 60) $percentage_class = 'percentage-average';
                                else $percentage_class = 'percentage-poor';
                                ?>
                                <tr>
                                    <td>
                                        <span class="employee-name"><?= htmlspecialchars($data['full_name']) ?></span>
                                    </td>
                                    <td><span class="badge bg-success"><?= $data['present_days'] ?: 0 ?></span></td>
                                    <td><span class="badge bg-danger"><?= $data['absent_days'] ?: 0 ?></span></td>
                                    <td><span class="badge bg-warning"><?= $data['leave_days'] ?: 0 ?></span></td>
                                    <td><span class="badge bg-info"><?= $data['total_days'] ?: 0 ?></span></td>
                                    <td>
                                        <div class="percentage-display">
                                            <span class="percentage-text"><?= number_format($percentage, 1) ?>%</span>
                                            <div class="percentage-bar">
                                                <div class="percentage-fill <?= $percentage_class ?>" style="width: <?= $percentage ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.form-control, .form-select').forEach(input => {
    input.addEventListener('focus', function() {
        this.style.transform = 'scale(1.02)';
        let icon = this.parentElement.querySelector('.input-icon');
        if (icon) icon.style.color = '#ffa500';
    });
    input.addEventListener('blur', function() {
        this.style.transform = 'scale(1)';
        let icon = this.parentElement.querySelector('.input-icon');
        if (icon) icon.style.color = 'rgba(255,255,255,0.7)';
    });
});

// Set today's date as default
document.querySelector('input[type="date"]').valueAsDate = new Date();

// Status change icon
document.querySelector('select[name="status"]').addEventListener('change', function() {
    let icon = this.parentElement.querySelector('.input-icon');
    switch(this.value) {
        case 'Present':
            icon.className = 'fas fa-check-circle input-icon status-present';
            break;
        case 'Absent':
            icon.className = 'fas fa-times-circle input-icon status-absent';
            break;
        case 'Leave':
            icon.className = 'fas fa-plane input-icon status-leave';
            break;
        default:
            icon.className = 'fas fa-check-circle input-icon';
    }
});
</script>
</body>
</html>
