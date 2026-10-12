<?php
require dirname(__FILE__) . '/_boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        header('Location: ' . $base_url . '/admin/tools-manager?msg=' . rawurlencode('The form expired. Try again.'));
        exit;
    }
    $toolId = isset($_POST['tool_id']) ? (int) $_POST['tool_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if ($toolId > 0 && $action === 'hide') {
        $stmt = $db->prepare("UPDATE tbl_site_tools SET is_active = 0 WHERE tool_id = ?");
        $stmt->bind_param('i', $toolId);
        $stmt->execute();
        $stmt->close();
    } elseif ($toolId > 0 && $action === 'show') {
        $stmt = $db->prepare("UPDATE tbl_site_tools SET is_active = 1 WHERE tool_id = ?");
        $stmt->bind_param('i', $toolId);
        $stmt->execute();
        $stmt->close();
    } elseif ($toolId > 0 && $action === 'delete') {
        $stmt = $db->prepare("DELETE FROM tbl_site_tools WHERE tool_id = ?");
        $stmt->bind_param('i', $toolId);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: ' . $base_url . '/admin/tools-manager?msg=' . rawurlencode('Saved.'));
    exit;
}

$tools = array();
$result = $db->query("SELECT tool_id, title, slug, summary, sort_order, is_active FROM tbl_site_tools ORDER BY sort_order ASC, tool_id ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tools[] = $row;
    }
}

ohsc_tools_admin_open('Tools Manager');
if (!empty($_GET['msg'])) {
    echo '<p class="tools-note"><strong>' . ohsc_h($_GET['msg']) . '</strong></p>';
}
?>
<p class="tools-actions"><a href="<?php echo ohsc_h($base_url); ?>/admin/tool-edit">Add tool</a></p>
<table class="tools" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td><strong>Title</strong></td>
        <td><strong>Page</strong></td>
        <td><strong>Order</strong></td>
        <td><strong>Status</strong></td>
        <td><strong>Actions</strong></td>
    </tr>
    <?php if (!$tools) { ?>
    <tr><td colspan="5">No tools yet.</td></tr>
    <?php } ?>
    <?php foreach ($tools as $tool) {
        $pageFile = dirname(__FILE__) . '/../' . $tool['slug'] . '/index.php';
        $pageReady = is_file($pageFile);
    ?>
    <tr>
        <td>
            <?php echo ohsc_h($tool['title']); ?><br>
            <span class="tools-note"><?php echo ohsc_h($tool['summary']); ?></span>
        </td>
        <td>
            /tools/<?php echo ohsc_h($tool['slug']); ?>/
            <?php if (!$pageReady) { ?><br><span class="tools-error">No page folder for this slug yet.</span><?php } ?>
        </td>
        <td><?php echo (int) $tool['sort_order']; ?></td>
        <td><?php echo ((int) $tool['is_active'] === 1) ? 'Shown' : 'Hidden'; ?></td>
        <td>
            <a href="<?php echo ohsc_h($base_url . '/admin/tool-edit?id=' . (int) $tool['tool_id']); ?>">Edit</a>
            <form method="post" style="display:inline;">
                <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
                <input type="hidden" name="tool_id" value="<?php echo (int) $tool['tool_id']; ?>">
                <input type="hidden" name="action" value="<?php echo ((int) $tool['is_active'] === 1) ? 'hide' : 'show'; ?>">
                <button type="submit"><?php echo ((int) $tool['is_active'] === 1) ? 'Hide' : 'Show'; ?></button>
            </form>
            <form method="post" style="display:inline;" onsubmit="return confirm('Remove this tool from the list?');">
                <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
                <input type="hidden" name="tool_id" value="<?php echo (int) $tool['tool_id']; ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<p class="tools-note">Hiding a tool removes it from the website menu. The typing test text is edited under Typing texts.</p>
<?php
ohsc_tools_admin_close();
