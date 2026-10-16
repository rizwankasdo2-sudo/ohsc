<?php
// Force JSON response
header('Content-Type: application/json');

// Directory to save uploaded images
$targetDir = "uploads/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (isset($_FILES['upload'])) {
    $fileName = basename($_FILES['upload']['name']);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES['upload']['tmp_name'], $targetFile)) {
        // URL to access the uploaded file
        $url = $targetFile;

        echo json_encode([
            "url" => 'https://www.oxfordhomestudy.com/admin/' . $targetFile
        ]);
        exit;
    } else {
        echo json_encode([
            "uploaded" => 0,
            "error" => [ "message" => "Failed to move uploaded file." ]
        ]);
        exit;
    }
}

echo json_encode([
    "uploaded" => 0,
    "error" => [ "message" => "No file uploaded." ]
]);
exit;
?>
