<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

$dataPoints = [];
$labels = [];

$result = $conn->query("SELECT DATE(created_at) AS date, SUM(view_count) AS views 
FROM poetry_content 
GROUP BY DATE(created_at) 
ORDER BY date DESC 
LIMIT 7");

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date'];
    $dataPoints[] = $row['views'];
}

echo json_encode([
    'labels' => array_reverse($labels),
    'dataPoints' => array_reverse($dataPoints)
]);
?>
