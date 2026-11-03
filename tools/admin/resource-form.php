<?php
require __DIR__.'/_boot.php';require_once __DIR__.'/../includes/resources.php';ohsc_resources_ensure($db);
$id=isset($_GET['id'])?(int)$_GET['id']:0;
$item=array('resource_id'=>0,'title'=>'','summary'=>'','category'=>'templates','goal'=>'find-job','image'=>'','sort_order'=>0,'is_featured'=>0,'is_active'=>0);
if ($id) {
    $stmt=$db->prepare('SELECT * FROM tbl_career_resources WHERE resource_id=?');$stmt->bind_param('i',$id);$stmt->execute();$row=$stmt->get_result()->fetch_assoc();$stmt->close();
    if (!$row) { http_response_code(404);ohsc_tools_admin_open('Resource not found');echo '<p>This resource does not exist.</p>';ohsc_tools_admin_close();exit; } $item=$row;
}
$error='';$files=$id?ohsc_resource_files($db,$id):array();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!ohsc_tools_csrf_ok()) { $error='The form expired or upload exceeded the server limit. Reload and try a smaller file.'; }
    else {
        foreach (array('title','summary','category','goal') as $key) { $item[$key]=isset($_POST[$key])&&is_string($_POST[$key])?trim($_POST[$key]):''; }
        $item['sort_order']=isset($_POST['sort_order'])?(int)$_POST['sort_order']:0;$item['is_featured']=!empty($_POST['is_featured'])?1:0;$item['is_active']=!empty($_POST['is_active'])?1:0;
        if ($item['title']==='' || strlen($item['title'])>150 || strlen($item['summary'])>500) { $error='Title is required (maximum 150 characters); summary maximum 500 characters.'; }
        elseif (!isset(ohsc_resource_categories()[$item['category']]) || !isset(ohsc_resource_goals()[$item['goal']])) { $error='Choose a valid category and goal.'; }
        $remove=isset($_POST['remove_files'])&&is_array($_POST['remove_files'])?array_map('intval',$_POST['remove_files']):array();
        $remaining=array_filter($files,function($f)use($remove){return !in_array((int)$f['file_id'],$remove,true);});
        $pending=array();$uploaded=isset($_FILES['downloads'])?$_FILES['downloads']:null;
        if ($uploaded && isset($uploaded['name']) && is_array($uploaded['name'])) {
            if (count($uploaded['name'])>5) { $error='Upload at most five files at a time.'; }
            foreach ($uploaded['name'] as $i=>$name) {
                if ($error!=='') { break; }
                $code=$uploaded['error'][$i];if ($code===UPLOAD_ERR_NO_FILE) { continue; }
                $tmp=$uploaded['tmp_name'][$i];$size=$uploaded['size'][$i];
                if ($code!==UPLOAD_ERR_OK || !is_uploaded_file($tmp)) { $error='A file could not upload. Check the server size limit below.';break; }
                $error=ohsc_resource_validate_file($tmp,$name,$size);if ($error!=='') { break; }
                $ext=strtolower(pathinfo($name,PATHINFO_EXTENSION));
                $pending[]=array('tmp'=>$tmp,'filename'=>bin2hex(random_bytes(16)).'.'.$ext,'name'=>substr(preg_replace('/[\x00-\x1f\x7f]/','',basename(str_replace('\\','/',$name))),0,180),'ext'=>$ext,'size'=>$size);
            }
        }
        $imageUpload=isset($_FILES['thumbnail'])?$_FILES['thumbnail']:null;$newImage=null;
        if ($error==='' && $imageUpload && $imageUpload['error']!==UPLOAD_ERR_NO_FILE) {
            if ($imageUpload['error']!==UPLOAD_ERR_OK || !is_uploaded_file($imageUpload['tmp_name'])) { $error='Thumbnail upload failed.'; }
            else { $error=ohsc_image_validate($imageUpload['tmp_name'],$imageUpload['name'],$imageUpload['size'],array('width'=>640,'height'=>400));if ($error==='') { $newImage=bin2hex(random_bytes(16)).'.webp'; } }
        }
        if ($error==='' && $item['is_active'] && count($remaining)+count($pending)===0) { $error='Add at least one downloadable file before publishing.'; }
        if ($error==='') {
            $moved=array();$oldImage=$item['image'];$db->begin_transaction();
            try {
                if ($newImage) {
                    $imageDir=dirname(__DIR__).'/uploads';if (!is_dir($imageDir)) { mkdir($imageDir,0755,true); }
                    $target=$imageDir.'/'.$newImage;if (!move_uploaded_file($imageUpload['tmp_name'],$target)) { throw new RuntimeException('Could not store thumbnail.'); }$moved[]=$target;$item['image']=$newImage;
                }
                $stmt=$db->prepare('INSERT INTO tbl_career_resources(resource_id,title,summary,category,goal,image,sort_order,is_featured,is_active,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE title=VALUES(title),summary=VALUES(summary),category=VALUES(category),goal=VALUES(goal),image=VALUES(image),sort_order=VALUES(sort_order),is_featured=VALUES(is_featured),is_active=VALUES(is_active),updated_at=NOW()');
                $stmt->bind_param('isssssiii',$id,$item['title'],$item['summary'],$item['category'],$item['goal'],$item['image'],$item['sort_order'],$item['is_featured'],$item['is_active']);$stmt->execute();if (!$id) { $id=(int)$db->insert_id; }$stmt->close();
                foreach ($remove as $fileId) { $stmt=$db->prepare('DELETE FROM tbl_career_resource_files WHERE file_id=? AND resource_id=?');$stmt->bind_param('ii',$fileId,$id);$stmt->execute();$stmt->close(); }
                foreach ($pending as $file) {
                    $target=ohsc_resource_file_path($file['filename']);if (!move_uploaded_file($file['tmp'],$target)) { throw new RuntimeException('Could not store download. Check directory permissions.'); }$moved[]=$target;
                    $mime=ohsc_resource_mime($file['ext']);$stmt=$db->prepare('INSERT INTO tbl_career_resource_files(resource_id,filename,original_name,extension,mime,bytes) VALUES(?,?,?,?,?,?)');$stmt->bind_param('issssi',$id,$file['filename'],$file['name'],$file['ext'],$mime,$file['size']);$stmt->execute();$stmt->close();
                }
                $db->commit();header('Location: '.$base_url.'/admin/career-resource?id='.$id.'&saved=1');exit;
            } catch (Throwable $e) { $db->rollback();foreach ($moved as $path) { if (is_file($path)) { unlink($path); } }$item['image']=$oldImage;$error='Could not save the resource. Please retry or check storage/database permissions.'; }
        }
    }
}
ohsc_tools_admin_open($item['resource_id']?'Edit resource':'Add resource');
if ($error) { echo '<p class="tools-error" role="alert">'.ohsc_h($error).'</p>'; }
if (!empty($_GET['saved'])) { echo '<p role="status">Resource saved.</p>'; }
?>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
<p><label>Title<br><input type="text" name="title" value="<?php echo ohsc_h($item['title']); ?>" maxlength="150" required></label></p>
<p><label>Short description<br><textarea name="summary" maxlength="500" style="min-height:90px"><?php echo ohsc_h($item['summary']); ?></textarea></label></p>
<p><label>Category <select name="category"><?php foreach (ohsc_resource_categories() as $k=>$label) { ?><option value="<?php echo ohsc_h($k); ?>" <?php echo $item['category']===$k?'selected':''; ?>><?php echo ohsc_h($label); ?></option><?php } ?></select></label></p>
<p><label>Career goal <select name="goal"><?php foreach (ohsc_resource_goals() as $k=>$label) { ?><option value="<?php echo ohsc_h($k); ?>" <?php echo $item['goal']===$k?'selected':''; ?>><?php echo ohsc_h($label); ?></option><?php } ?></select></label></p>
<p><label>Display order<br><input type="number" name="sort_order" value="<?php echo (int)$item['sort_order']; ?>"></label></p>
<h3>Resource image</h3>
<?php if ($item['image']) { ?><p><img src="<?php echo ohsc_h($base_url.'/tools/uploads/'.$item['image']); ?>" alt="Current thumbnail" style="max-width:240px"></p><?php } ?>
<p><label>Upload / replace thumbnail (optional): WebP, exactly <strong>640 × 400 px</strong>, maximum 5 MB<br><input type="file" name="thumbnail" accept="image/webp,.webp"></label></p>
<p>A document illustration is shown when no thumbnail is uploaded.</p>
<h3>Downloadable files</h3>
<?php foreach ($files as $f) { ?><p><?php echo ohsc_h($f['original_name']); ?> (<?php echo number_format($f['bytes']/1024,1); ?> KB) <label><input type="checkbox" name="remove_files[]" value="<?php echo (int)$f['file_id']; ?>"> Remove this download</label></p><?php } ?>
<p><label>Add files (up to 5 at a time)<br><input type="file" name="downloads[]" accept=".pdf,.docx,.xlsx,.pptx,.txt,.zip" multiple></label></p>
<p>PDF, Word (.docx), Excel (.xlsx), PowerPoint (.pptx), TXT or ZIP. Maximum 10 MB each, subject to server limits: upload_max_filesize=<?php echo ohsc_h(ini_get('upload_max_filesize')); ?>; post_max_size=<?php echo ohsc_h(ini_get('post_max_size')); ?>. Files are downloaded as attachments. ZIP files must not contain scripts, executable files or nested archives.</p>
<p><label><input type="checkbox" name="is_featured" value="1" <?php echo $item['is_featured']?'checked':''; ?>> Featured / show first</label></p>
<p><label><input type="checkbox" name="is_active" value="1" <?php echo $item['is_active']?'checked':''; ?>> Publish resource (requires at least one file)</label></p>
<p><button class="save-btn">Save resource</button> <a href="<?php echo ohsc_h($base_url); ?>/admin/career-resources">Back to resources</a></p></form>
<?php ohsc_tools_admin_close(); ?>
