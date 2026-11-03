<?php
require_once __DIR__.'/../includes/lock.php';
require __DIR__.'/../../admin/classes/connection.php';
require_once __DIR__.'/../includes/resources.php';
ohsc_resources_ensure($db);
$id=isset($_GET['id'])?(int)$_GET['id']:0;
$stmt=$db->prepare('SELECT f.* FROM tbl_career_resource_files f INNER JOIN tbl_career_resources r ON r.resource_id=f.resource_id WHERE f.file_id=? AND r.is_active=1 AND r.is_deleted=0');
$stmt->bind_param('i',$id);$stmt->execute();$file=$stmt->get_result()->fetch_assoc();$stmt->close();
$path=$file?ohsc_resource_file_path($file['filename']):null;
if (!$path || !is_file($path)) { http_response_code(404);header('Content-Type: text/plain; charset=UTF-8');exit('This download is unavailable.'); }
$name=preg_replace('/[^A-Za-z0-9._ -]/','_', $file['original_name']);
header('Content-Type: '.ohsc_resource_mime($file['extension']));
header('Content-Disposition: attachment; filename="'.$name.'"');
header('Content-Length: '.filesize($path));
header('X-Content-Type-Options: nosniff');header('Cache-Control: private, no-store');
if (session_status()===PHP_SESSION_ACTIVE) { session_write_close(); }
while (ob_get_level()) { ob_end_clean(); }
readfile($path);exit;
