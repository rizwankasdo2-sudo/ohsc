<?php
/** Additive interview upgrade: existing professions, questions and answers are retained. */
function ohsc_iv_upgrade($db)
{
    $db->query("CREATE TABLE IF NOT EXISTS tbl_iv_migrations (version INT NOT NULL PRIMARY KEY, applied_at DATETIME NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $done = $db->query("SELECT version FROM tbl_iv_migrations WHERE version = 1");
    if ($done && $done->num_rows) { return; }
    $column = $db->query("SHOW COLUMNS FROM tbl_iv_questions LIKE 'question_type'");
    if ($column && !$column->num_rows) {
        try { $db->query("ALTER TABLE tbl_iv_questions ADD COLUMN question_type VARCHAR(20) NOT NULL DEFAULT 'common'"); }
        catch (mysqli_sql_exception $error) { if ((int) $error->getCode() !== 1060) { throw $error; } }
    }
    $db->begin_transaction();
    try {
        $db->query("UPDATE tbl_iv_questions SET question_type = 'behavioural' WHERE question_type = 'common' AND (question_text LIKE 'Describe a time%' OR question_text LIKE 'Tell us about a time%' OR question_text LIKE 'Tell me about a time%')");
        $db->query("UPDATE tbl_iv_questions SET question_type = 'situational' WHERE question_type = 'common' AND (question_text LIKE 'How would%' OR question_text LIKE 'What would%')");
        $seeds = require dirname(__FILE__) . '/interview-upgrade-seed.php';
        foreach ($seeds as $seed) {
            $stmt = $db->prepare("INSERT IGNORE INTO tbl_iv_industries (slug, title, summary, image, sort_order, is_active) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->bind_param('ssssi', $seed['slug'], $seed['title'], $seed['summary'], $seed['image'], $seed['order']);
            $stmt->execute();
            $new = $stmt->affected_rows === 1;
            $id = (int) $db->insert_id;
            $stmt->close();
            if (!$new) { continue; }
            $insert = $db->prepare("INSERT INTO tbl_iv_questions (industry_id, question_text, sample_answer, question_type, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())");
            foreach ($seed['questions'] as $i => $question) {
                $order = $i + 1;
                $insert->bind_param('isssi', $id, $question[0], $question[1], $question[2], $order);
                $insert->execute();
            }
            $insert->close();
        }
        $db->query("INSERT IGNORE INTO tbl_iv_migrations (version, applied_at) VALUES (1, NOW())");
        $db->commit();
    } catch (Throwable $error) { $db->rollback(); throw $error; }
}

function ohsc_iv_catalog($db)
{
    $items = array();
    $result = $db->query("SELECT industry_id, slug, title, summary, image FROM tbl_iv_industries WHERE is_active = 1 ORDER BY sort_order, industry_id");
    while ($row = $result->fetch_assoc()) {
        $row['questions'] = array();
        $items[(int) $row['industry_id']] = $row;
    }
    if (!$items) { return array(); }
    $ids = implode(',', array_map('intval', array_keys($items)));
    $result = $db->query("SELECT question_id, industry_id, question_text, sample_answer, question_type FROM tbl_iv_questions WHERE is_active = 1 AND industry_id IN ($ids) ORDER BY sort_order, question_id");
    while ($row = $result->fetch_assoc()) { $items[(int) $row['industry_id']]['questions'][] = $row; }
    return array_values($items);
}

function ohsc_iv_answer_html($answer)
{
    // Plain text plus numbered lines. Never render administrator-supplied HTML.
    $lines = preg_split('/\R/u', trim($answer));
    $html = ''; $inList = false;
    foreach ($lines as $line) {
        if (preg_match('/^\s*\d+[.)]\s+(.+)$/u', $line, $match)) {
            if (!$inList) { $html .= '<ol>'; $inList = true; }
            if (preg_match('/^(.{1,50}?)\s+-\s+(.+)$/u', $match[1], $parts)) {
                $html .= '<li><strong>' . ohsc_h($parts[1]) . '</strong> – ' . ohsc_h($parts[2]) . '</li>';
            } else { $html .= '<li>' . ohsc_h($match[1]) . '</li>'; }
        } else {
            if ($inList) { $html .= '</ol>'; $inList = false; }
            if (trim($line) !== '') { $html .= '<p>' . ohsc_h($line) . '</p>'; }
        }
    }
    return $html . ($inList ? '</ol>' : '');
}

function ohsc_iv_question_type($type)
{
    return in_array($type, array('common', 'behavioural', 'situational'), true);
}

function ohsc_iv_article_route($db, $topic)
{
    $term = '%' . $topic . '%';
    $stmt = $db->prepare("SELECT slug FROM blog WHERE status = 1 AND slug <> '' AND title LIKE ? ORDER BY publish_date DESC LIMIT 1");
    $stmt->bind_param('s', $term); $stmt->execute(); $stmt->bind_result($slug);
    $route = $stmt->fetch() ? '/OHSC-Blog/' . rawurlencode($slug) : '/OHSC-Blog';
    $stmt->close(); return $route;
}

function ohsc_iv_expand_questions($db)
{
    $done = $db->query("SELECT version FROM tbl_iv_migrations WHERE version = 2");
    if ($done && $done->num_rows) { return; }
    $db->begin_transaction();
    try {
        // The marker also serialises concurrent first requests; rollback removes it on failure.
        $db->query("INSERT IGNORE INTO tbl_iv_migrations (version, applied_at) VALUES (2, NOW())");
        if ($db->affected_rows !== 1) { $db->commit(); return; }
        $sets = require dirname(__FILE__) . '/interview-extra-questions.php';
        foreach ($sets as $slug => $questions) {
            $find = $db->prepare("SELECT industry_id FROM tbl_iv_industries WHERE slug = ?");
            $find->bind_param('s', $slug); $find->execute(); $find->bind_result($id);
            $exists = $find->fetch(); $find->close();
            if (!$exists) { continue; }
            $id = (int) $id;
            $order = (int) $db->query('SELECT COALESCE(MAX(sort_order),0) n FROM tbl_iv_questions WHERE industry_id = ' . $id)->fetch_assoc()['n'];
            foreach ($questions as $question) {
                $check = $db->prepare("SELECT question_id FROM tbl_iv_questions WHERE industry_id = ? AND question_text = ?");
                $check->bind_param('is', $id, $question[0]); $check->execute(); $check->store_result(); $duplicate = $check->num_rows > 0; $check->close();
                if ($duplicate) { continue; }
                $order = min(999, $order + 1);
                $insert = $db->prepare("INSERT INTO tbl_iv_questions (industry_id,question_text,sample_answer,question_type,sort_order,is_active,created_at,updated_at) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())");
                $insert->bind_param('isssi', $id, $question[0], $question[1], $question[2], $order);
                $insert->execute(); $insert->close();
            }
        }
        $db->commit();
    } catch (Throwable $error) { $db->rollback(); throw $error; }
}
