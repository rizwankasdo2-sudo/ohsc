<?php
require dirname(__FILE__) . '/_boot.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        header('Location: ' . $base_url . '/admin/interview-questions?msg=' . rawurlencode('The form expired. Try again.'));
        exit;
    }
    $questionId = isset($_POST['question_id']) ? (int) $_POST['question_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if ($questionId > 0 && ($action === 'hide' || $action === 'show')) {
        $active = ($action === 'show') ? 1 : 0;
        $stmt = $db->prepare("UPDATE tbl_iv_questions SET is_active = ? WHERE question_id = ?");
        $stmt->bind_param('ii', $active, $questionId);
        $stmt->execute();
        $stmt->close();
    } elseif ($questionId > 0 && $action === 'delete') {
        $stmt = $db->prepare("DELETE FROM tbl_iv_questions WHERE question_id = ?");
        $stmt->bind_param('i', $questionId);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: ' . $base_url . '/admin/interview-questions?msg=' . rawurlencode('Saved.'));
    exit;
}

$rows = array();
$result = $db->query("SELECT q.question_id, q.question_text, q.question_type, q.sort_order, q.is_active, i.title AS industry_title
    FROM tbl_iv_questions AS q
    INNER JOIN tbl_iv_industries AS i ON i.industry_id = q.industry_id
    ORDER BY i.sort_order ASC, q.sort_order ASC, q.question_id ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

ohsc_tools_admin_open('Interview questions');
if (!empty($_GET['msg'])) {
    echo '<p class="tools-note"><strong>' . ohsc_h($_GET['msg']) . '</strong></p>';
}
?>
<p>These questions appear on Interview Prep. Each question belongs to a profession and category, with a sample answer. Use Common Interview Questions for the landing-page accordion.</p>
<p class="tools-actions"><a href="<?php echo ohsc_h($base_url); ?>/admin/interview-question">Add question</a> <a href="<?php echo ohsc_h($base_url); ?>/admin/interview-professions">Manage professions</a></p>
<table class="tools" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td><strong>Order</strong></td>
        <td><strong>Industry</strong></td>
        <td><strong>Question</strong></td>
        <td><strong>Category</strong></td>
        <td><strong>Status</strong></td>
        <td><strong>Actions</strong></td>
    </tr>
    <?php if (!$rows) { ?>
    <tr><td colspan="6">No questions yet.</td></tr>
    <?php } ?>
    <?php foreach ($rows as $row) { ?>
    <tr>
        <td><?php echo (int) $row['sort_order']; ?></td>
        <td><?php echo ohsc_h($row['industry_title']); ?></td>
        <td><?php echo ohsc_h($row['question_text']); ?></td>
        <td><?php echo ohsc_h(ucfirst($row['question_type'])); ?></td>
        <td><?php echo ((int) $row['is_active'] === 1) ? 'In the tool' : 'Hidden'; ?></td>
        <td>
            <a href="<?php echo ohsc_h($base_url . '/admin/interview-question?id=' . (int) $row['question_id']); ?>">Edit</a>
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
