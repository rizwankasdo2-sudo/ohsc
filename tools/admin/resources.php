<?php
require __DIR__.'/_boot.php';require_once __DIR__.'/../includes/resources.php';ohsc_resources_ensure($db);
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!ohsc_tools_csrf_ok()) { $error='The form expired. Reload and try again.'; }
    else {
        $id=isset($_POST['resource_id'])?(int)$_POST['resource_id']:0;
        $action=isset($_POST['action'])&&is_string($_POST['action'])?$_POST['action']:'hide';
        if (!ohsc_resource_action($db,$id,$action)) { $error='Choose a valid resource action.'; }
        else {
            $message=$action==='delete'?'Resource deleted. You can restore it from Deleted resources.':($action==='restore'?'Resource restored as a draft.':'Resource hidden.');
            $returnPage=isset($_POST['return_page'])&&is_string($_POST['return_page'])?$_POST['return_page']:'';$knownPages=ohsc_resource_pages($db);
            if ($returnPage && isset($knownPages[$returnPage])) { header('Location: '.$base_url.'/admin/resource-pages?page='.rawurlencode($returnPage).'&resource_msg='.rawurlencode($message)); }
            else { header('Location: '.$base_url.'/admin/career-resources?msg='.rawurlencode($message)); }exit;
        }
    }
}
$pages=ohsc_resource_pages($db);$scope=isset($_GET['page'])&&is_string($_GET['page'])&&isset($pages[$_GET['page']])?$_GET['page']:'';
$trash=!empty($_GET['trash']);$rows=ohsc_resources_for_page($db,$scope,$trash);

ohsc_tools_admin_open('Career resources');
if ($error) { echo '<p class="tools-error">'.ohsc_h($error).'</p>'; }
if (!empty($_GET['msg'])) { echo '<p role="status">'.ohsc_h($_GET['msg']).'</p>'; }
?>
<p class="tools-actions"><a href="<?php echo ohsc_h($base_url); ?>/admin/resource-pages">Manage resource pages &amp; SEO</a></p>
<p>Add downloadable resources, then publish them when the files are ready. Assign each item to a category and optional subcategory. FAQs can be published with an answer instead of a file. Hidden items and downloads are unavailable to visitors.</p>
<p class="tools-actions"><a href="<?php echo ohsc_h($base_url.'/admin/career-resource'.($scope?'?page='.rawurlencode($scope):'')); ?>">Add resource</a> <a href="<?php echo ohsc_h($base_url); ?>/admin/career-resources?trash=1">Deleted resources / Restore</a> <a href="<?php echo ohsc_h($base_url); ?>/tools/resources/" target="_blank" rel="noopener">View resources page</a></p>
<table class="tools" width="100%"><tr><th>Title</th><th>Category / Goal</th><th>Files</th><th>Order</th><th>Status</th><th>Actions</th></tr>
<?php if (!$rows) { ?><tr><td colspan="6">No resources yet. Use Add resource to upload your first download.</td></tr><?php } ?>
<?php foreach ($rows as $r) { ?><tr>
<td><?php echo ohsc_h($r['title']); ?><?php if ($r['is_featured']) { echo ' ★'; } ?></td>
<td><?php echo ohsc_h(ohsc_resource_categories()[$r['category']].' / '.ohsc_resource_goals()[$r['goal']]); ?></td>
<td><?php echo (int)$r['file_count']; ?></td><td><?php echo (int)$r['sort_order']; ?></td><td><?php echo $r['is_active']?'Published':'Hidden / Draft'; ?></td>
<td><a href="<?php echo ohsc_h($base_url.'/admin/career-resource?id='.$r['resource_id']); ?>">Edit / Upload files</a>
<?php if ($r['is_active']) { ?><form method="post" style="display:inline"><input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>"><input type="hidden" name="resource_id" value="<?php echo (int)$r['resource_id']; ?>"><button>Hide</button></form><?php } ?><form method="post" style="display:inline" onsubmit="return confirm('Move this resource to deleted resources, or restore it as a draft?');"><input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>"><input type="hidden" name="resource_id" value="<?php echo (int)$r['resource_id']; ?>"><input type="hidden" name="action" value="<?php echo $trash?'restore':'delete'; ?>"><button><?php echo $trash?'Restore as draft':'Delete'; ?></button></form></td></tr><?php } ?></table>
<?php ohsc_tools_admin_close(); ?>
