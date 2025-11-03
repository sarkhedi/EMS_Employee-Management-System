<?php
session_start();
if ($_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}
require '../config/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM employees WHERE user_id=?");
$stmt->execute([$user_id]);
$emp_id = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date) VALUES (?,?,?,?)")
        ->execute([$emp_id, $_POST['leave_type'], $_POST['start_date'], $_POST['end_date']]);
    echo "<div class='alert alert-success'>Leave request submitted</div>";
}
?>

<style>
/* Glass-style container */
.container-glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 15px;
    padding: 30px;
    margin: 20px auto;
    color: white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Transparent form controls */
.form-select, .form-control {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    border-radius: 10px;
    padding: 10px 15px;
    transition: background-color 0.3s ease;
}

.form-select option {
    color: black; /* option text black for contrast */
}

/* On focus */
.form-select:focus, .form-control:focus {
    background: rgba(255, 255, 255, 0.3);
    color: black;
    outline: none;
    box-shadow: 0 0 8px 2px rgba(255, 255, 255, 0.5);
}

/* Submit button style */
.btn-primary {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    border-radius: 12px;
    padding: 10px 25px;
    font-weight: 600;
    box-shadow: 0 6px 12px rgba(32, 201, 151, 0.4);
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #20c997, #28a745);
}

h2 {
    color: white;
    font-weight: 600;
}
</style>

<div class="container-glass">
    <h2>Apply for Leave</h2>
    <form method="POST">
        <select name="leave_type" class="form-select mb-3" required>
            <option value="" disabled selected>Select Leave Type</option>
            <option value="Sick">Sick Leave</option>
            <option value="Casual">Casual Leave</option>
            <option value="Annual">Annual Leave</option>
            <option value="Maternity">Maternity Leave</option>
            <option value="Emergency">Emergency Leave</option>
        </select>
        <input type="date" name="start_date" class="form-control mb-3" required>
        <input type="date" name="end_date" class="form-control mb-4" required>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
