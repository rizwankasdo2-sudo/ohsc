<?php
require dirname(__FILE__) . '/_boot.php';

$paths = ohsc_cq_paths();
$pathKeys = array_keys($paths);
$questionId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$emptyChoice = array('text' => '', 'scores' => array());
foreach ($pathKeys as $key) {
    $emptyChoice['scores'][$key] = 0;
}
$question = array(
    'question_id' => 0,
    'prompt' => '',
    'sort_order' => 1,
    'is_active' => 1,
    'choices' => array($emptyChoice, $emptyChoice, $emptyChoice),
);
$error = '';

if ($questionId > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $db->prepare("SELECT question_id, prompt, sort_order, is_active FROM tbl_cq_questions WHERE question_id = ?");
    $stmt->bind_param('i', $questionId);
    $stmt->execute();
    $stmt->bind_result($id, $prompt, $sort, $active);
    if ($stmt->fetch()) {
        $question['question_id'] = $id;
        $question['prompt'] = $prompt;
        $question['sort_order'] = $sort;
        $question['is_active'] = $active;
    }
    $stmt->close();
    $question['choices'] = array();
    $choiceStmt = $db->prepare("SELECT choice_text, path_scores FROM tbl_cq_choices WHERE question_id = ? ORDER BY sort_order ASC, choice_id ASC");
    $choiceStmt->bind_param('i', $questionId);
    $choiceStmt->execute();
    $choiceStmt->bind_result($choiceText, $scoresJson);
    while ($choiceStmt->fetch()) {
        $scores = json_decode($scoresJson, true);
        if (!is_array($scores)) {
            $scores = array();
        }
        $row = array('text' => $choiceText, 'scores' => array());
        foreach ($pathKeys as $key) {
            $row['scores'][$key] = isset($scores[$key]) ? (int) $scores[$key] : 0;
        }
        $question['choices'][] = $row;
    }
    $choiceStmt->close();
    while (count($question['choices']) < 3) {
        $question['choices'][] = $emptyChoice;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ohsc_tools_csrf_ok()) {
        $error = 'The form expired. Reload the page and try again.';
    } else {
        $question['question_id'] = isset($_POST['question_id']) ? (int) $_POST['question_id'] : 0;
        $question['prompt'] = trim(isset($_POST['prompt']) ? $_POST['prompt'] : '');
        $question['sort_order'] = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $question['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
        $postedText = isset($_POST['choice_text']) ? $_POST['choice_text'] : array();
        $postedScores = isset($_POST['score']) ? $_POST['score'] : array();
        $question['choices'] = array();
        for ($i = 0; $i < 4; $i++) {
            $text = trim(isset($postedText[$i]) ? $postedText[$i] : '');
            if ($text === '' && $i >= 3) {
                continue;
            }
            $scores = array();
            foreach ($pathKeys as $key) {
                $scores[$key] = isset($postedScores[$i][$key]) ? max(0, min(3, (int) $postedScores[$i][$key])) : 0;
            }
            $question['choices'][] = array('text' => $text, 'scores' => $scores);
        }
        while (count($question['choices']) < 3) {
            $question['choices'][] = $emptyChoice;
        }
        if ($question['prompt'] === '' || strlen($question['prompt']) > 400) {
            $error = 'Write a question of 400 characters or less.';
        } elseif ($question['sort_order'] < 0 || $question['sort_order'] > 999) {
            $error = 'Order must be between 0 and 999.';
        } else {
            $filled = 0;
            foreach ($question['choices'] as $choice) {
                if ($choice['text'] === '') {
                    continue;
                }
                if (strlen($choice['text']) > 255) {
                    $error = 'Each answer needs text of 255 characters or less.';
                    break;
                }
                $total = 0;
                foreach ($choice['scores'] as $points) {
                    $total += (int) $points;
                }
                if ($total < 1) {
                    $error = 'Each filled answer needs at least 1 path point.';
                    break;
                }
                $filled++;
            }
            if ($error === '' && $filled < 2) {
                $error = 'Add at least two answers.';
            }
        }
        if ($error === '') {
            $now = date('Y-m-d H:i:s');
            if ($question['question_id'] > 0) {
                $stmt = $db->prepare("UPDATE tbl_cq_questions SET prompt = ?, sort_order = ?, is_active = ?, updated_at = ? WHERE question_id = ?");
                $stmt->bind_param('siisi', $question['prompt'], $question['sort_order'], $question['is_active'], $now, $question['question_id']);
                $stmt->execute();
                $stmt->close();
                $savedId = $question['question_id'];
                $delete = $db->prepare("DELETE FROM tbl_cq_choices WHERE question_id = ?");
                $delete->bind_param('i', $savedId);
                $delete->execute();
                $delete->close();
            } else {
                $stmt = $db->prepare("INSERT INTO tbl_cq_questions (prompt, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('siiss', $question['prompt'], $question['sort_order'], $question['is_active'], $now, $now);
                $stmt->execute();
                $savedId = (int) $db->insert_id;
                $stmt->close();
            }
            $insert = $db->prepare("INSERT INTO tbl_cq_choices (question_id, choice_text, path_scores, sort_order) VALUES (?, ?, ?, ?)");
            $choiceOrder = 1;
            foreach ($question['choices'] as $choice) {
                if ($choice['text'] === '') {
                    continue;
                }
                $scoresJson = json_encode($choice['scores']);
                $insert->bind_param('issi', $savedId, $choice['text'], $scoresJson, $choiceOrder);
                $insert->execute();
                $choiceOrder++;
            }
            $insert->close();
            header('Location: ' . $base_url . '/admin/career-quiz-questions?msg=' . rawurlencode('Question saved.'));
            exit;
        }
    }
}

ohsc_tools_admin_open($question['question_id'] ? 'Edit Career Quiz question' : 'Add Career Quiz question');
if ($error !== '') {
    echo '<p class="tools-error">' . ohsc_h($error) . '</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
    <input type="hidden" name="question_id" value="<?php echo (int) $question['question_id']; ?>">
    <p>Question<br><textarea name="prompt" maxlength="400" required><?php echo ohsc_h($question['prompt']); ?></textarea></p>
    <p>Order<br><input type="number" name="sort_order" min="0" max="999" value="<?php echo (int) $question['sort_order']; ?>"></p>
    <?php for ($i = 0; $i < 4; $i++) {
        $choice = isset($question['choices'][$i]) ? $question['choices'][$i] : $emptyChoice;
        ?>
    <div style="margin:16px 0;padding:12px;border:1px solid #e5e5e5;background:#fafafa;">
        <p><strong>Answer <?php echo $i + 1; ?></strong><?php echo $i >= 3 ? ' (optional)' : ''; ?><br>
            <input type="text" name="choice_text[<?php echo $i; ?>]" maxlength="255" value="<?php echo ohsc_h($choice['text']); ?>" <?php echo $i < 2 ? 'required' : ''; ?>>
        </p>
        <p>Path points (0–3)</p>
        <?php foreach ($pathKeys as $key) {
            $label = isset($paths[$key]['title']) ? $paths[$key]['title'] : $key;
            $value = isset($choice['scores'][$key]) ? (int) $choice['scores'][$key] : 0;
            ?>
        <label style="display:inline-block;margin:0 12px 8px 0;"><?php echo ohsc_h($label); ?>
            <input type="number" name="score[<?php echo $i; ?>][<?php echo ohsc_h($key); ?>]" min="0" max="3" value="<?php echo $value; ?>" style="width:64px;">
        </label>
        <?php } ?>
    </div>
    <?php } ?>
    <p class="tools-note">Give the strongest matching path 2 or 3 points. Other paths can stay at 0.</p>
    <p><label><input type="checkbox" name="is_active" value="1" <?php echo ((int) $question['is_active'] === 1) ? 'checked' : ''; ?>> Ask this question in the quiz</label></p>
    <p><button class="save-btn" type="submit">Save</button></p>
</form>
<?php
ohsc_tools_admin_close();
