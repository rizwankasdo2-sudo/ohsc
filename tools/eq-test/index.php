<?php
include dirname(__FILE__) . '/../../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../../includes/functions.php';
require_once dirname(__FILE__) . '/../includes/registry.php';

$questions = ohsc_eq_questions($db);
$areas = ohsc_eq_areas();
$questionCount = count($questions);
$minutes = max(4, (int) round($questionCount / 4));
$courseIds = array(1366, 1129, 1369, 1374);
$coursePhotos = array(
    1366 => 'eq-course-eq.jpg',
    1129 => 'eq-course-talk.jpg',
    1369 => 'eq-course-conflict.jpg',
    1374 => 'eq-course-people.jpg',
);
$idList = implode(',', array_map('intval', $courseIds));
$courseResult = $db->query("SELECT td.diplomaid, td.name, td.slug, tc.slug AS catslug
    FROM tbl_diploma AS td
    INNER JOIN tbl_courses AS tc ON tc.cid = td.cid
    WHERE td.IStatus = 1 AND tc.IStatus = 1 AND td.diplomaid IN ($idList)
    ORDER BY FIELD(td.diplomaid, $idList)");
$payload = array();
foreach ($questions as $question) {
    $payload[] = array(
        'id' => (int) $question['question_id'],
        'prompt' => $question['prompt'],
        'area' => $question['area'],
        'choices' => $question['choices'],
    );
}
$benefits = array(
    array('Build Stronger Relationships', 'Communicate better and connect more deeply with the people around you.', 'career-service.jpg'),
    array('Manage Stress', 'Handle challenges more calmly and stay resilient when plans change.', 'career-data.jpg'),
    array('Make Better Decisions', 'Understand your emotions and make more balanced choices.', 'career-office.jpg'),
);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Emotional Intelligence Test | Oxford Home Study Centre</title>
    <meta name="description" content="Take a free emotional intelligence test. Answer short questions and see how you notice, manage, and respond to emotions.">
    <?php include dirname(__FILE__) . '/../../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($base_url); ?>/tools/assets/eq-test.css?v=2">
    <main class="ohsc-eq" id="ohsc-eq">
        <div class="ohsc-wrap">
            <section class="ohsc-hero">
                <div>
                    <span class="ohsc-badge">FREE EQ TEST</span>
                    <h1>Understand<br>your <span>emotions.</span></h1>
                    <p class="lede">Discover how you think, feel and connect.</p>
                    <a class="ohsc-go" href="#eq-test">Start Free Test</a>
                    <p class="ohsc-meta"><?php echo (int) $questionCount; ?> questions · About <?php echo (int) $minutes; ?> minutes</p>
                </div>
                <div class="ohsc-art" aria-hidden="true">
                    <img src="<?php echo ohsc_h($base_url); ?>/tools/assets/eq-brain.png" alt="">
                </div>
            </section>

            <section class="ohsc-section">
                <h2>What will you discover?</h2>
                <p class="sub">Explore five key areas of emotional intelligence.</p>
                <div class="ohsc-areas">
                    <?php
                    $areaIcons = array(
                        'self-awareness' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M6.5 19c.8-3 2.8-4.5 5.5-4.5S16.7 16 17.5 19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
                        'self-regulation' => '<svg viewBox="0 0 24 24"><path d="M4 15c2.2-4 3.4-4 5.2 0s3 4 5.2 0 3-4 5.6 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
                        'motivation' => '<svg viewBox="0 0 24 24"><path d="M12 20s-6-3.6-6-8.2A3.4 3.4 0 0 1 12 9a3.4 3.4 0 0 1 6 2.8C18 16.4 12 20 12 20z" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>',
                        'empathy' => '<svg viewBox="0 0 24 24"><circle cx="9" cy="9" r="2.4" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="16" cy="10" r="2.1" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="M4.8 18c.6-2.4 2.2-3.6 4.2-3.6s3.6 1.2 4.2 3.6M13.2 18c.4-1.8 1.6-2.8 3.2-2.8 1.4 0 2.5.8 3 2.4" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
                        'social-skills' => '<svg viewBox="0 0 24 24"><path d="M5 16.5V8.8A2.8 2.8 0 0 1 7.8 6h5.2A2.8 2.8 0 0 1 15.8 8.8V13a2.8 2.8 0 0 1-2.8 2.8H8.2L5 18.2v-1.7z" fill="none" stroke="currentColor" stroke-width="1.7"/><path d="M10 12.2h6.1A2.4 2.4 0 0 0 18.5 9.8V8" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>',
                    );
                    foreach ($areas as $key => $area) { ?>
                    <article class="ohsc-area <?php echo ohsc_h($key); ?>">
                        <span class="ohsc-ico"><?php echo $areaIcons[$key]; ?></span>
                        <h3><?php echo ohsc_h($area[0]); ?></h3>
                        <p><?php echo ohsc_h($area[1]); ?></p>
                    </article>
                    <?php } ?>
                </div>
            </section>

            <section class="ohsc-section" id="eq-test">
                <h2>A glimpse of the test</h2>
                <p class="sub">Answer simple questions about how you think, feel and behave.</p>
                <div class="ohsc-quiz" id="eqQuiz">
                    <p class="ohsc-progress" id="eqProgress">Question 1 of <?php echo (int) $questionCount; ?></p>
                    <h3 id="eqPrompt">Loading the first question...</h3>
                    <div class="ohsc-choices" id="eqChoices"></div>
                    <p class="ohsc-need" id="eqNeed" hidden>Choose an answer to continue.</p>
                    <button type="button" class="ohsc-go wide" id="eqNext">Start Free Test</button>
                </div>
                <div class="ohsc-score" id="eqScore" hidden>
                    <p class="ohsc-kicker">Your result</p>
                    <h3>Your emotional intelligence snapshot</h3>
                    <p class="ohsc-overall">Overall <strong id="eqOverall">0</strong></p>
                    <p class="ohsc-band" id="eqBand"></p>
                    <div class="ohsc-bars" id="eqBars"></div>
                    <p class="ohsc-note">This is a short reflection, not a clinical assessment. Use it to notice patterns, then practise one area at a time.</p>
                    <button type="button" class="ohsc-go" id="eqAgain">Take it again</button>
                </div>
            </section>

            <section class="ohsc-section">
                <h2>How it works</h2>
                <p class="sub">Get insights about your emotional intelligence in three simple steps.</p>
                <div class="ohsc-steps">
                    <article>
                        <span class="n1">1</span>
                        <h3>Answer questions</h3>
                        <p>Complete <?php echo (int) $questionCount; ?> simple questions about how you think, feel and behave.</p>
                    </article>
                    <article>
                        <span class="n2">2</span>
                        <h3>Get your results</h3>
                        <p>We add your answers and show a score for each area.</p>
                    </article>
                    <article>
                        <span class="n3">3</span>
                        <h3>Discover insights</h3>
                        <p>See where you are steady, and where a small change would help.</p>
                    </article>
                </div>
            </section>

            <section class="ohsc-section">
                <h2>Benefits for your personal and professional life</h2>
                <p class="sub">A clearer read on your emotions can help you at home and at work.</p>
                <div class="ohsc-benefits">
                    <?php foreach ($benefits as $benefit) { ?>
                    <article>
                        <img src="<?php echo ohsc_h($base_url . '/tools/assets/' . $benefit[2]); ?>" alt="">
                        <h3><?php echo ohsc_h($benefit[0]); ?></h3>
                        <p><?php echo ohsc_h($benefit[1]); ?></p>
                    </article>
                    <?php } ?>
                </div>
            </section>

            <?php if ($courseResult && $courseResult->num_rows > 0) { ?>
            <section class="ohsc-section">
                <div class="ohsc-section-head">
                    <div>
                        <h2>Recommended courses to improve your EQ</h2>
                        <p class="sub">Build people skills with free courses from Oxford Home Study Centre.</p>
                    </div>
                    <a href="<?php echo ohsc_h($base_url . '/free-online-courses-with-certificates'); ?>">View all courses</a>
                </div>
                <div class="ohsc-courses">
                    <?php while ($course = $courseResult->fetch_assoc()) {
                        $courseUrl = $base_url . '/courses/' . $course['catslug'] . '/' . $course['slug'];
                        $courseName = preg_replace('/\s+free course$/i', '', $course['name']);
                        $photo = isset($coursePhotos[(int) $course['diplomaid']]) ? $coursePhotos[(int) $course['diplomaid']] : '';
                    ?>
                    <article class="ohsc-course">
                        <?php if ($photo !== '') { ?>
                        <img src="<?php echo ohsc_h($base_url . '/tools/assets/' . $photo); ?>" alt="">
                        <?php } ?>
                        <div class="body">
                            <h3><?php echo ohsc_h($courseName); ?></h3>
                            <a href="<?php echo ohsc_h($courseUrl); ?>">Free Course</a>
                        </div>
                    </article>
                    <?php } ?>
                </div>
            </section>
            <?php } ?>

            <section class="ohsc-cta">
                <div>
                    <h2>Ready to learn more about yourself?</h2>
                    <p>Take the test now and see a personal snapshot of how you think, feel and connect.</p>
                </div>
                <div class="ohsc-cta-side">
                    <a href="#eq-test">Start Free Test</a>
                    <span>Takes only <?php echo (int) $minutes; ?> minutes</span>
                </div>
            </section>
        </div>
    </main>
    <script>
        window.OHSC_EQ = <?php echo json_encode(array('questions' => $payload, 'areas' => $areas), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <script src="<?php echo ohsc_h($base_url); ?>/tools/assets/eq-test.js?v=2"></script>
    <?php include dirname(__FILE__) . '/../../includes/footer-new.php'; ?>
</html>
