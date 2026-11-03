<?php
require __DIR__.'/_boot.php';require_once __DIR__.'/../includes/resources.php';ohsc_resources_ensure($db);
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!ohsc_tools_csrf_ok()) { $error='The form expired. Reload and try again.'; }
    else {
        $id=isset($_POST['resource_id'])?(int)$_POST['resource_id']:0;
        $stmt=$db->prepare('UPDATE tbl_career_resources SET is_active=0,updated_at=NOW() WHERE resource_id=?');
        $stmt->bind_param('i',$id);$stmt->execute();$stmt->close();
        header('Location: '.$base_url.'/admin/career-resources?msg=Resource+hidden.');exit;
    }
}
$rows=$db->query('SELECT r.*, (SELECT COUNT(*) FROM tbl_career_resource_files f WHERE f.resource_id=r.resource_id) file_count FROM tbl_career_resources r ORDER BY sort_order,resource_id DESC')->fetch_all(MYSQLI_ASSOC);
ohsc_tools_admin_open('Career resources');
if ($error) { echo '<p class="tools-error">'.ohsc_h($error).'</p>'; }
if (!empty($_GET['msg'])) { echo '<p role="status">'.ohsc_h($_GET['msg']).'</p>'; }
?>
<p>Add downloadable resources, then publish them when the files are ready. Hidden resources and their downloads are unavailable to visitors.</p>
<p class="tools-actions"><a href="<?php echo ohsc_h($base_url); ?>/admin/career-resource">Add resource</a> <a href="<?php echo ohsc_h($base_url); ?>/tools/resources/" target="_blank" rel="noopener">View resources page</a></p>
<table class="tools" width="100%"><tr><th>Title</th><th>Category / Goal</th><th>Files</th><th>Order</th><th>Status</th><th>Actions</th></tr>
<?php if (!$rows) { ?><tr><td colspan="6">No resources yet. Use Add resource to upload your first download.</td></tr><?php } ?>
<?php foreach ($rows as $r) { ?><tr>
<td><?php echo ohsc_h($r['title']); ?><?php if ($r['is_featured']) { echo ' ★'; } ?></td>
<td><?php echo ohsc_h(ohsc_resource_categories()[$r['category']].' / '.ohsc_resource_goals()[$r['goal']]); ?></td>
<td><?php echo (int)$r['file_count']; ?></td><td><?php echo (int)$r['sort_order']; ?></td><td><?php echo $r['is_active']?'Published':'Hidden / Draft'; ?></td>
<td><a href="<?php echo ohsc_h($base_url.'/admin/career-resource?id='.$r['resource_id']); ?>">Edit / Upload files</a>
<?php if ($r['is_active']) { ?><form method="post" style="display:inline"><input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>"><input type="hidden" name="resource_id" value="<?php echo (int)$r['resource_id']; ?>"><button>Hide</button></form><?php } ?></td></tr><?php } ?></table>
<?php ohsc_tools_admin_close(); ?>
