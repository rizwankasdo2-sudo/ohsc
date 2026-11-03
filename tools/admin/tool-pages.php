<?php
require __DIR__.'/_boot.php';$pages=ohsc_tool_pages($db);$key=isset($_GET['page'])&&is_string($_GET['page'])?$_GET['page']:'';$item=isset($pages[$key])?$pages[$key]:null;$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!ohsc_tools_csrf_ok()) { $error='The form expired. Reload and try again.'; }
    elseif (!$item) { $error='Choose a tool page.'; }
    else { $input=array();foreach (array('slug','meta_title','meta_description','meta_keywords','meta_robots') as $f) { $input[$f]=isset($_POST[$f])&&is_string($_POST[$f])?trim($_POST[$f]):''; }$error=ohsc_tool_page_save($db,$pages,$key,$input);$item=array_merge($item,$input);if (!$error) { header('Location: '.$base_url.'/admin/tool-pages?page='.rawurlencode($key).'&saved=1');exit; } }
}
ohsc_tools_admin_open('Tool pages & SEO');if ($error) { echo '<p class="tools-error">'.ohsc_h($error).'</p>'; }if (!empty($_GET['saved'])) { echo '<p role="status">Tool page SEO saved.</p>'; }
?>
<p>SEO for the tools hub, each working tool and every interview profession page. Resource hub/category SEO is under <a href="<?php echo ohsc_h($base_url); ?>/admin/resource-pages">Resource pages &amp; SEO</a>. Staging login and site-wide noindex remain in force.</p>
<?php if ($item) { ?><h3><?php echo ohsc_h($item['title']); ?></h3><p>Current URL: <?php echo ohsc_h(ohsc_tool_page_path($pages,$key)); ?> · <a href="<?php echo ohsc_h($base_url); ?>/admin/tool-pages">All tool pages</a></p>
<form method="post"><input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
<p><label>Custom slug<br><input type="text" name="slug" maxlength="80" value="<?php echo ohsc_h($item['slug']); ?>" <?php echo $key!=='tools-hub'?'required':''; ?>></label></p><p>Use a single URL segment. Leave the hub slug empty for /tools/. Changing the interview landing slug updates profession URLs; old addresses redirect.</p>
<p><label>Meta title<br><input type="text" name="meta_title" maxlength="200" required value="<?php echo ohsc_h($item['meta_title']); ?>"></label></p>
<p><label>Meta description<br><textarea name="meta_description" maxlength="500" style="min-height:90px"><?php echo ohsc_h($item['meta_description']); ?></textarea></label></p>
<p><label>Meta keywords<br><input type="text" name="meta_keywords" maxlength="500" value="<?php echo ohsc_h($item['meta_keywords']); ?>"></label></p>
<p><label>Meta robots <select name="meta_robots"><?php foreach (ohsc_resource_robots_options() as $v) { ?><option <?php echo $item['meta_robots']===$v?'selected':''; ?>><?php echo ohsc_h($v); ?></option><?php } ?></select></label></p><button class="save-btn">Save tool SEO</button></form>
<?php } else { ?><table class="tools" width="100%"><tr><th>Page</th><th>URL</th><th>Action</th></tr><?php foreach ($pages as $p) { ?><tr><td><?php echo ohsc_h($p['title']); ?></td><td><?php echo ohsc_h(ohsc_tool_page_path($pages,$p['page_key'])); ?></td><td><a href="<?php echo ohsc_h($base_url.'/admin/tool-pages?page='.rawurlencode($p['page_key'])); ?>">Edit slug / SEO</a></td></tr><?php } ?></table><?php } ohsc_tools_admin_close(); ?>
