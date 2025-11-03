<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Management System - Leave Requests</title>
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
    background: rgba(255, 255, 255, 0.13);
    backdrop-filter: blur(13px);
    border: 1px solid rgba(255,255,255,0.20);
    border-radius: 14px;
    padding: 25px;
    margin: 30px auto;
    color: white;
    max-width: 1000px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
h2 {
    color: white;
    font-weight: 600;
    margin-bottom: 25px;
    font-size: 24px;
}
.table {
    color: white;
    background: rgba(255, 255, 255, 0.07);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 0;
}
.table th, .table td {
    background: transparent !important;
    border-color: rgba(255, 255, 255, 0.18) !important;
    padding: 12px;
    font-size: 14px;
}
.table th {
    font-weight: 600;
    background: rgba(255,255,255,0.15) !important;
    color: #ffd071;
}
.table tbody tr {
    transition: background 0.2s;
}
.table-hover tbody tr:hover {
    background: rgba(255,255,255,0.15) !important;
}
.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
    margin: 0 2px;
}
.alert {
    border-radius: 10px;
    backdrop-filter: blur(10px);
    margin-bottom: 20px;
}
.alert-success {
    background: rgba(40, 167, 69, 0.2);
    border: 1px solid rgba(40, 167, 69, 0.3);
    color: #28a745;
}
.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: #ff6b6b;
}
.badge {
    font-size: 11px;
    padding: 4px 8px;
}
.add-form {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
}
.form-control, .form-select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 8px;
    margin-bottom: 10px;
}
.form-control:focus, .form-select:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    box-shadow: 0 0 15px rgba(255,255,255,0.1);
}
.form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
}
.form-select option {
    background: #333;
    color: white;
}
.btn-primary {
    background: linear-gradient(135deg, #ff6b6b, #ffa500);
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
}
</style>
</head>
<body>

<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ems";

$message = "";
$message_type = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Add new leave request
        if (isset($_POST['add_leave'])) {
            $employee_id = $_POST['employee_id'];
            $leave_type = $_POST['leave_type'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $reason = $_POST['reason'];
            
            $sql = "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, reason, status, applied_date, created_at) 
                    VALUES (:employee_id, :leave_type, :start_date, :end_date, :reason, 'Pending', CURDATE(), NOW())";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':employee_id' => $employee_id,
                ':leave_type' => $leave_type,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':reason' => $reason
            ]);
            
            $message = "Leave request added successfully!";
            $message_type = "success";
        }
        
        // Approve/Reject leave
        if (isset($_POST['action']) && isset($_POST['leave_id'])) {
            $leave_id = $_POST['leave_id'];
            $action = $_POST['action'];
            $status = ($action == 'approve') ? 'Approved' : 'Rejected';
            
            $sql = "UPDATE leave_requests SET status = :status, approved_date = NOW() WHERE id = :leave_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':status' => $status, ':leave_id' => $leave_id]);
            
            $message = "Leave request " . strtolower($status) . " successfully!";
            $message_type = "success";
        }
    }
    
    // Fetch leave requests with employee names using exact column names - WITH JOIN
    $sql = "SELECT lr.*, e.full_name as employee_name 
            FROM leave_requests lr 
            LEFT JOIN employees e ON lr.employee_id = e.id 
            ORDER BY lr.created_at DESC";
    $leaves = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch employees for dropdown using exact column names
    $employees_sql = "SELECT id as emp_id, full_name as emp_name FROM employees";
    $employees = $conn->query($employees_sql)->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $message = "Database Error: " . $e->getMessage();
    $message_type = "danger";
}
?>

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
      <!-- <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li> -->
    </ul>
  </div>
</div>
</nav>

<div class="container mt-4">
<div class="container-main">
    <h2><i class="fas fa-plane-departure me-2"></i>Leave Management </h2>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Add Leave Form -->
    <div class="add-form">
        <h5><i class="fas fa-plus me-2"></i>Add New Leave Request</h5>
        <form method="POST">
            <div class="row">
                <div class="col-md-3">
                    <select name="employee_id" class="form-select" required>
                        <option value="">Select Employee</option>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?php echo $emp['emp_id']; ?>"><?php echo htmlspecialchars($emp['emp_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="leave_type" class="form-select" required>
                        <option value="">Leave Type</option>
                        <option value="Sick">Sick Leave</option>
                        <option value="Casual">Casual Leave</option>
                        <option value="Annual">Annual Leave</option>
                        <option value="Maternity">Maternity Leave</option>
                        <option value="Emergency">Emergency Leave</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="reason" class="form-control" placeholder="Reason" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="add_leave" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Leave Requests Table -->
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Employee</th>
            <th>Type</th>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Applied</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($leaves)): ?>
        <tr>
            <td colspan="8" class="text-center">No leave requests found</td>
        </tr>
        <?php else: ?>
        <?php foreach($leaves as $leave): ?>
        <tr>
            <td><?php echo htmlspecialchars($leave['employee_name'] ?? 'Unknown Employee'); ?></td>
            <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
            <td><?php echo date('M d, Y', strtotime($leave['start_date'])); ?></td>
            <td><?php echo date('M d, Y', strtotime($leave['end_date'])); ?></td>
            <td><?php echo htmlspecialchars($leave['reason'] ?? ''); ?></td>
            <td>
                <?php
                $status = $leave['status'];
                $badge_class = 'bg-warning text-dark';
                if ($status == 'Approved') $badge_class = 'bg-success';
                if ($status == 'Rejected') $badge_class = 'bg-danger';
                ?>
                <span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span>
            </td>
            <td><?php echo date('M d, Y', strtotime($leave['applied_date'] ?? $leave['created_at'])); ?></td>
            <td>
                <?php if ($leave['status'] == 'Pending'): ?>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-success" title="Approve">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" title="Reject">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
                <?php else: ?>
                <span class="text-muted">No Action</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    btn.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>
</body>
</html>
