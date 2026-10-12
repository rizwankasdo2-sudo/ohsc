<?php
require dirname(__FILE__) . '/_boot.php';

$passageId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$passage = array(
    'passage_id' => 0,
    'title' => '',
    'passage_text' => '',
    'is_active' => 1,
);
$error = '';

if ($passageId > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $db->prepare("SELECT passage_id, title, passage_text, is_active FROM tbl_typing_passages WHERE passage_id = ?");
    $stmt->bind_param('i', $passageId);
    $stmt->execute();
    $stmt->bind_result($id, $title, $text, $active);
    if ($stmt->fetch()) {
        $passage = array(
            'passage_id' => $id,
            'title' => $title,
            'passage_text' => $text,
            'is_active' => $active,
        );
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        $error = 'The form expired. Reload the page and try again.';
    } else {
        $passage['passage_id'] = isset($_POST['passage_id']) ? (int) $_POST['passage_id'] : 0;
        $passage['title'] = trim(isset($_POST['title']) ? $_POST['title'] : '');
        $passage['passage_text'] = trim(isset($_POST['passage_text']) ? $_POST['passage_text'] : '');
        $passage['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
        $plain = trim(preg_replace('/\s+/', ' ', $passage['passage_text']));
        if ($passage['title'] === '' || strlen($passage['title']) > 150) {
            $error = 'Add a title of 150 characters or less.';
        } elseif (strlen($plain) < 40) {
            $error = 'The text needs at least 40 characters so the test has something to type.';
        } elseif (strlen($passage['passage_text']) > 5000) {
            $error = 'Keep the text under 5000 characters.';
        } else {
            $now = date('Y-m-d H:i:s');
            if ($passage['passage_id'] > 0) {
                $stmt = $db->prepare("UPDATE tbl_typing_passages SET title = ?, passage_text = ?, is_active = ?, updated_at = ? WHERE passage_id = ?");
                $stmt->bind_param('ssisi', $passage['title'], $passage['passage_text'], $passage['is_active'], $now, $passage['passage_id']);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $db->prepare("INSERT INTO tbl_typing_passages (title, passage_text, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('ssiss', $passage['title'], $passage['passage_text'], $passage['is_active'], $now, $now);
                $stmt->execute();
                $stmt->close();
            }
            header('Location: ' . $base_url . '/admin/typing-passages?msg=' . rawurlencode('Text saved.'));
            exit;
        }
    }
}

ohsc_tools_admin_open($passage['passage_id'] ? 'Edit typing text' : 'Add typing text');
if ($error !== '') {
    echo '<p class="tools-error">' . ohsc_h($error) . '</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
    <input type="hidden" name="passage_id" value="<?php echo (int) $passage['passage_id']; ?>">
    <p>Title<br><input type="text" name="title" maxlength="150" value="<?php echo ohsc_h($passage['title']); ?>" required></p>
    <p>Text the visitor will type<br><textarea name="passage_text" required><?php echo ohsc_h($passage['passage_text']); ?></textarea></p>
    <p class="tools-note">The test picks one published text at random, then repeats published texts if the timer is still running.</p>
    <p><label><input type="checkbox" name="is_active" value="1" <?php echo ((int) $passage['is_active'] === 1) ? 'checked' : ''; ?>> Use this text in the test</label></p>
    <p><button class="save-btn" type="submit">Save</button></p>
</form>
<?php
ohsc_tools_admin_close();
