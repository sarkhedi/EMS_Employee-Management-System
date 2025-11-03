<?php
// 🔹 DO NOT put session_start() here!
// It should be in header.php or the main file that includes navbar.php

$userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="/ems/index.php">EMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <?php if ($username): ?>
          <?php if ($userType == 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="/ems/admin/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/admin/employees.php">Employees</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/admin/leave_status.php">Leave</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/admin/payroll.php">Payroll</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/assign_project.php">Assign Project</a></li>
          <?php elseif ($userType == 'employee'): ?>
            <li class="nav-item"><a class="nav-link" href="/ems/employee/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/employee/leave_request.php">Leave Request</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/employee/salary.php">Salary</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/employee/attendance.php">Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="/ems/employee/profile.php">Profile</a></li>
          <?php endif; ?> 
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if ($username): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
              <?php echo htmlspecialchars($username); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="/ems/logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/ems/login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
