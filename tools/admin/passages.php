<?php
require dirname(__FILE__) . '/_boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        header('Location: ' . $base_url . '/admin/typing-passages?msg=' . rawurlencode('The form expired. Try again.'));
        exit;
    }
    $passageId = isset($_POST['passage_id']) ? (int) $_POST['passage_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if ($passageId > 0 && ($action === 'hide' || $action === 'show')) {
        $active = ($action === 'show') ? 1 : 0;
        $stmt = $db->prepare("UPDATE tbl_typing_passages SET is_active = ? WHERE passage_id = ?");
        $stmt->bind_param('ii', $active, $passageId);
        $stmt->execute();
        $stmt->close();
    } elseif ($passageId > 0 && $action === 'delete') {
        $stmt = $db->prepare("DELETE FROM tbl_typing_passages WHERE passage_id = ?");
        $stmt->bind_param('i', $passageId);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: ' . $base_url . '/admin/typing-passages?msg=' . rawurlencode('Saved.'));
    exit;
}

$rows = array();
$result = $db->query("SELECT passage_id, title, passage_text, is_active, updated_at FROM tbl_typing_passages ORDER BY passage_id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

ohsc_tools_admin_open('Typing texts');
if (!empty($_GET['msg'])) {
    echo '<p class="tools-note"><strong>' . ohsc_h($_GET['msg']) . '</strong></p>';
}
?>
<p>These passages are picked at random on the typing test. Hidden passages stay in this list but are not used.</p>
<p class="tools-actions"><a href="<?php echo ohsc_h($base_url); ?>/admin/typing-passage">Add text</a></p>
<table class="tools" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td><strong>Title</strong></td>
        <td><strong>Text</strong></td>
        <td><strong>Status</strong></td>
        <td><strong>Actions</strong></td>
    </tr>
    <?php if (!$rows) { ?>
    <tr><td colspan="4">No text yet.</td></tr>
    <?php } ?>
    <?php foreach ($rows as $row) {
        $excerpt = $row['passage_text'];
        if (strlen($excerpt) > 140) {
            $excerpt = substr($excerpt, 0, 140) . '...';
        }
    ?>
    <tr>
        <td><?php echo ohsc_h($row['title']); ?></td>
        <td><?php echo ohsc_h($excerpt); ?></td>
        <td><?php echo ((int) $row['is_active'] === 1) ? 'In the test' : 'Hidden'; ?></td>
        <td>
            <a href="<?php echo ohsc_h($base_url . '/admin/typing-passage?id=' . (int) $row['passage_id']); ?>">Edit</a>
            <form method="post" style="display:inline;">
                <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
                <input type="hidden" name="passage_id" value="<?php echo (int) $row['passage_id']; ?>">
                <input type="hidden" name="action" value="<?php echo ((int) $row['is_active'] === 1) ? 'hide' : 'show'; ?>">
                <button type="submit"><?php echo ((int) $row['is_active'] === 1) ? 'Hide' : 'Show'; ?></button>
            </form>
            <form method="post" style="display:inline;" onsubmit="return confirm('Delete this text?');">
                <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
                <input type="hidden" name="passage_id" value="<?php echo (int) $row['passage_id']; ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<?php
ohsc_tools_admin_close();
