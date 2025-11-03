<?php
session_start();
require '../config/db.php';

// Check admin authentication
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch all employees with their details
try {
    $stmt = $pdo->prepare("
        SELECT 
            e.id as employee_id,
            e.full_name,
            e.email,
            e.phone,
            e.department,
            e.position,
            e.salary,
            e.join_date,
            u.username,
            e.created_at
        FROM employees e 
        LEFT JOIN users u ON e.user_id = u.id 
        ORDER BY e.id DESC
    ");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching employees: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Employees - Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: white;
}

.container-main {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 30px;
    margin: 30px auto;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
}

/* Navigation Bar - Simple */
.navbar {
    background: rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 15px 0;
}

.navbar-brand {
    color: white !important;
    font-weight: 700;
    font-size: 1.5rem;
}

.nav-link {
    color: white !important;
    font-weight: 600;
    padding: 10px 20px !important;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white !important;
    transform: translateY(-2px);
}

/* Enhanced Table Styling */
.table {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 15px;
    overflow: hidden;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.table th {
    background: rgba(0, 0, 0, 0.4);
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}

.table td {
    border-color: rgba(255, 255, 255, 0.15);
    color: #f8f9fa;
    font-weight: 500;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
}

.table tbody tr:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.01);
    transition: all 0.3s ease;
}

/* Department Badges with Better Contrast */
.dept-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dept-it { background: #28a745; color: white; }
.dept-hr { background: #dc3545; color: white; }
.dept-finance { background: #ffc107; color: black; }
.dept-marketing { background: #17a2b8; color: white; }
.dept-operations { background: #6f42c1; color: white; }
.dept-sales { background: #fd7e14; color: white; }
.dept-default { background: #6c757d; color: white; }

/* Username Code Style */
.username-code {
    background: rgba(0, 0, 0, 0.3);
    color: #00ff88;
    padding: 6px 10px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    border: 1px solid rgba(0, 255, 136, 0.3);
}

/* Employee ID Style */
.emp-id {
    background: linear-gradient(135deg, #ff6b6b, #ffa500);
    color: white;
    padding: 4px 8px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.9rem;
}

/* Salary Style */
.salary {
    color: #28a745;
    font-weight: 700;
    font-size: 1.05rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 2px dashed rgba(255, 255, 255, 0.3);
}

/* Page Header */
.page-header h2 {
    font-size: 2.2rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    margin-bottom: 10px;
}
</style>
</head>
<body>

<!-- Simple Navigation with Only Dashboard -->
<nav class="navbar">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-users me-2"></i>EMS Admin
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="container-main">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users me-3"></i>All Employees</h2>
            <span class="badge bg-info fs-6 px-3 py-2">
                <i class="fas fa-users me-2"></i>Total: <?= count($employees) ?>
            </span>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (empty($employees)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-users fa-4x mb-3" style="opacity: 0.3;"></i>
                <h4>No Employees Found</h4>
                <p>No employee records available in the system.</p>
            </div>
        <?php else: ?>
            <!-- Employee Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-badge me-2"></i>Employee ID</th>
                            <th><i class="fas fa-user me-2"></i>Name</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <th><i class="fas fa-building me-2"></i>Department</th>
                            <th><i class="fas fa-briefcase me-2"></i>Position</th>
                            <th><i class="fas fa-calendar me-2"></i>Join Date</th>
                            <th><i class="fas fa-money-bill me-2"></i>Salary</th>
                            <th><i class="fas fa-user-circle me-2"></i>Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td>
                                <span class="emp-id">EMP-<?= str_pad($employee['employee_id'], 4, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($employee['full_name'] ?: 'N/A') ?></strong>
                                <?php if ($employee['phone']): ?>
                                    <br><small style="color: #adb5bd;"><i class="fas fa-phone fa-xs me-1"></i><?= htmlspecialchars($employee['phone']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($employee['email'] ?: 'N/A') ?></td>
                            <td>
                                <?php 
                                $dept = strtolower($employee['department'] ?: 'default');
                                $badge_class = 'dept-' . str_replace(' ', '', $dept);
                                ?>
                                <span class="dept-badge <?= $badge_class ?>">
                                    <?= htmlspecialchars($employee['department'] ?: 'Not Assigned') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($employee['position'] ?: 'N/A') ?></td>
                            <td>
                                <?php if ($employee['join_date']): ?>
                                    <?= date('M d, Y', strtotime($employee['join_date'])) ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($employee['salary'] && $employee['salary'] > 0): ?>
                                    <span class="salary">₹<?= number_format($employee['salary'], 2) ?></span>
                                <?php else: ?>
                                    <span style="color: #6c757d;">Not Set</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code class="username-code">
                                    <?= htmlspecialchars($employee['username'] ?: 'N/A') ?>
                                </code>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
// Add some interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Animate table rows on load
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        setTimeout(() => {
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

</body>
</html>
