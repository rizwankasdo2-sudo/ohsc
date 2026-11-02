<?php
$imageMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_action'])) {
    if (!ohsc_tools_csrf_ok()) { $error = 'The form expired. Reload and try again.'; }
    elseif (!$tool['tool_id']) { $error = 'Save the tool before uploading images.'; }
    else {
        $inventory = ohsc_image_inventory($db, $tool['slug']);
        $source = isset($_POST['image_source']) && is_string($_POST['image_source']) ? $_POST['image_source'] : '';
        $upload = isset($_FILES['tool_image']) ? $_FILES['tool_image'] : null;
        if (!isset($inventory[$source])) { $error = 'Unknown image slot. Reload the page.'; }
        elseif (!$upload || !is_array($upload) || !isset($upload['error'], $upload['tmp_name'], $upload['name'], $upload['size']) || !is_string($upload['tmp_name']) || !is_string($upload['name']) || !is_int($upload['error']) || $upload['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'])) { $error = 'Upload failed. Select one WebP file within the server upload limit.'; }
        else {
            $error = ohsc_image_validate($upload['tmp_name'], $upload['name'], $upload['size'], $inventory[$source]);
            if ($error === '') {
                $dir = dirname(__DIR__) . '/uploads';
                if (!is_dir($dir)) { mkdir($dir, 0755, true); }
                $filename = bin2hex(random_bytes(16)) . '.webp';
                $target = $dir . '/' . $filename;
                if (!move_uploaded_file($upload['tmp_name'], $target)) { $error = 'Could not save the image. Check upload directory permissions.'; }
                else {
                    ohsc_image_table($db);
                    $stmt = $db->prepare('INSERT INTO tbl_tool_images (tool_slug, source, filename) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE filename=VALUES(filename)');
                    $stmt->bind_param('sss', $tool['slug'], $source, $filename);
                    if ($stmt->execute()) { $imageMessage = 'Image updated. The public tool now uses this WebP image.'; }
                    else { unlink($target); $error = 'Could not save the image record. Please try again.'; }
                    $stmt->close();
                }
            }
        }
    }
}
