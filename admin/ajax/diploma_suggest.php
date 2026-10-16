<?php
session_start();

if (empty($_SESSION['adminid'])) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

include('../classes/connection.php');

$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
$excludeId = isset($_GET['exclude_id']) ? (int) $_GET['exclude_id'] : 0;
$response = [];

if ($searchTerm !== '') {
    $likeTerm = '%' . $searchTerm . '%';

    if ($excludeId > 0) {
        $stmt = $db->prepare('SELECT diplomaid, name FROM tbl_diploma WHERE name LIKE ? AND diplomaid <> ? ORDER BY name ASC LIMIT 20');
        $stmt->bind_param('si', $likeTerm, $excludeId);
    } else {
        $stmt = $db->prepare('SELECT diplomaid, name FROM tbl_diploma WHERE name LIKE ? ORDER BY name ASC LIMIT 20');
        $stmt->bind_param('s', $likeTerm);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = [
            'id' => (int) $row['diplomaid'],
            'name' => $row['name'],
        ];
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
