<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['HTTP_HOST']='localhost:300'; $_SERVER['REQUEST_URI']='/tools/typing-test/';
require __DIR__ . '/../../admin/classes/connection.php';
require __DIR__ . '/../includes/registry.php';
function image_check($value, $message) { if (!$value) { throw new RuntimeException($message); } }
ohsc_tools_ensure($db); ohsc_image_table($db);
foreach (array('typing-test','eq-test','career-quiz','cv-builder','interview-prep') as $slug) {
    $slots=ohsc_image_inventory($db,$slug);
    image_check(count($slots)>=5, 'Missing images for '.$slug);
    $tool=array('tool_id'=>1,'slug'=>$slug); $imageMessage=''; $_SESSION['ohsc_tools_csrf']='test';
    ob_start(); require __DIR__.'/../admin/tool-image-fields.php'; $editor=ob_get_clean();
    image_check(substr_count($editor,'name="tool_image"')===count($slots), 'Missing admin upload fields');
    image_check(substr_count($editor,'accept="image/webp,.webp"')===count($slots), 'Missing format restrictions');
    echo $slug.': '.count($slots)." slots and upload forms\n";
}
image_check(!ohsc_image_inventory($db,'../../admin'), 'Unknown slug accepted');
$tmp=tempnam(sys_get_temp_dir(),'webp-test-');
$im=imagecreatetruecolor(20,10); imagewebp($im,$tmp); imagedestroy($im);
$spec=array('width'=>20,'height'=>10);
image_check(ohsc_image_validate($tmp,'valid.webp',filesize($tmp),$spec)==='', 'Valid WebP rejected');
image_check(ohsc_image_validate($tmp,'invalid.png',filesize($tmp),$spec)!=='', 'Wrong extension accepted');
image_check(ohsc_image_validate($tmp,'valid.webp',filesize($tmp),array('width'=>21,'height'=>10))!=='', 'Wrong dimensions accepted');
image_check(ohsc_image_validate($tmp,'valid.webp',6*1024*1024,$spec)!=='', 'Oversized upload accepted');
$im=imagecreatetruecolor(20,10);imagepng($im,$tmp);imagedestroy($im);
image_check(ohsc_image_validate($tmp,'renamed.webp',filesize($tmp),$spec)!=='', 'Renamed PNG accepted');
file_put_contents($tmp,'<?php echo "not an image";');
image_check(ohsc_image_validate($tmp,'fake.webp',filesize($tmp),$spec)!=='', 'Fake WebP accepted'); unlink($tmp);
$filename=bin2hex(random_bytes(16)).'.webp'; $file=__DIR__.'/../uploads/'.$filename;
$im=imagecreatetruecolor(20,10);imagewebp($im,$file);imagedestroy($im);
$db->begin_transaction();
try {
    $source='/tools/assets/hero-typing.jpg'; $slug='typing-test';
    $stmt=$db->prepare('INSERT INTO tbl_tool_images(tool_slug,source,filename) VALUES(?,?,?) ON DUPLICATE KEY UPDATE filename=VALUES(filename)');
    $stmt->bind_param('sss',$slug,$source,$filename); $stmt->execute();$stmt->close();
    image_check(isset(ohsc_image_overrides($db,$slug)[$source]),'Override not saved');
    image_check(!isset(ohsc_image_overrides($db,'cv-builder')[$source]),'Override leaked across tools');
    ob_start();
    ohsc_image_start($db,$slug,'http://localhost:300');
    echo '<img src="http://localhost:300/tools/assets/hero-typing.jpg"><img src="http://localhost:300/images/logo.png">';
    ob_end_flush();
    $html=ob_get_clean();
    image_check(strpos($html,'/tools/uploads/'.$filename)!==false, 'Public image did not change');
    image_check(strpos($html,'/images/logo.png')!==false, 'Shared logo changed');
} finally { $db->rollback();unlink($file); }
echo "\nImage validation and isolation checks passed.\n";
