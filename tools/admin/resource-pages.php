<?php
require __DIR__.'/_boot.php';require_once __DIR__.'/../includes/resources.php';ohsc_resources_ensure($db);
$pages=ohsc_resource_pages($db);$key=isset($_GET['page'])&&is_string($_GET['page'])?$_GET['page']:'';$error='';$saved=false;
if ($key && !isset($pages[$key])) { http_response_code(404);$error='Resource page not found.';$key=''; }
$item=$key?$pages[$key]:null;
require __DIR__.'/resource-page-image-upload.php';
if ($_SERVER['REQUEST_METHOD']==='POST' && !isset($_POST['image_action'])) {
    if (!ohsc_tools_csrf_ok()) { $error='The form expired. Reload and try again.'; }
    elseif (!$item) { $error='Choose a resource page.'; }
    else {
        $input=array();foreach (array('slug','title','summary','meta_title','meta_description','meta_keywords','meta_robots') as $field) { $input[$field]=isset($_POST[$field])&&is_string($_POST[$field])?trim($_POST[$field]):''; }
        $error=ohsc_resource_page_save($db,$pages,$key,$input);$item=array_merge($item,$input);
        if (!$error) { header('Location: '.$base_url.'/admin/resource-pages?page='.rawurlencode($key).'&saved=1');exit; }
    }
}
ohsc_tools_admin_open('Resource pages & SEO');
if ($error) { echo '<p class="tools-error">'.ohsc_h($error).'</p>'; }
if (!empty($_GET['resource_msg']) && is_string($_GET['resource_msg'])) { echo '<p role="status">'.ohsc_h($_GET['resource_msg']).'</p>'; }
if (!empty($_GET['saved'])) { echo '<p role="status">Page and SEO settings saved.</p>'; }
?>
<p>Manage the resources hub, seven categories and their listed subcategories. Download items do not create additional front-end pages.</p>
<p>Meta robots is stored for each page. The staging website lock and site-wide noindex headers remain in force, even if you choose index/follow here.</p>
<?php if ($item) { ?>
<p><a href="<?php echo ohsc_h($base_url); ?>/admin/resource-pages">← All resource pages</a> · <a target="_blank" rel="noopener" href="<?php echo ohsc_h($base_url.ohsc_resource_page_path($pages,$key)); ?>">View page</a></p>
<form method="post"><input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
<p><label>Page heading<br><input type="text" name="title" maxlength="150" required value="<?php echo ohsc_h($item['title']); ?>"></label></p>
<p><label>Page introduction<br><textarea name="summary" maxlength="500" style="min-height:90px"><?php echo ohsc_h($item['summary']); ?></textarea></label></p>
<p><label>Custom slug (this URL segment only)<br><input type="text" name="slug" maxlength="80" pattern="[a-z0-9]+(-[a-z0-9]+)*" required value="<?php echo ohsc_h($item['slug']); ?>"></label></p>
<p>Current URL: <?php echo ohsc_h(ohsc_resource_page_path($pages,$key)); ?>. Changing a parent slug updates child URLs too; previous URLs redirect automatically.</p>
<p><label>Meta title<br><input type="text" name="meta_title" maxlength="200" required value="<?php echo ohsc_h($item['meta_title']); ?>"></label></p>
<p><label>Meta description<br><textarea name="meta_description" maxlength="500" style="min-height:90px"><?php echo ohsc_h($item['meta_description']); ?></textarea></label></p>
<p><label>Meta keywords<br><input type="text" name="meta_keywords" maxlength="500" value="<?php echo ohsc_h($item['meta_keywords']); ?>"></label></p>
<p><label>Meta robots <select name="meta_robots"><?php foreach (ohsc_resource_robots_options() as $robots) { ?><option <?php echo $item['meta_robots']===$robots?'selected':''; ?>><?php echo ohsc_h($robots); ?></option><?php } ?></select></label></p>
<button type="submit" class="save-btn">Save page & SEO</button></form>
<?php require __DIR__.'/resource-page-images.php';require __DIR__.'/resource-page-items.php'; } else { ?>
<table class="tools" width="100%"><tr><th>Page</th><th>URL</th><th>Meta robots</th><th>Action</th></tr>
<?php foreach (ohsc_resource_page_seed() as $seed) { $page=$pages[$seed['page_key']]; ?><tr><td><?php echo ohsc_h($page['title']); ?></td><td><?php echo ohsc_h(ohsc_resource_page_path($pages,$page['page_key'])); ?></td><td><?php echo ohsc_h($page['meta_robots']); ?></td><td><a href="<?php echo ohsc_h($base_url.'/admin/resource-pages?page='.$page['page_key']); ?>">Edit page / SEO</a></td></tr><?php } ?></table>
<?php } ohsc_tools_admin_close(); ?>
