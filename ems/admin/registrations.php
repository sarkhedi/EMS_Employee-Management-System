<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit; 
}
require '../config/db.php';
include '../includes/header.php';

// Handle approve/reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $admin_id = $_SESSION['user_id'];
    
    if ($action === 'approve') {
        try {
            // Get registration data
            $stmt = $pdo->prepare("SELECT * FROM user_registrations WHERE id = ? AND status = 'pending'");
            $stmt->execute([$id]);
            $registration = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($registration) {
                // Create user account
                $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'employee')")
                    ->execute([$registration['username'], $registration['password']]);
                
                $user_id = $pdo->lastInsertId();
                
                // Create employee record
                $pdo->prepare("INSERT INTO employees (user_id, full_name, email, phone, department, position, join_date) VALUES (?, ?, ?, ?, ?, ?, CURDATE())")
                    ->execute([$user_id, $registration['full_name'], $registration['email'], $registration['phone'], $registration['department'], $registration['position']]);
                
                // Update registration status
                $pdo->prepare("UPDATE user_registrations SET status = 'approved', reviewed_by = ?, reviewed_date = NOW() WHERE id = ?")
                    ->execute([$admin_id, $id]);
                
                echo "<div class='alert alert-success'>Registration approved successfully! User can now login.</div>";
            }
        } catch(PDOException $e) {
            echo "<div class='alert alert-danger'>Error approving registration: " . $e->getMessage() . "</div>";
        }
    } elseif ($action === 'reject') {
        $reason = $_POST['rejection_reason'] ?? 'No reason provided';
        
        try {
            $pdo->prepare("UPDATE user_registrations SET status = 'rejected', reviewed_by = ?, reviewed_date = NOW(), rejection_reason = ? WHERE id = ?")
                ->execute([$admin_id, $reason, $id]);
            
            echo "<div class='alert alert-warning'>Registration rejected successfully.</div>";
        } catch(PDOException $e) {
            echo "<div class='alert alert-danger'>Error rejecting registration.</div>";
        }
    }
}

// Get all registrations
try {
    $stmt = $pdo->query("SELECT ur.*, u.username as reviewed_by_name FROM user_registrations ur LEFT JOIN users u ON ur.reviewed_by = u.id ORDER BY ur.applied_date DESC");
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $registrations = [];
}
?>

<style>
    .registration-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .registration-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-pending {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .status-approved {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .status-rejected {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
</style>

<h2><i class="fas fa-user-clock"></i> Registration Applications</h2>
<p class="text-white-50 mb-4">Review and approve employee registration requests</p>

<?php if (count($registrations) > 0): ?>
    <?php foreach($registrations as $reg): ?>
    <div class="registration-card">
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-user" style="color: white;"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-1"><?= htmlspecialchars($reg['full_name']); ?></h5>
                        <small class="text-muted">Applied on <?= date('M j, Y', strtotime($reg['applied_date'])); ?></small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <strong class="text-white-50">Email:</strong>
                        <span class="text-white"><?= htmlspecialchars($reg['email']); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <strong class="text-white-50">Username:</strong>
                        <span class="text-white"><?= htmlspecialchars($reg['username']); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <strong class="text-white-50">Phone:</strong>
                        <span class="text-white"><?= htmlspecialchars($reg['phone'] ?? 'Not provided'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <strong class="text-white-50">Department:</strong>
                        <span class="text-white"><?= htmlspecialchars($reg['department'] ?? 'Not specified'); ?></span>
                    </div>
                </div>
                <div class="mt-2">
                    <strong class="text-white-50">Position:</strong>
                    <span class="text-white"><?= htmlspecialchars($reg['position'] ?? 'Not specified'); ?></span>
                </div>
                
                <?php if ($reg['rejection_reason']): ?>
                <div class="mt-2">
                    <strong class="text-white-50">Rejection Reason:</strong>
                    <span class="text-white"><?= htmlspecialchars($reg['rejection_reason']); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4 text-end">
                <div class="mb-3">
                    <span class="status-badge status-<?= $reg['status']; ?>">
                        <?= ucfirst($reg['status']); ?>
                    </span>
                </div>
                
                <?php if ($reg['status'] === 'pending'): ?>
                    <div class="d-grid gap-2">
                        <a href="?action=approve&id=<?= $reg['id']; ?>" 
                           class="btn btn-success btn-sm"
                           onclick="return confirm('Approve this registration? User will be able to login immediately.')">
                            <i class="fas fa-check"></i> Approve
                        </a>
                        <button class="btn btn-danger btn-sm" 
                                onclick="showRejectModal(<?= $reg['id']; ?>)">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                <?php else: ?>
                    <small class="text-muted">
                        Reviewed on <?= date('M j, Y', strtotime($reg['reviewed_date'])); ?>
                        <?php if ($reg['reviewed_by_name']): ?>
                        <br>by <?= htmlspecialchars($reg['reviewed_by_name']); ?>
                        <?php endif; ?>
                    </small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-5x mb-3" style="opacity: 0.5;"></i>
        <h4 class="text-white">No Registration Applications</h4>
        <p class="text-muted">Employee registration requests will appear here</p>
    </div>
<?php endif; ?>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2);">
            <form method="POST" id="rejectForm">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="modal-title text-white">Reject Registration</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label text-white">Reason for rejection:</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" 
                                  style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); color: white;"
                                  placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.2);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '?action=reject&id=' + id;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>

<?php include '../includes/footer.php'; ?>
