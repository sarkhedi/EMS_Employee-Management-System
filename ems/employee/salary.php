<?php
session_start();
if ($_SESSION['role'] !== 'employee') { header("Location: ../login.php"); exit; }
require '../config/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM employees WHERE user_id=?");
$stmt->execute([$user_id]);
$emp_id = $stmt->fetchColumn();

$list = $pdo->prepare("SELECT * FROM payroll WHERE employee_id=? ORDER BY pay_date DESC");
$list->execute([$emp_id]);
?>
<h2>My Salary Details</h2>
<table class="table table-bordered">
    <tr><th>Pay Date</th><th>Basic</th><th>Allowances</th><th>Deductions</th><th>Net Salary</th></tr>
    <?php foreach($list as $p): ?>
    <tr>
        <td><?= $p['pay_date']; ?></td>
        <td><?= $p['basic_salary']; ?></td>
        <td><?= $p['allowances']; ?></td>
        <td><?= $p['deductions']; ?></td>
        <td><?= $p['net_salary']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>
