<?php
require dirname(__FILE__) . '/_boot.php';

$industries = array();
$industryResult = $db->query("SELECT industry_id, title FROM tbl_iv_industries WHERE is_active = 1 ORDER BY sort_order ASC, industry_id ASC");
if ($industryResult) {
    while ($row = $industryResult->fetch_assoc()) {
        $industries[] = $row;
    }
}

$questionId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$question = array(
    'question_id' => 0,
    'industry_id' => !empty($industries[0]['industry_id']) ? (int) $industries[0]['industry_id'] : 0,
    'question_text' => '',
    'sample_answer' => '',
    'sort_order' => 1,
    'is_active' => 1,
);
$error = '';

if ($questionId > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $db->prepare("SELECT question_id, industry_id, question_text, sample_answer, sort_order, is_active FROM tbl_iv_questions WHERE question_id = ?");
    $stmt->bind_param('i', $questionId);
    $stmt->execute();
    $stmt->bind_result($id, $industryId, $text, $answer, $sort, $active);
    if ($stmt->fetch()) {
        $question['question_id'] = $id;
        $question['industry_id'] = $industryId;
        $question['question_text'] = $text;
        $question['sample_answer'] = $answer;
        $question['sort_order'] = $sort;
        $question['is_active'] = $active;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        $error = 'The form expired. Reload the page and try again.';
    } else {
        $question['question_id'] = isset($_POST['question_id']) ? (int) $_POST['question_id'] : 0;
        $question['industry_id'] = isset($_POST['industry_id']) ? (int) $_POST['industry_id'] : 0;
        $question['question_text'] = trim(isset($_POST['question_text']) ? $_POST['question_text'] : '');
        $question['sample_answer'] = trim(isset($_POST['sample_answer']) ? $_POST['sample_answer'] : '');
        $question['sort_order'] = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $question['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
        $industryOk = false;
        foreach ($industries as $industry) {
            if ((int) $industry['industry_id'] === $question['industry_id']) {
                $industryOk = true;
                break;
            }
        }
        if ($question['question_text'] === '' || strlen($question['question_text']) > 400) {
            $error = 'Write a question of 400 characters or less.';
        } elseif ($question['sample_answer'] === '' || strlen($question['sample_answer']) > 2000) {
            $error = 'Write a sample answer of 2000 characters or less.';
        } elseif (!$industryOk) {
            $error = 'Choose an industry.';
        } elseif ($question['sort_order'] < 0 || $question['sort_order'] > 999) {
            $error = 'Order must be between 0 and 999.';
        } else {
            $now = date('Y-m-d H:i:s');
            if ($question['question_id'] > 0) {
                $stmt = $db->prepare("UPDATE tbl_iv_questions SET industry_id = ?, question_text = ?, sample_answer = ?, sort_order = ?, is_active = ?, updated_at = ? WHERE question_id = ?");
                $stmt->bind_param('issiisi', $question['industry_id'], $question['question_text'], $question['sample_answer'], $question['sort_order'], $question['is_active'], $now, $question['question_id']);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $db->prepare("INSERT INTO tbl_iv_questions (industry_id, question_text, sample_answer, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('issiiss', $question['industry_id'], $question['question_text'], $question['sample_answer'], $question['sort_order'], $question['is_active'], $now, $now);
                $stmt->execute();
                $stmt->close();
            }
            header('Location: ' . $base_url . '/admin/interview-questions?msg=' . rawurlencode('Question saved.'));
            exit;
        }
    }
}

ohsc_tools_admin_open($question['question_id'] ? 'Edit interview question' : 'Add interview question');
if ($error !== '') {
    echo '<p class="tools-error">' . ohsc_h($error) . '</p>';
}
if (!$industries) {
    echo '<p class="tools-error">No industries are ready yet. Open the Interview Prep tool once so the default industries can be created.</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
    <input type="hidden" name="question_id" value="<?php echo (int) $question['question_id']; ?>">
    <p>Industry<br>
        <select name="industry_id" required>
            <?php foreach ($industries as $industry) { ?>
            <option value="<?php echo (int) $industry['industry_id']; ?>" <?php echo ((int) $question['industry_id'] === (int) $industry['industry_id']) ? 'selected' : ''; ?>><?php echo ohsc_h($industry['title']); ?></option>
            <?php } ?>
        </select>
    </p>
    <p>Question<br><textarea name="question_text" maxlength="400" required><?php echo ohsc_h($question['question_text']); ?></textarea></p>
    <p>Sample answer tip<br><textarea name="sample_answer" maxlength="2000" required><?php echo ohsc_h($question['sample_answer']); ?></textarea></p>
    <p>Order<br><input type="number" name="sort_order" min="0" max="999" value="<?php echo (int) $question['sort_order']; ?>"></p>
    <p><label><input type="checkbox" name="is_active" value="1" <?php echo ((int) $question['is_active'] === 1) ? 'checked' : ''; ?>> Show this question in the tool</label></p>
    <p><button class="save-btn" type="submit" <?php echo !$industries ? 'disabled' : ''; ?>>Save</button></p>
</form>
<?php
ohsc_tools_admin_close();
