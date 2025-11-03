<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header('Location: ../login.php');
    exit;
}

require '../config/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Employee';
$today = date('Y-m-d');

// Initialize variables
$employee = null;
$projects = [];
$error_message = null;
$success_message = null;

try {
    // Get employee details
    $emp_stmt = $pdo->prepare('SELECT id, user_id, full_name, department FROM employees WHERE user_id = ?');
    $emp_stmt->execute([$user_id]);
    $employee = $emp_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        throw new Exception('Employee not found');
    }

    $employee_database_id = $employee['id'];

    // Handle status update - keeps old comments intact
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
        $project_id = $_POST['project_id'];
        $new_status = $_POST['status'];

        // Insert new status record (keeps all old data)
        $update_stmt = $pdo->prepare('INSERT INTO project_status (project_id, status, updated_by, status_date) VALUES (?, ?, ?, NOW())');
        $update_stmt->execute([$project_id, $new_status, $employee_database_id]);

        $success_message = "Project status updated to '$new_status' successfully!";
        
        // Refresh page to show updated data
        header('Location: ' . $_SERVER['REQUEST_URI'] . '?success=1');
        exit;
    }

    // Show success message from URL parameter
    if (isset($_GET['success'])) {
        $success_message = "Status updated successfully!";
    }

    // Fetch projects with their latest status and keep old comments visible
    $project_sql = "
        SELECT ap.*, 
               COALESCE(ps.status, 'Assigned') AS current_status,
               ps.status_date,
               ps.status_comment,
               ps.updated_by
        FROM assign_project ap
        LEFT JOIN (
            SELECT *, 
                   ROW_NUMBER() OVER (PARTITION BY project_id ORDER BY status_date DESC) AS rn
            FROM project_status
        ) ps ON ap.id = ps.project_id AND ps.rn = 1
        WHERE ap.employee_id = ?
        ORDER BY ap.start_date ASC
    ";

    $proj_stmt = $pdo->prepare($project_sql);
    $proj_stmt->execute([$employee_database_id]);
    $projects = $proj_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error_message = 'Error: ' . $e->getMessage();
}

function getStatusBadge($status) {
    $badges = [
        'Assigned' => ['badge bg-primary', 'fas fa-clipboard-list'],
        'In Progress' => ['badge bg-warning text-dark', 'fas fa-spinner'],
        'Completed' => ['badge bg-success', 'fas fa-check-circle'],
        'On Hold' => ['badge bg-secondary', 'fas fa-pause-circle'],
        'Cancelled' => ['badge bg-danger', 'fas fa-times-circle']
    ];
    
    $status = $status ?: 'Assigned';
    return $badges[$status] ?? ['badge bg-secondary', 'fas fa-question-circle'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Assigned Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .navbar {
            background: rgba(0, 0, 0, 0.4) !important;
            backdrop-filter: blur(15px);
            margin-bottom: 20px;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .table {
            color: #333;
            margin: 0;
        }
        
        .table th {
            background: rgba(102, 126, 234, 0.1);
            border: none;
            padding: 15px 10px;
            font-weight: 600;
            color: #495057;
        }
        
        .table td {
            padding: 15px 10px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .update-section {
            background: rgba(248, 249, 250, 0.1);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        
        .btn-update {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .success-message {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 15px;
            color: white;
            padding: 15px 20px;
            margin: 20px 0;
            animation: slideInDown 0.5s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .comment-text {
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-briefcase me-3"></i>Employee Portal
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
                <a class="nav-link active" href="my_project.php">
                    <i class="fas fa-project-diagram me-2"></i>My Projects
                </a>
                <a class="nav-link" href="../logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- Header -->
        <div class="main-container">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-user-circle me-3"></i>My Project Dashboard
                </h1>
                <p class="lead">Welcome back, <strong><?= htmlspecialchars($employee['full_name'] ?? $username) ?></strong>!</p>
            </div>

            <!-- Success Message -->
            <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                </div>
            <?php endif; ?>

            <!-- Error Messages -->
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error_message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Employee Stats -->
            <?php if ($employee): ?>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4><i class="fas fa-user text-primary"></i></h4>
                        <h5><?= htmlspecialchars($employee['full_name']) ?></h5>
                        <small>Employee Name</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4><i class="fas fa-id-badge text-success"></i></h4>
                        <h5><?= $employee['user_id'] ?></h5>
                        <small>Employee ID</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4><i class="fas fa-building text-warning"></i></h4>
                        <h5><?= htmlspecialchars($employee['department']) ?></h5>
                        <small>Department</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4><i class="fas fa-tasks text-info"></i></h4>
                        <h5><?= count($projects) ?></h5>
                        <small>Total Projects</small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Projects Table -->
        <div class="main-container">
            <h4 class="mb-4">
                <i class="fas fa-project-diagram me-2"></i>My Assigned Projects (<?= count($projects) ?>)
            </h4>
            
            <div class="table-container">
                <?php if (empty($projects)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-5x mb-4 text-muted"></i>
                        <h3 class="text-muted">No Projects Found</h3>
                        <p class="text-muted">No projects assigned to you yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Current Status</th>
                                    <th>Last Updated</th>
                                    <th>Previous Comments</th>
                                    <th>Quick Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project): ?>
                                <?php list($badgeClass, $badgeIcon) = getStatusBadge($project['current_status']); ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($project['project_name']) ?></strong>
                                        <?php if ($project['description']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars(substr($project['description'], 0, 50)) ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($project['start_date'])) ?></td>
                                    <td><?= $project['end_date'] ? date('M d, Y', strtotime($project['end_date'])) : '-' ?></td>
                                    <td>
                                        <span class="status-badge <?= $badgeClass ?>">
                                            <i class="<?= $badgeIcon ?>"></i>
                                            <?= htmlspecialchars($project['current_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= $project['status_date'] ? date('M d, H:i', strtotime($project['status_date'])) : 'Not updated' ?>
                                    </td>
                                    <td>
                                        <?php if ($project['status_comment']): ?>
                                            <div class="comment-text">
                                                <i class="fas fa-comment me-1"></i>
                                                <?= htmlspecialchars($project['status_comment']) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">No comments</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="update-section">
                                            <form method="POST" onsubmit="return confirm('Update status to selected option?');">
                                                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                                                
                                                <select class="form-select form-select-sm" name="status" required>
                                                    <option value="Assigned" <?= $project['current_status'] === 'Assigned' ? 'selected' : '' ?>>📋 Assigned</option>
                                                    <option value="In Progress" <?= $project['current_status'] === 'In Progress' ? 'selected' : '' ?>>⚡ In Progress</option>
                                                    <option value="Completed" <?= $project['current_status'] === 'Completed' ? 'selected' : '' ?>>✅ Completed</option>
                                                    <option value="On Hold" <?= $project['current_status'] === 'On Hold' ? 'selected' : '' ?>>⏸️ On Hold</option>
                                                    <option value="Cancelled" <?= $project['current_status'] === 'Cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                                                </select>
                                                
                                                <button type="submit" name="update_status" class="btn-update btn-sm">
                                                    <i class="fas fa-sync-alt me-2"></i>Update Status
                                                </button>
                                            </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide success message after 3 seconds
        setTimeout(function() {
            const successMsg = document.querySelector('.success-message');
            if (successMsg) {
                successMsg.style.transition = 'opacity 0.5s ease-out';
                successMsg.style.opacity = '0';
                setTimeout(() => successMsg.remove(), 500);
            }
        }, 3000);
    </script>
</body>
</html>
