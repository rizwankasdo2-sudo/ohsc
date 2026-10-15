<?php
require dirname(__FILE__) . '/_boot.php';

$areas = ohsc_eq_areas();
$questionId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$question = array(
    'question_id' => 0,
    'prompt' => '',
    'area' => 'self-awareness',
    'sort_order' => 1,
    'is_active' => 1,
    'choices' => array(
        array('text' => '', 'points' => 1),
        array('text' => '', 'points' => 2),
        array('text' => '', 'points' => 3),
    ),
);
$error = '';

if ($questionId > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $db->prepare("SELECT question_id, prompt, area, sort_order, is_active FROM tbl_eq_questions WHERE question_id = ?");
    $stmt->bind_param('i', $questionId);
    $stmt->execute();
    $stmt->bind_result($id, $prompt, $area, $sort, $active);
    if ($stmt->fetch()) {
        $question['question_id'] = $id;
        $question['prompt'] = $prompt;
        $question['area'] = $area;
        $question['sort_order'] = $sort;
        $question['is_active'] = $active;
    }
    $stmt->close();
    $question['choices'] = array();
    $choiceStmt = $db->prepare("SELECT choice_text, points FROM tbl_eq_choices WHERE question_id = ? ORDER BY sort_order ASC, choice_id ASC");
    $choiceStmt->bind_param('i', $questionId);
    $choiceStmt->execute();
    $choiceStmt->bind_result($choiceText, $points);
    while ($choiceStmt->fetch()) {
        $question['choices'][] = array('text' => $choiceText, 'points' => $points);
    }
    $choiceStmt->close();
    while (count($question['choices']) < 3) {
        $question['choices'][] = array('text' => '', 'points' => 1);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        $error = 'The form expired. Reload the page and try again.';
    } else {
        $question['question_id'] = isset($_POST['question_id']) ? (int) $_POST['question_id'] : 0;
        $question['prompt'] = trim(isset($_POST['prompt']) ? $_POST['prompt'] : '');
        $question['area'] = isset($_POST['area']) ? $_POST['area'] : '';
        $question['sort_order'] = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $question['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
        $postedChoices = isset($_POST['choice_text']) ? $_POST['choice_text'] : array();
        $postedPoints = isset($_POST['points']) ? $_POST['points'] : array();
        $question['choices'] = array();
        for ($i = 0; $i < 3; $i++) {
            $question['choices'][] = array(
                'text' => trim(isset($postedChoices[$i]) ? $postedChoices[$i] : ''),
                'points' => isset($postedPoints[$i]) ? (int) $postedPoints[$i] : 0,
            );
        }
        if ($question['prompt'] === '' || strlen($question['prompt']) > 400) {
            $error = 'Write a question of 400 characters or less.';
        } elseif (!isset($areas[$question['area']])) {
            $error = 'Choose one of the five areas.';
        } elseif ($question['sort_order'] < 0 || $question['sort_order'] > 999) {
            $error = 'Order must be between 0 and 999.';
        } else {
            foreach ($question['choices'] as $choice) {
                if ($choice['text'] === '' || strlen($choice['text']) > 255) {
                    $error = 'Each answer needs text of 255 characters or less.';
                    break;
                }
                if ($choice['points'] < 1 || $choice['points'] > 3) {
                    $error = 'Each answer scores 1, 2, or 3 points.';
                    break;
                }
            }
        }
        if ($error === '') {
            $now = date('Y-m-d H:i:s');
            if ($question['question_id'] > 0) {
                $stmt = $db->prepare("UPDATE tbl_eq_questions SET prompt = ?, area = ?, sort_order = ?, is_active = ?, updated_at = ? WHERE question_id = ?");
                $stmt->bind_param('ssiisi', $question['prompt'], $question['area'], $question['sort_order'], $question['is_active'], $now, $question['question_id']);
                $stmt->execute();
                $stmt->close();
                $savedId = $question['question_id'];
                $delete = $db->prepare("DELETE FROM tbl_eq_choices WHERE question_id = ?");
                $delete->bind_param('i', $savedId);
                $delete->execute();
                $delete->close();
            } else {
                $stmt = $db->prepare("INSERT INTO tbl_eq_questions (prompt, area, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssiiss', $question['prompt'], $question['area'], $question['sort_order'], $question['is_active'], $now, $now);
                $stmt->execute();
                $savedId = (int) $db->insert_id;
                $stmt->close();
            }
            $insert = $db->prepare("INSERT INTO tbl_eq_choices (question_id, choice_text, points, sort_order) VALUES (?, ?, ?, ?)");
            $choiceOrder = 1;
            foreach ($question['choices'] as $choice) {
                $insert->bind_param('isii', $savedId, $choice['text'], $choice['points'], $choiceOrder);
                $insert->execute();
                $choiceOrder++;
            }
            $insert->close();
            header('Location: ' . $base_url . '/admin/eq-questions?msg=' . rawurlencode('Question saved.'));
            exit;
        }
    }
}

ohsc_tools_admin_open($question['question_id'] ? 'Edit EQ question' : 'Add EQ question');
if ($error !== '') {
    echo '<p class="tools-error">' . ohsc_h($error) . '</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
    <input type="hidden" name="question_id" value="<?php echo (int) $question['question_id']; ?>">
    <p>Question<br><textarea name="prompt" maxlength="400" required><?php echo ohsc_h($question['prompt']); ?></textarea></p>
    <p>Area<br>
        <select name="area">
            <?php foreach ($areas as $key => $area) { ?>
            <option value="<?php echo ohsc_h($key); ?>" <?php echo ($question['area'] === $key) ? 'selected' : ''; ?>><?php echo ohsc_h($area[0]); ?></option>
            <?php } ?>
        </select>
    </p>
    <p>Order<br><input type="number" name="sort_order" min="0" max="999" value="<?php echo (int) $question['sort_order']; ?>"></p>
    <?php foreach ($question['choices'] as $index => $choice) { ?>
    <p>Answer <?php echo $index + 1; ?><br>
        <input type="text" name="choice_text[]" maxlength="255" value="<?php echo ohsc_h($choice['text']); ?>" required>
        Score
        <select name="points[]">
            <?php foreach (array(1, 2, 3) as $point) { ?>
            <option value="<?php echo $point; ?>" <?php echo ((int) $choice['points'] === $point) ? 'selected' : ''; ?>><?php echo $point; ?></option>
            <?php } ?>
        </select>
    </p>
    <?php } ?>
    <p class="tools-note">3 is the strongest answer for that area. 1 is the weakest. The test turns the total into a percentage.</p>
    <p><label><input type="checkbox" name="is_active" value="1" <?php echo ((int) $question['is_active'] === 1) ? 'checked' : ''; ?>> Ask this question in the test</label></p>
    <p><button class="save-btn" type="submit">Save</button></p>
</form>
<?php
ohsc_tools_admin_close();
