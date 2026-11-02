<?php
// Local-only integration check. New test rows are always rolled back.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['HTTP_HOST'] = 'localhost:300';
$_SERVER['REQUEST_URI'] = '/tools/interview-prep/';
require dirname(__FILE__) . '/../../admin/classes/connection.php';
require dirname(__FILE__) . '/../includes/registry.php';
function iv_check($ok, $message) { if (!$ok) { throw new RuntimeException($message); } }
$before = $db->query("SELECT question_id, industry_id, question_text, sample_answer, sort_order, is_active FROM tbl_iv_questions ORDER BY question_id")->fetch_all(MYSQLI_ASSOC);
ohsc_tools_ensure($db);
foreach ($before as $old) {
    $result = $db->query('SELECT question_id, industry_id, question_text, sample_answer, sort_order, is_active FROM tbl_iv_questions WHERE question_id = ' . (int) $old['question_id'])->fetch_assoc();
    iv_check($old == $result, 'An existing question changed during migration.');
}
$count = (int) $db->query('SELECT COUNT(*) n FROM tbl_iv_questions')->fetch_assoc()['n'];
ohsc_iv_upgrade($db);
ohsc_iv_expand_questions($db);
iv_check($count === (int) $db->query('SELECT COUNT(*) n FROM tbl_iv_questions')->fetch_assoc()['n'], 'Repeated migration duplicated questions.');
$catalog = ohsc_iv_catalog($db);
iv_check(count($catalog) >= 7, 'Expected six professions plus common collection.');
$customer = array_values(array_filter($catalog, function ($p) { return $p['slug'] === 'customer-service'; }));
iv_check(count($customer) === 1 && count($customer[0]['questions']) >= 16, 'Customer Service content is missing.');
$types = array_unique(array_column($customer[0]['questions'], 'question_type'));
iv_check(count($types) === 3, 'Missing question categories.');
$html = ohsc_iv_answer_html("<script>alert(1)</script>\n1. Listen\n2. Follow up");
iv_check(strpos($html, '<script>') === false && substr_count($html, '<li>') === 2, 'Unsafe or broken numbered answer rendering.');
$db->begin_transaction();
try {
    $slug = 'integration-test-' . bin2hex(random_bytes(4));
    $stmt = $db->prepare("INSERT INTO tbl_iv_industries (slug,title,summary,image,sort_order,is_active) VALUES (?, 'Integration Test', 'Test', 'career-office.jpg', 999, 0)");
    $stmt->bind_param('s', $slug); $stmt->execute(); $id = (int) $db->insert_id; $stmt->close();
    iv_check(!in_array($slug, array_column(ohsc_iv_catalog($db), 'slug')), 'Hidden profession is public.');
    $db->query('UPDATE tbl_iv_industries SET is_active = 1 WHERE industry_id = ' . $id);
    $stmt = $db->prepare("INSERT INTO tbl_iv_questions (industry_id,question_text,sample_answer,question_type,sort_order,is_active,created_at,updated_at) VALUES (?, 'Test question?', 'Test answer', 'behavioural', 1, 1, NOW(), NOW())");
    $stmt->bind_param('i', $id); $stmt->execute(); $qid = (int) $db->insert_id; $stmt->close();
    $entry = array_values(array_filter(ohsc_iv_catalog($db), function ($p) use ($slug) { return $p['slug'] === $slug; }))[0];
    iv_check(count($entry['questions']) === 1 && $entry['questions'][0]['question_type'] === 'behavioural', 'Saved category did not round-trip.');
    $db->query('UPDATE tbl_iv_questions SET is_active = 0 WHERE question_id = ' . $qid);
    $entry = array_values(array_filter(ohsc_iv_catalog($db), function ($p) use ($slug) { return $p['slug'] === $slug; }))[0];
    iv_check(count($entry['questions']) === 0, 'Hidden question is public.');
} finally { $db->rollback(); }
echo "PASS: existing content preserved, migration idempotent, six professions, three categories, safe numbered answers, profession/question publication and rollback.\n";
