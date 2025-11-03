<?php
session_start();
require '../config/db.php';

echo "<h2>🔍 DEBUG MODE</h2>";
echo "<p><strong>Your Session User ID:</strong> " . ($_SESSION['user_id'] ?? 'NOT SET') . "</p>";
echo "<p><strong>Your Role:</strong> " . ($_SESSION['role'] ?? 'NOT SET') . "</p>";

// Check database
try {
    
    $all_stmt = $pdo->query("SELECT * FROM assign_project ORDER BY id DESC");
    $all_projects = $all_stmt->fetchAll();
    
    echo "<h3>Database માં બધા Projects:</h3>";
    echo "<table border='1' style='border-collapse:collapse; padding:10px;'>";
    echo "<tr style='background:#333; color:white;'>";
    echo "<th>ID</th><th>Project Name</th><th>Employee ID</th><th>Start Date</th><th>End Date</th><th>Match?</th>";
    echo "</tr>";
    
    foreach ($all_projects as $project) {
        $is_yours = ($project['employee_id'] == ($_SESSION['user_id'] ?? 0));
        $row_color = $is_yours ? 'background-color: green; color: white;' : '';
        
        echo "<tr style='$row_color'>";
        echo "<td>{$project['id']}</td>";
        echo "<td>" . htmlspecialchars($project['project_name']) . "</td>";
        echo "<td>{$project['employee_id']}</td>";
        echo "<td>{$project['start_date']}</td>";
        echo "<td>{$project['end_date']}</td>";
        echo "<td>" . ($is_yours ? "✅ YES" : "❌ NO") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    //  specific query
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $my_stmt = $pdo->prepare("SELECT * FROM assign_project WHERE employee_id = ?");
        $my_stmt->execute([$user_id]);
        $my_projects = $my_stmt->fetchAll();
        
        echo "<h3>Your Projects (Employee ID: $user_id):</h3>";
        if (empty($my_projects)) {
            echo "<p style='color:red;'>❌ NO PROJECTS FOUND!</p>";
            echo "<p><strong>Solution:</strong> Admin should assign projects to Employee ID: $user_id</p>";
        } else {
            echo "<p style='color:green;'>✅ Found " . count($my_projects) . " projects!</p>";
            foreach ($my_projects as $mp) {
                echo "<p>• " . htmlspecialchars($mp['project_name']) . "</p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
