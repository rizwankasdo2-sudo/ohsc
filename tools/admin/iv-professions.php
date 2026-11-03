<?php
require dirname(__FILE__) . '/_boot.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$item = array('industry_id' => 0, 'slug' => '', 'title' => '', 'summary' => '', 'image' => 'career-office.jpg', 'sort_order' => 10, 'is_active' => 1);
$error = '';
if ($id > 0) {
    $stmt = $db->prepare("SELECT industry_id, slug, title, summary, image, sort_order, is_active FROM tbl_iv_industries WHERE industry_id = ?");
    $stmt->bind_param('i', $id); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
    if ($row) { $item = $row; } else { $error = 'Profession not found.'; }
}
$images = array();
foreach (glob(dirname(__FILE__) . '/../assets/*') as $file) {
    if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $file)) { $images[] = basename($file); }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) { $error = 'The form expired. Reload the page and try again.'; }
    else {
        foreach (array('slug', 'title', 'summary', 'image') as $key) { $item[$key] = isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : ''; }
        $item['slug'] = strtolower($item['slug']);
        $item['sort_order'] = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $item['is_active'] = empty($_POST['is_active']) ? 0 : 1;
        if (strlen($item['slug']) > 80 || !ohsc_tools_slug_ok($item['slug'])) { $error = 'Use a valid lowercase slug with letters, numbers and hyphens.'; }
        elseif ($item['title'] === '' || strlen($item['title']) > 150) { $error = 'Enter a title of up to 150 characters.'; }
        elseif (strlen($item['summary']) > 255) { $error = 'Keep the description under 255 characters.'; }
        elseif (!in_array($item['image'], $images, true)) { $error = 'Choose an available image.'; }
        elseif ($item['sort_order'] < 0 || $item['sort_order'] > 999) { $error = 'Order must be between 0 and 999.'; }
        elseif ($id > 0 && !$row) { $error = 'Profession not found.'; }
        else {
            $check = $db->prepare("SELECT industry_id FROM tbl_iv_industries WHERE slug = ? AND industry_id <> ?");
            $check->bind_param('si', $item['slug'], $id); $check->execute(); $check->store_result(); $taken = $check->num_rows > 0; $check->close();
            if ($taken) { $error = 'That slug is already used.'; }
            elseif ($id > 0 && $row['slug'] === 'general' && $item['slug'] !== 'general') { $error = 'Keep the reserved general slug for the common question collection.'; }
            else {
                if ($id > 0) {
                    $stmt = $db->prepare("UPDATE tbl_iv_industries SET slug = ?, title = ?, summary = ?, image = ?, sort_order = ?, is_active = ? WHERE industry_id = ?");
                    $stmt->bind_param('ssssiii', $item['slug'], $item['title'], $item['summary'], $item['image'], $item['sort_order'], $item['is_active'], $id);
                } else {
                    $stmt = $db->prepare("INSERT INTO tbl_iv_industries (slug, title, summary, image, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('ssssii', $item['slug'], $item['title'], $item['summary'], $item['image'], $item['sort_order'], $item['is_active']);
                }
                $stmt->execute(); $stmt->close();
                header('Location: ' . $base_url . '/admin/interview-professions?msg=' . rawurlencode('Profession saved. Add or edit its questions from Interview Prep.')); exit;
            }
        }
    }
}
ohsc_tools_admin_open('Interview professions');
if (!empty($_GET['msg']) && is_string($_GET['msg'])) { echo '<p class="tools-note">' . ohsc_h($_GET['msg']) . '</p>'; }
if ($error !== '') { echo '<p class="tools-error">' . ohsc_h($error) . '</p>'; }
?>
<p>Manage the public profession directory. Hidden professions and their questions are not accessible publicly. The reserved general collection supplies common questions. Existing questions stay attached when a title or image changes.</p>
<?php if ($id) { ?><p><a href="<?php echo ohsc_h($base_url.'/admin/tool-pages?page=profession-'.$id); ?>">Edit this profession page slug &amp; SEO</a></p><?php } ?>
<form method="post">
<input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
<p>Title<br><input type="text" name="title" maxlength="150" required value="<?php echo ohsc_h($item['title']); ?>"></p>
<p>Slug<br><input type="text" name="slug" maxlength="80" pattern="[a-z0-9]+(-[a-z0-9]+)*" required value="<?php echo ohsc_h($item['slug']); ?>"></p>
<p>Description<br><input type="text" name="summary" maxlength="255" value="<?php echo ohsc_h($item['summary']); ?>"></p>
<p>Image<br><select name="image"><?php foreach ($images as $image) { ?><option value="<?php echo ohsc_h($image); ?>" <?php echo $item['image'] === $image ? 'selected' : ''; ?>><?php echo ohsc_h($image); ?></option><?php } ?></select></p>
<p>Order<br><input type="number" name="sort_order" min="0" max="999" value="<?php echo (int) $item['sort_order']; ?>"></p>
<p><label><input type="checkbox" name="is_active" value="1" <?php echo $item['is_active'] ? 'checked' : ''; ?>> Publish this profession</label></p>
<p><button class="save-btn" type="submit"><?php echo $id ? 'Save profession' : 'Add profession'; ?></button> <a href="<?php echo ohsc_h($base_url); ?>/admin/interview-professions">New profession</a></p>
</form>
<table class="tools" width="100%"><tr><th>Order</th><th>Profession</th><th>Slug</th><th>Status</th><th>Actions</th></tr>
<?php $result = $db->query("SELECT industry_id, title, slug, sort_order, is_active FROM tbl_iv_industries ORDER BY sort_order, industry_id"); while ($profession = $result->fetch_assoc()) { ?>
<tr><td><?php echo (int) $profession['sort_order']; ?></td><td><?php echo ohsc_h($profession['title']); ?></td><td><?php echo ohsc_h($profession['slug']); ?></td><td><?php echo $profession['is_active'] ? 'Published' : 'Hidden'; ?></td><td><a href="?id=<?php echo (int) $profession['industry_id']; ?>">Edit</a> · <a href="<?php echo ohsc_h($base_url . '/tools/interview-prep/?industry=' . rawurlencode($profession['slug'])); ?>">View page</a></td></tr>
<?php } ?></table>
<?php ohsc_tools_admin_close(); ?>
