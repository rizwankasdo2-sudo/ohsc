<?php
require_once dirname(__FILE__) . '/../includes/lock.php';
include dirname(__FILE__) . '/../../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../../includes/functions.php';
require_once dirname(__FILE__) . '/../includes/registry.php';
ohsc_image_start($db, 'interview-prep', $base_url);
ohsc_tools_ensure($db);
$site = rtrim($base_url, '/');
$asset = $site . '/tools/assets/';
$ivBase = $site . '/tools/interview-prep/';
$catalog = ohsc_iv_catalog($db);
$professions = array(); $common = array(); $selected = null;
$requested = isset($_GET['industry']) && is_string($_GET['industry']) ? trim($_GET['industry']) : '';
foreach ($catalog as $profession) {
    if ($profession['slug'] === 'general') { $common = $profession['questions']; }
    else { $professions[] = $profession; }
    if ($requested !== '' && $profession['slug'] === $requested) { $selected = $profession; }
}
$articleRoutes = array(
    'tips' => ohsc_iv_article_route($db, 'interview'),
    'career' => ohsc_iv_article_route($db, $selected && $selected['slug'] !== 'general' ? $selected['title'] : 'career'),
    'cv' => ohsc_iv_article_route($db, 'cv'),
    'impression' => ohsc_iv_article_route($db, 'first impression'),
    'after' => ohsc_iv_article_route($db, 'after the interview'),
);
$notFound = $requested !== '' && !$selected;
if ($notFound) { http_response_code(404); }
$title = $selected ? $selected['title'] . ' Interview Questions' : 'Interview Preparation';
if ($selected && $selected['slug'] === 'general') { $title = 'Common Interview Questions'; }
$search = isset($_GET['q']) && is_string($_GET['q']) ? substr(trim($_GET['q']), 0, 200) : '';
$type = isset($_GET['type']) && is_string($_GET['type']) && ohsc_iv_question_type($_GET['type']) ? $_GET['type'] : 'all';
$questions = $selected ? $selected['questions'] : $common;
$questionMatchCount = count(array_filter($questions, function ($q) use ($selected, $type, $search) {
    return !$selected || (($type === 'all' || $q['question_type'] === $type) && ($search === '' || stripos($q['question_text'] . ' ' . $q['sample_answer'], $search) !== false));
}));
$professionMatchCount = count(array_filter($professions, function ($p) use ($search) { return $search === '' || stripos($p['title'] . ' ' . $p['summary'], $search) !== false; }));
$ohsc_tool_meta=$notFound?null:ohsc_tool_seo_init($db,'interview-prep',$base_url,$selected?(int)$selected['industry_id']:0);$ohsc_resource_meta=true;
$ivBase=ohsc_tool_url($db,$base_url,'interview-prep');
function iv_cards($items, $site, $asset) {
    foreach ($items as $item) { ?>
    <a class="iv-story" href="<?php echo ohsc_h($site . $item[3]); ?>"><img src="<?php echo ohsc_h($asset . $item[2]); ?>" alt="" loading="lazy" width="560" height="220"><div><h3><?php echo ohsc_h($item[0]); ?><span aria-hidden="true">→</span></h3><p><?php echo ohsc_h($item[1]); ?></p></div></a>
    <?php }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php if ($ohsc_tool_meta) { ohsc_tool_seo_head($ohsc_tool_meta); } else { ?><title>Profession not found | OHSC Career Expert</title><meta name="robots" content="noindex, nofollow"><?php } ?>
    <?php include dirname(__FILE__) . '/../../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($asset); ?>interview-prep.css?v=8">
    <main class="ohsc-iv" id="ohsc-iv">
        <div class="iv-wrap">
        <?php if ($notFound) { ?>
            <section class="iv-panel iv-not-found"><h1>Profession not found</h1><p>This profession is unavailable. Explore the published professions to find your practice questions.</p><a class="iv-go" href="<?php echo ohsc_h($ivBase); ?>">Explore professions <span aria-hidden="true">→</span></a></section>
        <?php } else { ?>
            <?php if ($selected) { ?><nav class="iv-breadcrumb" aria-label="Breadcrumb"><a href="<?php echo ohsc_h($ivBase); ?>">Interview Questions</a><span aria-hidden="true">›</span><span aria-current="page"><?php echo ohsc_h($selected['title']); ?></span></nav><?php } ?>
            <section class="iv-hero<?php echo $selected ? ' iv-detail-hero' : ''; ?>">
                <div class="iv-hero-copy">
                    <?php if ($selected) { ?>
                    <span class="iv-badge">FREE PRACTICE</span>
                    <h1><?php echo ohsc_h($selected['slug'] === 'general' ? 'Common' : $selected['title']); ?><br><span>Interview Questions</span></h1>
                    <p>Prepare for real questions and shape stronger answers.</p>
                    <a class="iv-go" href="#iv-questions">Explore Questions <span aria-hidden="true">→</span></a>
                    <?php } else { ?>
                    <h1>Walk into your next<br>interview ready.</h1>
                    <span class="iv-badge">FREE INTERVIEW PREP</span>
                    <p>Questions, answers and practice for every career.</p>
                    <a class="iv-go" href="#iv-professions">Explore Interview Questions <span aria-hidden="true">→</span></a>
                    <?php } ?>
                </div>
                <div class="iv-hero-art"><img src="<?php echo ohsc_h($asset); ?>interview-hero.png" alt="Candidate speaking confidently with an interviewer" width="900" height="600" fetchpriority="high"></div>
            </section>

            <div id="iv-practice" aria-hidden="true"></div>
            <?php if (!$selected) { ?>
            <section class="iv-panel" id="iv-professions">
                <h2>Prepare for your profession</h2><p class="iv-sub">Browse interview questions and practise for your career.</p>
                <form class="iv-search" role="search" method="get" action="<?php echo ohsc_h($ivBase); ?>#iv-professions"><label class="iv-sr" for="ivProfessionSearch">Search professions</label><input id="ivProfessionSearch" name="q" type="search" maxlength="200" placeholder="Search a profession..." value="<?php echo ohsc_h($search); ?>"><button type="submit" aria-label="Search professions">⌕</button></form>
                <div class="iv-professions" id="ivProfessions">
                    <?php foreach ($professions as $i => $profession) {
                        $match = $search === '' || stripos($profession['title'] . ' ' . $profession['summary'], $search) !== false;
                    ?>
                    <a class="iv-profession iv-tone-<?php echo $i % 6; ?>" href="<?php echo ohsc_h($ivBase . '?industry=' . rawurlencode($profession['slug'])); ?>" data-profession="<?php echo ohsc_h($profession['title'] . ' ' . $profession['summary']); ?>" <?php echo $match ? '' : 'hidden'; ?>><img src="<?php echo ohsc_h(ohsc_iv_image_url($site,$profession['image'])); ?>" alt="" loading="lazy" width="180" height="140"><strong><?php echo ohsc_h($profession['title']); ?></strong><span aria-hidden="true">→</span></a>
                    <?php } ?>
                </div>
                <p id="ivProfessionEmpty" class="iv-empty" <?php echo $professionMatchCount ? 'hidden' : ''; ?>>No professions match your search. Try another name.</p>
                <?php if (!$professions) { ?><p class="iv-empty">No professions are published yet. You can still explore the common questions below.</p><?php } ?>
                <button id="ivAllProfessions" type="button" class="iv-text-link" hidden>View all professions <span aria-hidden="true">→</span></button>
                <p class="iv-sr" id="ivProfessionCount" role="status"></p>
            </section>
            <?php } ?>

            <section class="iv-panel iv-question-panel<?php echo !$selected ? ' iv-common-panel' : ''; ?>" id="<?php echo $selected ? 'iv-questions' : 'iv-common'; ?>">
                <h2><?php echo $selected ? 'Explore ' . ohsc_h($selected['slug'] === 'general' ? 'Common' : $selected['title']) . ' Interview Questions' : 'Practise common interview questions'; ?></h2>
                <?php if (!$selected) { ?><p class="iv-sub">Explore the most common interview questions with sample answers.</p><?php } else { ?>
                <form class="iv-search" role="search" method="get" action="<?php echo ohsc_h($ivBase); ?>#iv-questions"><input type="hidden" name="industry" value="<?php echo ohsc_h($selected['slug']); ?>"><label class="iv-sr" for="ivQuestionSearch">Search questions</label><input id="ivQuestionSearch" name="q" type="search" maxlength="200" placeholder="Search questions..." value="<?php echo ohsc_h($search); ?>"><input type="hidden" name="type" id="ivTypeInput" value="<?php echo ohsc_h($type); ?>"><button type="submit" aria-label="Search questions">⌕</button></form>
                <nav class="iv-filters" aria-label="Question categories">
                    <?php foreach (array('all' => 'All Questions', 'common' => 'Common', 'behavioural' => 'Behavioural', 'situational' => 'Situational') as $filter => $label) { ?>
                    <a href="<?php echo ohsc_h($ivBase . '?industry=' . rawurlencode($selected['slug']) . '&type=' . $filter . '&q=' . rawurlencode($search)); ?>#iv-questions" data-filter="<?php echo $filter; ?>" class="<?php echo $type === $filter ? 'is-active' : ''; ?>" <?php echo $type === $filter ? 'aria-current="true"' : ''; ?>><?php echo $label; ?></a>
                    <?php } ?>
                </nav>
                <?php } ?>
                <div class="iv-question-list" id="ivQuestionList">
                    <?php foreach ($questions as $i => $question) {
                        if (!$selected && $i >= 4) { break; }
                        $match = !$selected || (($type === 'all' || $question['question_type'] === $type) && ($search === '' || stripos($question['question_text'] . ' ' . $question['sample_answer'], $search) !== false));
                    ?>
                    <details class="iv-question" data-type="<?php echo ohsc_h($question['question_type']); ?>" <?php echo $i === 0 ? 'open' : ''; ?> <?php echo $match ? '' : 'hidden'; ?>><summary><?php if ($selected) { ?><span class="iv-question-num"><?php echo $i + 1; ?></span><?php } ?><h3><?php echo ohsc_h($question['question_text']); ?></h3><span class="iv-chevron" aria-hidden="true"></span></summary><div class="iv-answer"><?php echo ohsc_iv_answer_html($question['sample_answer']); ?></div></details>
                    <?php } ?>
                </div>
                <p class="iv-empty" id="ivQuestionEmpty" <?php echo $questionMatchCount ? 'hidden' : ''; ?>>No questions match. Try another search or category.</p>
                <p class="iv-sr" id="ivQuestionCount" role="status"></p>
                <?php if ($selected) { ?><button class="iv-go iv-wide" type="button" id="ivMoreQuestions" hidden>See More Questions <span aria-hidden="true">→</span></button><?php } else { ?><a class="iv-go iv-wide" href="<?php echo ohsc_h($ivBase . '?industry=general'); ?>">Practise More Questions <span aria-hidden="true">→</span></a><?php } ?>
            </section>

            <?php if ($selected) { ?>
            <a class="iv-tips-banner" href="#iv-related-guides"><span class="iv-bulb" aria-hidden="true"><svg viewBox="0 0 64 80" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M21 60h22M23 67h18M27 73h10" stroke="#6680a7"/><path d="M32 12c-24 0-27 29-14 39l5 9h18l5-9C59 41 56 12 32 12z" fill="#ffb114"/><path d="M25 20c-6 3-8 7-8 12" stroke="white"/><path d="M32 2v5M7 13l4 4m42 0 4-4M2 35h5m50 0h5" stroke="#10265f"/></svg></span><div><h2><?php echo ohsc_h($selected['slug'] === 'general' ? 'Practical' : $selected['title']); ?> Interview Tips</h2><p>Get practical advice to help you give confident and well-structured answers.</p></div><span aria-hidden="true">→</span></a>
            <section class="iv-panel iv-mock" id="iv-mock"><div class="iv-mock-intro"><img src="<?php echo ohsc_h($asset); ?>career-service.jpg" alt="" loading="lazy" width="480" height="280"><div><h2>Try a Mock Interview</h2><p>Practise with realistic questions and get comfortable before your interview.</p><button type="button" class="iv-gold" id="ivMockStart" <?php echo !$questions ? 'disabled' : ''; ?>>Start Practising <span aria-hidden="true">→</span></button></div></div><div id="ivMockBoard" class="iv-mock-board" hidden><p class="iv-mock-progress" id="ivMockProgress" role="status"></p><h3 id="ivMockQuestion"></h3><p>Answer out loud, then compare your response with the sample guidance.</p><button type="button" class="iv-outline" id="ivMockReveal">Show sample guidance</button><div class="iv-answer" id="ivMockAnswer" hidden></div><div class="iv-mock-actions"><button type="button" class="iv-outline" id="ivMockEnd">End practice</button><button type="button" class="iv-go" id="ivMockNext">Next Question <span aria-hidden="true">→</span></button></div></div></section>
            <section class="iv-section" id="iv-related-guides"><div class="iv-section-head"><h2>Related Career Guides</h2><a href="<?php echo ohsc_h($site . '/career-expert/career-guides'); ?>">View all guides <span aria-hidden="true">→</span></a></div><div class="iv-stories"><?php iv_cards(array(array($selected['slug'] === 'general' ? 'Explore your career options' : $selected['title'] . ' Career Guide', 'Learn about roles, key skills and career opportunities.', 'career-office.jpg', $articleRoutes['career']), array('How to Write a Standout CV', 'Build a CV that highlights your strengths and experience.', 'career-data.jpg', $articleRoutes['cv'])), $site, $asset); ?></div></section>
            <section class="iv-section"><div class="iv-section-head"><h2>More Interview Resources</h2><a href="<?php echo ohsc_h($ivBase); ?>">View all resources <span aria-hidden="true">→</span></a></div><div class="iv-stories"><?php iv_cards(array(array('Common Interview Questions', 'Explore more frequently asked questions with sample answers.', 'interview-hero.png', '/tools/interview-prep/?industry=general'), array('What to Do After the Interview', 'Find out the next steps and how to follow up professionally.', 'career-data.jpg', $articleRoutes['after'])), $site, $asset); ?></div></section>
            <?php } else { ?>
            <section class="iv-panel"><h2>Interview tips &amp; guides</h2><p class="iv-sub">Helpful advice to build your confidence and perform your best.</p><div class="iv-stories"><?php iv_cards(array(array('Interview Tips', 'Practical advice for every stage of your interview.', 'career-office.jpg', $articleRoutes['tips']), array('Career Guides', 'Step-by-step guides to help you succeed.', 'career-data.jpg', $articleRoutes['career'])), $site, $asset); ?></div>
                <h2 class="iv-spaced">More ways to prepare</h2><p class="iv-sub">Use our free tools to build your confidence.</p><div class="iv-tools"><a href="<?php echo ohsc_h($site . '/tools/cv-builder/'); ?>"><span class="iv-tool-icon" aria-hidden="true">CV</span><div><h3>Create a CV</h3><p>Build a professional CV in minutes.</p></div><span aria-hidden="true">→</span></a><a href="<?php echo ohsc_h($ivBase . '?industry=general'); ?>#iv-mock"><span class="iv-tool-icon" aria-hidden="true"><svg viewBox="0 0 80 64" fill="currentColor"><circle cx="40" cy="16" r="12"/><circle cx="15" cy="23" r="9"/><circle cx="65" cy="23" r="9"/><path d="M19 60V48c0-23 42-23 42 0v12zM0 52v-7c0-16 19-19 23-8-8 5-9 10-9 15zm66 0c0-5-1-10-9-15 4-11 23-8 23 8v7z"/></svg></span><div><h3>Try a Mock Interview</h3><p>Practise with realistic questions.</p></div><span aria-hidden="true">→</span></a></div>
                <div class="iv-section-head iv-spaced"><div><h2>Related learning</h2><p class="iv-sub">Explore more free resources to support your career journey.</p></div><a href="<?php echo ohsc_h($site . '/career-expert/career-guides'); ?>">View all resources <span aria-hidden="true">→</span></a></div><div class="iv-stories iv-related"><?php iv_cards(array(array('How to Answer Common Interview Questions', '', 'career-office.jpg', '/tools/interview-prep/?industry=general'), array('How to Make a Great First Impression', '', 'career-data.jpg', $articleRoutes['impression']), array('What to Do After the Interview', '', 'career-service.jpg', $articleRoutes['after'])), $site, $asset); ?></div>
            </section>
            <section class="iv-cta"><div><h2>Ready to feel confident<br>in your next interview?</h2><p>Explore interview questions, guides and free tools<br>for your profession.</p></div><a class="iv-gold" href="#iv-professions">Explore Interview Questions <span aria-hidden="true">→</span></a></section>
            <?php } ?>
        <?php } ?>
        </div>
    </main>
    <script>window.OHSC_IV_PRACTICE = <?php echo json_encode($selected ? array_map(function ($q) { return array('question' => $q['question_text'], 'answer' => $q['sample_answer']); }, $questions) : array(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;</script>
    <script src="<?php echo ohsc_h($asset); ?>interview-prep.js?v=6" defer></script>
    <?php include dirname(__FILE__) . '/../../includes/footer-new.php'; ?>
</html>
