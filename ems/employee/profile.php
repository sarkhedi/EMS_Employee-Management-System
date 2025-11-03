<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}
require '../config/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch employee data safely
$stmt = $pdo->prepare("SELECT * FROM employees WHERE user_id = ?");
$stmt->execute([$user_id]);
$emp = $stmt->fetch(PDO::FETCH_ASSOC);

$success_message = $error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch and sanitize input
    $full_name  = filter_var(trim($_POST['full_name']), FILTER_SANITIZE_STRING);
    $email      = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone      = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
    $department = filter_var(trim($_POST['department']), FILTER_SANITIZE_STRING);
    $position   = filter_var(trim($_POST['position']), FILTER_SANITIZE_STRING);

    // Validate required fields
    if (!$full_name) {
        $error_message = "Full name is required.";
    } elseif ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please provide a valid email address.";
    } else {
        try {
            $update_stmt = $pdo->prepare("UPDATE employees SET full_name = ?, email = ?, phone = ?, department = ?, position = ? WHERE user_id = ?");
            $update_stmt->execute([$full_name, $email, $phone, $department, $position, $user_id]);

            $success_message = "<div class='alert alert-success'>Profile updated successfully.</div>";

            // Refresh employee data
            $stmt->execute([$user_id]);
            $emp = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error_message = "Error updating profile. Please try again later.";
        }
    }
}
?>

<h2 class="mb-4">My Profile</h2>

<?php if ($error_message): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>
<?= $success_message ?>

<form method="POST" novalidate>
    <div class="mb-3">
        <label for="full_name" class="form-label fw-semibold">Full Name *</label>
        <input type="text" id="full_name" name="full_name" class="form-control" required value="<?= htmlspecialchars($emp['full_name'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($emp['email'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label fw-semibold">Phone Number</label>
        <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($emp['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="department" class="form-label fw-semibold">Department</label>
        <input type="text" id="department" name="department" class="form-control" value="<?= htmlspecialchars($emp['department'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="position" class="form-label fw-semibold">Position</label>
        <input type="text" id="position" name="position" class="form-control" value="<?= htmlspecialchars($emp['position'] ?? '') ?>">
    </div>

    <button type="submit" class="btn btn-primary px-4">Update Profile</button>
</form>

<?php include '../includes/footer.php'; ?>

