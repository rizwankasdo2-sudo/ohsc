<?php
require dirname(__FILE__) . '/_boot.php';

$toolId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$tool = array(
    'tool_id' => 0,
    'title' => '',
    'slug' => '',
    'summary' => '',
    'sort_order' => 1,
    'is_active' => 1,
);
$error = '';

if ($toolId > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $db->prepare("SELECT tool_id, title, slug, summary, sort_order, is_active FROM tbl_site_tools WHERE tool_id = ?");
    $stmt->bind_param('i', $toolId);
    $stmt->execute();
    $stmt->bind_result($id, $title, $slug, $summary, $sort, $active);
    if ($stmt->fetch()) {
        $tool = array(
            'tool_id' => $id,
            'title' => $title,
            'slug' => $slug,
            'summary' => $summary,
            'sort_order' => $sort,
            'is_active' => $active,
        );
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        $error = 'The form expired. Reload the page and try again.';
    } else {
        $tool['tool_id'] = isset($_POST['tool_id']) ? (int) $_POST['tool_id'] : 0;
        $tool['title'] = trim(isset($_POST['title']) ? $_POST['title'] : '');
        $tool['slug'] = strtolower(trim(isset($_POST['slug']) ? $_POST['slug'] : ''));
        $tool['summary'] = trim(isset($_POST['summary']) ? $_POST['summary'] : '');
        $tool['sort_order'] = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $tool['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
        if ($tool['title'] === '' || strlen($tool['title']) > 150) {
            $error = 'Add a title of 150 characters or less.';
        } elseif (!ohsc_tools_slug_ok($tool['slug'])) {
            $error = 'Use a slug made of lowercase letters, numbers, and hyphens.';
        } elseif (strlen($tool['summary']) > 255) {
            $error = 'Keep the short description under 255 characters.';
        } else {
            $check = $db->prepare("SELECT tool_id FROM tbl_site_tools WHERE slug = ? AND tool_id <> ?");
            $check->bind_param('si', $tool['slug'], $tool['tool_id']);
            $check->execute();
            $check->store_result();
            $taken = $check->num_rows > 0;
            $check->close();
            if ($taken) {
                $error = 'That slug is already used.';
            } elseif ($tool['tool_id'] > 0) {
                $stmt = $db->prepare("UPDATE tbl_site_tools SET title = ?, slug = ?, summary = ?, sort_order = ?, is_active = ? WHERE tool_id = ?");
                $stmt->bind_param('sssiii', $tool['title'], $tool['slug'], $tool['summary'], $tool['sort_order'], $tool['is_active'], $tool['tool_id']);
                $stmt->execute();
                $stmt->close();
                header('Location: ' . $base_url . '/admin/tools-manager?msg=' . rawurlencode('Tool saved.'));
                exit;
            } else {
                $now = date('Y-m-d H:i:s');
                $stmt = $db->prepare("INSERT INTO tbl_site_tools (title, slug, summary, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssiis', $tool['title'], $tool['slug'], $tool['summary'], $tool['sort_order'], $tool['is_active'], $now);
                $stmt->execute();
                $stmt->close();
                header('Location: ' . $base_url . '/admin/tools-manager?msg=' . rawurlencode('Tool added.'));
                exit;
            }
        }
    }
}

ohsc_tools_admin_open($tool['tool_id'] ? 'Edit tool' : 'Add tool');
if ($error !== '') {
    echo '<p class="tools-error">' . ohsc_h($error) . '</p>';
}
$pageReady = $tool['slug'] !== '' && is_file(dirname(__FILE__) . '/../' . $tool['slug'] . '/index.php');
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
    <input type="hidden" name="tool_id" value="<?php echo (int) $tool['tool_id']; ?>">
    <p>Title<br><input type="text" name="title" maxlength="150" value="<?php echo ohsc_h($tool['title']); ?>" required></p>
    <p>Slug<br><input type="text" name="slug" maxlength="80" value="<?php echo ohsc_h($tool['slug']); ?>" required></p>
    <p class="tools-note">The public link is /tools/slug/. The typing test slug is typing-test. A new slug is only a working page when that folder exists in the tools directory.</p>
    <?php if ($tool['slug'] !== '' && !$pageReady) { ?>
    <p class="tools-error">No page folder was found for this slug.</p>
    <?php } ?>
    <p>Short description<br><input type="text" name="summary" maxlength="255" value="<?php echo ohsc_h($tool['summary']); ?>"></p>
    <p>Order<br><input type="number" name="sort_order" value="<?php echo (int) $tool['sort_order']; ?>"></p>
    <p><label><input type="checkbox" name="is_active" value="1" <?php echo ((int) $tool['is_active'] === 1) ? 'checked' : ''; ?>> Show in the website menu</label></p>
    <p><button class="save-btn" type="submit">Save</button></p>
</form>
<?php
ohsc_tools_admin_close();
