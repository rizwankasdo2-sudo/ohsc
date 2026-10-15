<?php
require dirname(__FILE__) . '/_boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        header('Location: ' . $base_url . '/admin/eq-questions?msg=' . rawurlencode('The form expired. Try again.'));
        exit;
    }
    $questionId = isset($_POST['question_id']) ? (int) $_POST['question_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if ($questionId > 0 && ($action === 'hide' || $action === 'show')) {
        $active = ($action === 'show') ? 1 : 0;
        $stmt = $db->prepare("UPDATE tbl_eq_questions SET is_active = ? WHERE question_id = ?");
        $stmt->bind_param('ii', $active, $questionId);
        $stmt->execute();
        $stmt->close();
    } elseif ($questionId > 0 && $action === 'delete') {
        $stmt = $db->prepare("DELETE FROM tbl_eq_choices WHERE question_id = ?");
        $stmt->bind_param('i', $questionId);
        $stmt->execute();
        $stmt->close();
        $stmt = $db->prepare("DELETE FROM tbl_eq_questions WHERE question_id = ?");
        $stmt->bind_param('i', $questionId);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: ' . $base_url . '/admin/eq-questions?msg=' . rawurlencode('Saved.'));
    exit;
}

$rows = array();
$result = $db->query("SELECT question_id, prompt, area, sort_order, is_active FROM tbl_eq_questions ORDER BY sort_order ASC, question_id ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}
$areas = ohsc_eq_areas();

ohsc_tools_admin_open('EQ questions');
if (!empty($_GET['msg'])) {
    echo '<p class="tools-note"><strong>' . ohsc_h($_GET['msg']) . '</strong></p>';
}
?>
<p>These questions appear on the emotional intelligence test. Hidden questions stay here but are not asked. Each answer adds points to one of the five areas.</p>
<p class="tools-actions"><a href="<?php echo ohsc_h($base_url); ?>/admin/eq-question">Add question</a></p>
<table class="tools" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td><strong>Order</strong></td>
        <td><strong>Area</strong></td>
        <td><strong>Question</strong></td>
        <td><strong>Status</strong></td>
        <td><strong>Actions</strong></td>
    </tr>
    <?php if (!$rows) { ?>
    <tr><td colspan="5">No questions yet.</td></tr>
    <?php } ?>
    <?php foreach ($rows as $row) {
        $areaName = isset($areas[$row['area']]) ? $areas[$row['area']][0] : $row['area'];
    ?>
    <tr>
        <td><?php echo (int) $row['sort_order']; ?></td>
        <td><?php echo ohsc_h($areaName); ?></td>
        <td><?php echo ohsc_h($row['prompt']); ?></td>
        <td><?php echo ((int) $row['is_active'] === 1) ? 'In the test' : 'Hidden'; ?></td>
        <td>
            <a href="<?php echo ohsc_h($base_url . '/admin/eq-question?id=' . (int) $row['question_id']); ?>">Edit</a>
            <form method="post" style="display:inline;">
                <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
                <input type="hidden" name="question_id" value="<?php echo (int) $row['question_id']; ?>">
                <input type="hidden" name="action" value="<?php echo ((int) $row['is_active'] === 1) ? 'hide' : 'show'; ?>">
                <button type="submit"><?php echo ((int) $row['is_active'] === 1) ? 'Hide' : 'Show'; ?></button>
            </form>
            <form method="post" style="display:inline;" onsubmit="return confirm('Delete this question?');">
                <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
                <input type="hidden" name="question_id" value="<?php echo (int) $row['question_id']; ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<?php
ohsc_tools_admin_close();
