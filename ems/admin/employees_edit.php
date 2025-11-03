<?php
session_start();
if ($_SESSION['role'] !== 'admin') { header("Location: ../login.php"); exit; }
require '../config/db.php';
include '../includes/header.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id=?");
$stmt->execute([$id]);
$emp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emp) { echo "Employee not found"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $join_date = $_POST['join_date'];

    $pdo->prepare("UPDATE employees SET full_name=?, email=?, phone=?, department=?, position=?, join_date=? WHERE id=?")
        ->execute([$full_name, $email, $phone, $department, $position, $join_date, $id]);

    echo "<div class='alert alert-success'>Employee updated successfully</div>";
}
?>
<h2>Edit Employee</h2>
<form method="POST">
    <input type="text" name="full_name" value="<?= $emp['full_name']; ?>" class="form-control mb-2">
    <input type="email" name="email" value="<?= $emp['email']; ?>" class="form-control mb-2">
    <input type="text" name="phone" value="<?= $emp['phone']; ?>" class="form-control mb-2">
    <input type="text" name="department" value="<?= $emp['department']; ?>" class="form-control mb-2">
    <input type="text" name="position" value="<?= $emp['position']; ?>" class="form-control mb-2">
    <input type="date" name="join_date" value="<?= $emp['join_date']; ?>" class="form-control mb-2">
    <button type="submit" class="btn btn-warning">Update</button>
</form>
<?php include '../includes/footer.php'; ?>
