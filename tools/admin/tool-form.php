<?php
require __DIR__.'/_boot.php';
$toolId=isset($_GET['id'])?max(0,(int)$_GET['id']):0;
$tool=array('tool_id'=>0,'title'=>'','slug'=>'','summary'=>'','sort_order'=>1,'is_active'=>1);$error='';
if ($toolId>0) {
    $stmt=$db->prepare('SELECT tool_id,title,slug,summary,sort_order,is_active FROM tbl_site_tools WHERE tool_id=?');$stmt->bind_param('i',$toolId);$stmt->execute();$found=$stmt->get_result()->fetch_assoc();$stmt->close();
    if (!$found) { http_response_code(404);ohsc_tools_admin_open('Tool not found');echo '<p>Tool not found.</p>';ohsc_tools_admin_close();exit; }$tool=$found;
}
$physicalSlug=$tool['slug'];$seoPages=ohsc_tool_pages($db);$seoKey=isset($seoPages[$physicalSlug])?$physicalSlug:'';$resourceSeoPages=array();$seo=null;
if ($physicalSlug==='resources') { require_once __DIR__.'/../includes/resources.php';ohsc_resources_ensure($db);$resourceSeoPages=ohsc_resource_pages($db);$seo=$resourceSeoPages['hub']; }
elseif ($seoKey) { $seo=$seoPages[$seoKey]; }
$seoInput=$seo?array('slug'=>$seo['slug'],'meta_title'=>$seo['meta_title'],'meta_description'=>$seo['meta_description'],'meta_keywords'=>$seo['meta_keywords'],'meta_robots'=>$seo['meta_robots']):array();
if ($physicalSlug==='resources' && isset($_POST['image_slot'])) { $key='hub';$item=$resourceSeoPages['hub'];$resourceImageReturn=$base_url.'/admin/tool-edit?id='.$toolId.'&image_saved=1#page-images';require __DIR__.'/resource-page-image-upload.php'; }else { require __DIR__.'/tool-image-upload.php'; }
if ($_SERVER['REQUEST_METHOD']==='POST' && !isset($_POST['image_action'])) {
    if (!ohsc_tools_csrf_ok()) { $error='The form expired. Reload and try again.'; }
    elseif ((int)($_POST['tool_id']??0)!==$toolId) { $error='The selected tool changed. Reload and try again.'; }
    else {
        foreach (array('title','summary') as $f) { $tool[$f]=isset($_POST[$f])&&is_string($_POST[$f])?trim($_POST[$f]):''; }
        $tool['slug']=$physicalSlug!==''?$physicalSlug:(isset($_POST['slug'])&&is_string($_POST['slug'])?strtolower(trim($_POST['slug'])):'');
        $tool['sort_order']=(int)($_POST['sort_order']??0);$tool['is_active']=!empty($_POST['is_active'])?1:0;
        if ($tool['title']==='' || strlen($tool['title'])>150 || strlen($tool['summary'])>255) { $error='Add a title of up to 150 characters and a description of up to 255 characters.'; }
        elseif (strlen($tool['slug'])>80 || !ohsc_tools_slug_ok($tool['slug'])) { $error='Use a valid internal tool key.'; }
        if ($seo) {
            foreach (array('meta_title','meta_description','meta_keywords','meta_robots') as $f) { $seoInput[$f]=isset($_POST[$f])&&is_string($_POST[$f])?trim($_POST[$f]):''; }
            $seoInput['slug']=isset($_POST['public_slug'])&&is_string($_POST['public_slug'])?trim($_POST['public_slug']):'';
            if ($error==='') { $error=$physicalSlug==='resources'?ohsc_resource_page_save($db,$resourceSeoPages,'hub',array_merge($resourceSeoPages['hub'],$seoInput),true):ohsc_tool_page_save($db,$seoPages,$seoKey,$seoInput,true); }
        }
        if ($error==='') {
            $stmt=$db->prepare('SELECT tool_id FROM tbl_site_tools WHERE slug=? AND tool_id<>?');$stmt->bind_param('si',$tool['slug'],$toolId);$stmt->execute();$taken=$stmt->get_result()->num_rows>0;$stmt->close();if ($taken) { $error='That internal tool key is already used.'; }
        }
        if ($error==='') {
            $db->begin_transaction();try {
                if ($toolId) { $stmt=$db->prepare('UPDATE tbl_site_tools SET title=?,summary=?,sort_order=?,is_active=? WHERE tool_id=?');$stmt->bind_param('ssiii',$tool['title'],$tool['summary'],$tool['sort_order'],$tool['is_active'],$toolId); }
                else { $stmt=$db->prepare('INSERT INTO tbl_site_tools(title,slug,summary,sort_order,is_active,created_at) VALUES(?,?,?,?,?,NOW())');$stmt->bind_param('sssii',$tool['title'],$tool['slug'],$tool['summary'],$tool['sort_order'],$tool['is_active']); }
                $stmt->execute();$stmt->close();
                if ($seo) { $seoError=$physicalSlug==='resources'?ohsc_resource_page_save($db,$resourceSeoPages,'hub',array_merge($resourceSeoPages['hub'],$seoInput),false,false):ohsc_tool_page_save($db,$seoPages,$seoKey,$seoInput,false,false);if ($seoError!=='') { throw new RuntimeException($seoError); } }
                $db->commit();header('Location: '.$base_url.'/admin/tools-manager?msg='.rawurlencode('Tool and SEO saved.'));exit;
            } catch (Throwable $e) { $db->rollback();$error='Could not save the tool and SEO. Please retry.'; }
        }
    }
}
ohsc_tools_admin_open($toolId?'Edit tool':'Add tool');if ($error!=='') { echo '<p class="tools-error" role="alert">'.ohsc_h($error).'</p>'; }
?>
<form method="post"><input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>"><input type="hidden" name="tool_id" value="<?php echo $toolId; ?>">
<p><label>Title<br><input type="text" name="title" maxlength="150" required value="<?php echo ohsc_h($tool['title']); ?>"></label></p>
<?php if (!$toolId) { ?><p><label>Internal tool folder key<br><input type="text" name="slug" maxlength="80" required value="<?php echo ohsc_h($tool['slug']); ?>"></label></p><p>A new tool needs a working page folder in the tools directory.</p><?php } ?>
<p><label>Short description<br><input type="text" name="summary" maxlength="255" value="<?php echo ohsc_h($tool['summary']); ?>"></label></p>
<p><label>Order<br><input type="number" name="sort_order" value="<?php echo (int)$tool['sort_order']; ?>"></label></p>
<p><label><input type="checkbox" name="is_active" value="1" <?php echo $tool['is_active']?'checked':''; ?>> Show in the website menu</label></p>
<?php if ($seo) { ?><h3>Page SEO</h3>
<p><label>Custom slug<br><input type="text" name="public_slug" maxlength="80" required value="<?php echo ohsc_h($seoInput['slug']); ?>"></label></p>
<p>Public URL: <?php echo ohsc_h($physicalSlug==='resources'?ohsc_resource_page_path($resourceSeoPages,'hub'):ohsc_tool_page_path($seoPages,$seoKey)); ?>. Old URLs redirect automatically; the internal tool folder stays unchanged.</p>
<p><label>Meta title<br><input type="text" name="meta_title" maxlength="200" required value="<?php echo ohsc_h($seoInput['meta_title']); ?>"></label></p>
<p><label>Meta description<br><textarea name="meta_description" maxlength="500" style="min-height:90px"><?php echo ohsc_h($seoInput['meta_description']); ?></textarea></label></p>
<p><label>Meta keywords<br><input type="text" name="meta_keywords" maxlength="500" value="<?php echo ohsc_h($seoInput['meta_keywords']); ?>"></label></p>
<p><label>Meta robots <select name="meta_robots"><?php foreach (ohsc_resource_robots_options() as $r) { ?><option <?php echo $seoInput['meta_robots']===$r?'selected':''; ?>><?php echo ohsc_h($r); ?></option><?php } ?></select></label></p>
<p>Staging login and site-wide noindex remain in force.</p><?php } ?>
<button class="save-btn">Save tool<?php echo $seo?' &amp; SEO':''; ?></button></form>
<?php if ($physicalSlug==='resources') { ?><p class="tools-actions"><a href="<?php echo ohsc_h($base_url); ?>/admin/resource-pages?page=hub#page-images">Manage Resources hub and featured images</a></p><?php } ?>
<?php if ($physicalSlug==='resources') { $key='hub';require __DIR__.'/resource-page-images.php'; }else { require __DIR__.'/tool-image-fields.php'; }ohsc_tools_admin_close(); ?>
