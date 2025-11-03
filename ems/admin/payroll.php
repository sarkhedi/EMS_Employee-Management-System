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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $basic_salary = $_POST['basic_salary'];
    $allowances = $_POST['allowances'] ?: 0;
    $deductions = $_POST['deductions'] ?: 0;
    $pay_date = $_POST['pay_date'];
    
    // Calculate net salary
    $net_salary = $basic_salary + $allowances - $deductions;
    
    if (!empty($employee_id) && !empty($basic_salary) && !empty($pay_date)) {
        try {
            // Check if payroll already exists for this employee and month
            $check_stmt = $pdo->prepare("SELECT id FROM payroll WHERE employee_id = ? AND DATE_FORMAT(pay_date, '%Y-%m') = DATE_FORMAT(?, '%Y-%m')");
            $check_stmt->execute([$employee_id, $pay_date]);
            
            if ($check_stmt->rowCount() > 0) {
                // Update existing payroll
                $update_stmt = $pdo->prepare("
                    UPDATE payroll 
                    SET basic_salary = ?, allowances = ?, deductions = ?, net_salary = ?, pay_date = ?
                    WHERE employee_id = ? AND DATE_FORMAT(pay_date, '%Y-%m') = DATE_FORMAT(?, '%Y-%m')
                ");
                $update_stmt->execute([$basic_salary, $allowances, $deductions, $net_salary, $pay_date, $employee_id, $pay_date]);
                $success = "Payroll updated successfully!";
            } else {
                // Insert new payroll
                $insert_stmt = $pdo->prepare("
                    INSERT INTO payroll (employee_id, basic_salary, allowances, deductions, net_salary, pay_date) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $insert_stmt->execute([$employee_id, $basic_salary, $allowances, $deductions, $net_salary, $pay_date]);
                $success = "Payroll saved successfully!";
            }
        } catch (PDOException $e) {
            $error = "Error saving payroll: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all required fields.";
    }
}

// Fetch all employees for dropdown
try {
    $emp_stmt = $pdo->prepare("SELECT id, full_name, salary FROM employees ORDER BY full_name");
    $emp_stmt->execute();
    $employees = $emp_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching employees: " . $e->getMessage();
}

// Fetch payroll records with employee details
try {
    $payroll_stmt = $pdo->prepare("
        SELECT 
            p.*,
            e.full_name,
            e.department,
            e.position
        FROM payroll p
        JOIN employees e ON p.employee_id = e.id
        ORDER BY p.pay_date DESC, e.full_name
    ");
    $payroll_stmt->execute();
    $payroll_records = $payroll_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $payroll_records = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Management System - Payroll</title>
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
    max-width: 1400px;
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

.payroll-records {
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

/* Enhanced Payroll Records */
.payroll-table {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.payroll-table th {
    background: rgba(0, 0, 0, 0.4);
    color: #ffffff;
    font-weight: 700;
    padding: 15px 12px;
    font-size: 14px;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payroll-table td {
    color: #f8f9fa;
    font-weight: 500;
    padding: 12px;
    border-color: rgba(255, 255, 255, 0.15);
    font-size: 14px;
    background: rgba(255, 255, 255, 0.05);
}

.payroll-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.01);
    transition: all 0.3s ease;
}

/* Salary Display */
.salary-amount {
    font-weight: 700;
    font-size: 15px;
    color: #00ff88;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.employee-name {
    color: #ffffff;
    font-weight: 700;
    font-size: 15px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

/* Department Badge */
.dept-badge {
    padding: 4px 10px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
}

.dept-it { background: #28a745; color: white; }
.dept-hr { background: #dc3545; color: white; }
.dept-finance { background: #ffc107; color: black; }
.dept-marketing { background: #17a2b8; color: white; }
.dept-operations { background: #6f42c1; color: white; }
.dept-sales { background: #fd7e14; color: white; }
.dept-default { background: #6c757d; color: white; }

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

/* Empty State */
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

/* Auto-calculation Display */
.calculation-display {
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
}

.calc-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}

.calc-total {
    border-top: 1px solid rgba(255, 255, 255, 0.3);
    padding-top: 8px;
    font-weight: 700;
    font-size: 16px;
    color: #00ff88;
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
                <li class="nav-item"><a class="nav-link" href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li class="nav-item"><a class="nav-link" href="leaves.php"><i class="fas fa-plane"></i> Leaves</a></li>
                <li class="nav-item"><a class="nav-link" href="payroll.php"><i class="fas fa-money-bill-wave"></i> Payroll</a></li>
                <li class="nav-item"><span class="nav-link">Welcome, admin</span></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="container-main">
        <!-- Payroll Form -->
        <div class="form-glass">
            <h2 class="form-title"><i class="fas fa-money-bill-wave me-2"></i>Generate Payroll</h2>
            
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
            
            <form method="POST" id="payrollForm">
                <div class="form-group">
                    <label class="form-label">Employee</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <select name="employee_id" id="employeeSelect" class="form-select" required>
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['id'] ?>" data-salary="<?= $employee['salary'] ?>">
                                    <?= htmlspecialchars($employee['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Basic Salary</label>
                    <div class="input-group">
                        <i class="fas fa-coins input-icon"></i>
                        <input type="number" name="basic_salary" id="basicSalary" step="0.01" placeholder="Basic Salary" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Allowances</label>
                    <div class="input-group">
                        <i class="fas fa-plus-circle input-icon"></i>
                        <input type="number" name="allowances" id="allowances" step="0.01" placeholder="Allowances" class="form-control" value="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deductions</label>
                    <div class="input-group">
                        <i class="fas fa-minus-circle input-icon"></i>
                        <input type="number" name="deductions" id="deductions" step="0.01" placeholder="Deductions" class="form-control" value="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Pay Date</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="date" name="pay_date" class="form-control" required>
                    </div>
                </div>
                
                <!-- Auto-calculation Display -->
                <div class="calculation-display" id="calculationDisplay" style="display: none;">
                    <div class="calc-row">
                        <span>Basic Salary:</span>
                        <span id="displayBasic">₹0.00</span>
                    </div>
                    <div class="calc-row">
                        <span>Allowances:</span>
                        <span id="displayAllowances">₹0.00</span>
                    </div>
                    <div class="calc-row">
                        <span>Deductions:</span>
                        <span id="displayDeductions">₹0.00</span>
                    </div>
                    <div class="calc-row calc-total">
                        <span>Net Salary:</span>
                        <span id="displayNetSalary">₹0.00</span>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Generate Payroll
                </button>
            </form>
        </div>

        <!-- Payroll Records -->
        <div class="payroll-records">
            <h2 class="form-title"><i class="fas fa-receipt me-2"></i>Payroll Records</h2>
            
            <?php if (empty($payroll_records)): ?>
                <div class="empty-state">
                    <i class="fas fa-receipt fa-3x mb-3" style="opacity: 0.3;"></i>
                    <h5>No Payroll Records</h5>
                    <p>Start generating payroll to see records.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table payroll-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Employee</th>
                                <th><i class="fas fa-building me-2"></i>Department</th>
                                <th><i class="fas fa-coins me-2"></i>Basic</th>
                                <th><i class="fas fa-plus me-2"></i>Allowances</th>
                                <th><i class="fas fa-minus me-2"></i>Deductions</th>
                                <th><i class="fas fa-money-bill me-2"></i>Net Salary</th>
                                <th><i class="fas fa-calendar me-2"></i>Pay Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payroll_records as $record): ?>
                                <?php 
                                $dept = strtolower($record['department'] ?: 'default');
                                $badge_class = 'dept-' . str_replace(' ', '', $dept);
                                ?>
                                <tr>
                                    <td>
                                        <span class="employee-name"><?= htmlspecialchars($record['full_name']) ?></span>
                                        <br><small style="color: #adb5bd;"><?= htmlspecialchars($record['position']) ?></small>
                                    </td>
                                    <td>
                                        <span class="dept-badge <?= $badge_class ?>">
                                            <?= htmlspecialchars($record['department']) ?>
                                        </span>
                                    </td>
                                    <td><span class="salary-amount">₹<?= number_format($record['basic_salary'], 2) ?></span></td>
                                    <td><span style="color: #28a745;">₹<?= number_format($record['allowances'], 2) ?></span></td>
                                    <td><span style="color: #dc3545;">₹<?= number_format($record['deductions'], 2) ?></span></td>
                                    <td><span class="salary-amount">₹<?= number_format($record['net_salary'], 2) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($record['pay_date'])) ?></td>
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

// Auto-fill basic salary when employee is selected
document.getElementById('employeeSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const salary = selectedOption.getAttribute('data-salary');
    
    if (salary && salary > 0) {
        document.getElementById('basicSalary').value = salary;
        calculateNetSalary();
    } else {
        document.getElementById('basicSalary').value = '';
        hideCalculation();
    }
});

// Real-time calculation
function calculateNetSalary() {
    const basic = parseFloat(document.getElementById('basicSalary').value) || 0;
    const allowances = parseFloat(document.getElementById('allowances').value) || 0;
    const deductions = parseFloat(document.getElementById('deductions').value) || 0;
    const netSalary = basic + allowances - deductions;
    
    if (basic > 0) {
        document.getElementById('displayBasic').textContent = '₹' + basic.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('displayAllowances').textContent = '₹' + allowances.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('displayDeductions').textContent = '₹' + deductions.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('displayNetSalary').textContent = '₹' + netSalary.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('calculationDisplay').style.display = 'block';
    } else {
        hideCalculation();
    }
}

function hideCalculation() {
    document.getElementById('calculationDisplay').style.display = 'none';
}

// Add event listeners for real-time calculation
['basicSalary', 'allowances', 'deductions'].forEach(id => {
    document.getElementById(id).addEventListener('input', calculateNetSalary);
});

// Set current date as default
document.querySelector('input[type="date"]').valueAsDate = new Date();
</script>
</body>
</html>
