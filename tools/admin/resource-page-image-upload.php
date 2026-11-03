<?php
if (!function_exists('ohsc_tools_csrf_ok') || empty($_SESSION['adminid'])) { http_response_code(403);exit; }

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['image_action'])) {
    if (!ohsc_tools_csrf_ok()) { $error='The form expired. Reload and try again.'; }
    elseif (!$item) { $error='Choose a resource page.'; }
    else {
        $specs=ohsc_resource_image_specs($key);$slot=isset($_POST['image_slot'])&&is_string($_POST['image_slot'])?$_POST['image_slot']:'';$action=is_string($_POST['image_action'])?$_POST['image_action']:'';
        if (!isset($specs[$slot]) || !in_array($action,array('upload','delete','reset'),true)) { $error='Choose a valid image slot and action.'; }
        elseif ($action==='delete') { ohsc_resource_image_store($db,$key,$slot,'',1); }
        elseif ($action==='reset') { ohsc_resource_images_table($db);$stmt=$db->prepare('DELETE FROM tbl_resource_page_images WHERE page_key=? AND slot=?');$stmt->bind_param('ss',$key,$slot);$stmt->execute();$stmt->close(); }
        else {
            $u=$_FILES['page_image']??null;
            if (!$u || !is_array($u) || !isset($u['error'],$u['tmp_name'],$u['name'],$u['size']) || $u['error']!==UPLOAD_ERR_OK || !is_string($u['tmp_name']) || !is_string($u['name']) || !is_uploaded_file($u['tmp_name'])) { $error='Select one WebP file within the server upload limit.'; }
            else {
                $error=ohsc_image_validate($u['tmp_name'],$u['name'],$u['size'],$specs[$slot]);
                if ($error==='') { $dir=dirname(__DIR__).'/uploads';if (!is_dir($dir)) { mkdir($dir,0755,true); }$filename=bin2hex(random_bytes(16)).'.webp';$path=$dir.'/'.$filename;
                    if (!move_uploaded_file($u['tmp_name'],$path)) { $error='Could not save the image. Check upload folder permissions.'; }
                    else { try { ohsc_resource_image_store($db,$key,$slot,$filename,0); } catch (Throwable $e) { unlink($path);$error='Could not save the image record.'; } }
                }
            }
        }
        if ($error==='') { header('Location: '.(isset($resourceImageReturn)?$resourceImageReturn:$base_url.'/admin/resource-pages?page='.rawurlencode($key).'&image_saved=1#page-images'));exit; }
    }
}
