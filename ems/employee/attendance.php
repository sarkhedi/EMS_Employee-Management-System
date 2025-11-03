<?php
session_start();
if ($_SESSION['role'] !== 'employee') { header("Location: ../login.php"); exit; }
require '../config/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM employees WHERE user_id=?");
$stmt->execute([$user_id]);
$emp_id = $stmt->fetchColumn();

// Mark attendance today
if (isset($_POST['mark_attendance'])) {
    $status = $_POST['status'];
    $today = date('Y-m-d');

    $check = $pdo->prepare("SELECT id FROM attendance WHERE employee_id=? AND date=?");
    $check->execute([$emp_id, $today]);
    if ($check->rowCount() == 0) {
        $pdo->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?,?,?)")
            ->execute([$emp_id,$today,$status]);
        echo "<div class='alert alert-success'>Attendance marked as $status for today</div>";
    } else {
        echo "<div class='alert alert-warning'>Already marked today</div>";
    }
}

$list = $pdo->prepare("SELECT * FROM attendance WHERE employee_id=? ORDER BY date DESC");
$list->execute([$emp_id]);
?>
<h2>Attendance</h2>
<form method="POST" class="mb-3">
    <select name="status" class="form-control mb-2">
        <option value="Present">Present</option>
        <option value="Leave">Leave</option>
    </select>
    <button type="submit" name="mark_attendance" class="btn btn-primary">Mark Attendance</button>
</form>
<h4>History</h4>
<table class="table table-bordered">
    <tr><th>Date</th><th>Status</th></tr>
    <?php foreach($list as $a): ?>
    <tr>
        <td><?= $a['date']; ?></td>
        <td><?= $a['status']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>
