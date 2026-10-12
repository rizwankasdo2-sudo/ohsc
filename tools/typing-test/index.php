<?php
include dirname(__FILE__) . '/../../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../../includes/functions.php';
require_once dirname(__FILE__) . '/../includes/registry.php';
$passages = ohsc_typing_passages($db);
$courseResult = $db->query("SELECT td.name, td.slug, td.square_icon, td.square_icon_alt, tc.slug AS catslug
    FROM tbl_diploma AS td
    INNER JOIN tbl_courses AS tc ON tc.cid = td.cid
    WHERE td.IStatus = 1 AND tc.IStatus = 1 AND td.type = 2
    ORDER BY td.ordernumber ASC
    LIMIT 4");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Typing Speed Test | Oxford Home Study Centre</title>
    <meta name="description" content="Take a free one, three, or five minute typing test. Check your words per minute and accuracy.">
    <?php include dirname(__FILE__) . '/../../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($base_url); ?>/tools/assets/typing-test.css?v=17">
    <main class="ohsc-tool" id="ohsc-typing">
        <div class="ohsc-wrap">
            <section class="ohsc-hero">
                <div>
                    <span class="ohsc-badge">FREE TYPING TEST</span>
                    <h1>Type faster.<br>Know your <span>speed.</span></h1>
                    <p class="lede">Check your speed and accuracy in minutes. No account needed.</p>
                </div>
                <div class="ohsc-art">
                    <img src="<?php echo ohsc_h($base_url); ?>/tools/assets/hero-typing.jpg" alt="A student smiling while practising on a laptop">
                </div>
            </section>

            <section class="ohsc-panel" id="start-test">
                <h2>Start your typing test</h2>
                <p class="sub">Type in the box. Correct letters turn purple. A wrong letter turns red and counts as an error. Symbols and numbers stay out until you turn them on.</p>
                <div class="ohsc-times">
                    <button type="button" class="is-on" data-seconds="60">1 Minute</button>
                    <button type="button" data-seconds="180">3 Minutes</button>
                    <button type="button" data-seconds="300">5 Minutes</button>
                    <button type="button" class="ohsc-mode" id="modeSymbols" aria-pressed="false">@! Symbols</button>
                    <button type="button" class="ohsc-mode" id="modeNumbers" aria-pressed="false">123 Numbers</button>
                </div>
                <div class="ohsc-passage" id="passage">
                    <input id="capture" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" aria-label="Typing input">
                </div>
                <div class="ohsc-meters">
                    <div class="ohsc-meter"><span>Time Left</span><strong class="time" id="statTime">1:00</strong></div>
                    <div class="ohsc-meter"><span>Words Typed</span><strong id="statWords">–</strong></div>
                    <div class="ohsc-meter"><span>WPM</span><strong id="statWpm">–</strong></div>
                    <div class="ohsc-meter"><span>Accuracy</span><strong id="statAccuracy">–</strong></div>
                    <div class="ohsc-meter"><span>Errors</span><strong class="bad" id="statErrors">–</strong></div>
                </div>
                <div class="ohsc-actions">
                    <button type="button" id="restartTest">Restart</button>
                    <button type="button" id="newText">New text</button>
                </div>
                <button type="button" class="ohsc-start" id="startTest">Start Free Test</button>
                <p class="ohsc-tip">Tip: Accuracy matters more than speed. WPM is correct characters divided by five, then divided by minutes.</p>
                <div class="ohsc-score" id="scoreBox"></div>
            </section>

            <section class="ohsc-section" id="ohsc-results">
                <h2>Your results will show</h2>
                <p class="sub">See your typing performance after you complete the test.</p>
                <div class="ohsc-results">
                    <article class="ohsc-result">
                        <div class="ohsc-result-ico speed" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><path d="M5 16a7 7 0 1 1 14 0"/><path d="M12 16l4-4.5"/><circle cx="12" cy="16" r="1.15" fill="currentColor" stroke="none"/></svg>
                        </div>
                        <span>Typing Speed (WPM)</span>
                        <strong id="resultWpm">–</strong>
                    </article>
                    <article class="ohsc-result">
                        <div class="ohsc-result-ico accuracy" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="7.2"/><circle cx="12" cy="12" r="3.4"/><circle cx="12" cy="12" r="1.15" fill="currentColor" stroke="none"/></svg>
                        </div>
                        <span>Accuracy</span>
                        <strong id="resultAccuracy">–</strong>
                    </article>
                    <article class="ohsc-result">
                        <div class="ohsc-result-ico errors" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><circle cx="12" cy="12" r="7.2"/><path d="M9.2 9.2l5.6 5.6M14.8 9.2l-5.6 5.6"/></svg>
                        </div>
                        <span>Errors</span>
                        <strong id="resultErrors">–</strong>
                    </article>
                    <article class="ohsc-result">
                        <div class="ohsc-result-ico time" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="7.2"/><path d="M12 8.2V12l2.8 1.8"/></svg>
                        </div>
                        <span>Time Taken</span>
                        <strong id="resultTime">–</strong>
                    </article>
                </div>
            </section>

            <section class="ohsc-section">
                <h2>Improve your typing</h2>
                <p class="sub">Small habits make a large difference.</p>
                <div class="ohsc-cards">
                    <article class="ohsc-card practice">
                        <div class="ohsc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M7 10h.01M11 10h.01M15 10h.01M7 14h10"/></svg></div>
                        <h3>Practice regularly</h3>
                        <p>Fifteen calm minutes a day builds memory faster than one long session.</p>
                    </article>
                    <article class="ohsc-card accuracy">
                        <div class="ohsc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1.4" fill="currentColor"/></svg></div>
                        <h3>Focus on accuracy</h3>
                        <p>Slow down until mistakes are rare. Speed follows a clean rhythm.</p>
                    </article>
                    <article class="ohsc-card fingers">
                        <div class="ohsc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M9.2 11.2V5.6a1.3 1.3 0 0 1 2.6 0v5.1h.7V3.8a1.3 1.3 0 0 1 2.6 0v6.9h.7V6.4a1.3 1.3 0 0 1 2.6 0v7.2c0 3.4-2.2 6.2-5.6 6.4H11c-2.6 0-4.6-1.6-5.2-3.8l-.8-2.8a1.35 1.35 0 0 1 2.5-1.1l1.7 1.9V11.2z"/></svg></div>
                        <h3>Use all fingers</h3>
                        <p>Keep your hands on the home row and let each finger take its own keys.</p>
                    </article>
                    <article class="ohsc-card longer">
                        <div class="ohsc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><rect x="3" y="13" width="4" height="7" rx="1"/><rect x="10" y="8" width="4" height="12" rx="1"/><rect x="17" y="4" width="4" height="16" rx="1"/></svg></div>
                        <h3>Try longer texts</h3>
                        <p>Move from single words to full sentences once the home row feels natural.</p>
                    </article>
                </div>
            </section>

            <section class="ohsc-section">
                <h2>Where typing skills help</h2>
                <p class="sub">Good typing skills can support many career paths.</p>
                <div class="ohsc-careers">
                    <?php
                    $careerCourses = array(
                        array('Office & Administration', 'secretarial-courses', 'administration-assistant-certification', 'career-office.jpg', 'A person working in an office'),
                        array('Customer Service', 'customer-service-courses-online', 'learn-skills-about-customer-service', 'career-service.jpg', 'A customer service adviser'),
                        array('Healthcare', 'online-health-and-safety-courses', 'patient-care-assistant-certification-online', 'career-health-2.jpg', 'A healthcare professional'),
                        array('Data Entry & Operations', 'job-skills-courses', 'data-entry-certification', 'career-data.jpg', 'A person typing at a desk'),
                    );
                    foreach ($careerCourses as $career) {
                        $careerUrl = $base_url . '/courses/' . $career[1] . '/' . $career[2];
                        $careerImg = $base_url . '/tools/assets/' . $career[3];
                    ?>
                    <a class="ohsc-role" href="<?php echo ohsc_h($careerUrl); ?>">
                        <img src="<?php echo ohsc_h($careerImg); ?>" alt="<?php echo ohsc_h($career[4]); ?>">
                        <span><?php echo ohsc_h($career[0]); ?> <svg viewBox="0 0 20 20" width="16" height="16" aria-hidden="true"><path d="M7 4l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    </a>
                    <?php } ?>
                </div>
            </section>

            <?php if ($courseResult && $courseResult->num_rows > 0) { ?>
            <section class="ohsc-section">
                <div class="ohsc-section-head">
                    <div>
                        <h2>Recommended courses</h2>
                        <p class="sub">Free courses from Oxford Home Study Centre.</p>
                    </div>
                    <a href="<?php echo ohsc_h($base_url . '/free-online-courses-with-certificates'); ?>">View all courses</a>
                </div>
                <div class="ohsc-courses">
                    <?php while ($course = $courseResult->fetch_assoc()) {
                        $courseUrl = $base_url . '/courses/' . $course['catslug'] . '/' . $course['slug'];
                        $initial = strtoupper(substr(trim($course['name']), 0, 1));
                    ?>
                    <article class="ohsc-course">
                        <?php if (!empty($course['square_icon'])) { ?>
                        <img src="<?php echo ohsc_h($base_url . '/images/subject/' . $course['square_icon']); ?>" alt="<?php echo ohsc_h($course['square_icon_alt']); ?>">
                        <?php } else { ?>
                        <div class="ph"><?php echo ohsc_h($initial); ?></div>
                        <?php } ?>
                        <div class="body">
                            <span class="ohsc-free">Free</span>
                            <h3><?php echo ohsc_h($course['name']); ?></h3>
                            <a href="<?php echo ohsc_h($courseUrl); ?>">View course</a>
                        </div>
                    </article>
                    <?php } ?>
                </div>
            </section>
            <?php } ?>

            <section class="ohsc-cta">
                <div>
                    <h2>Ready to test your typing skills?</h2>
                    <p>It only takes a minute. Pick a duration and start when you are ready.</p>
                </div>
                <a href="#start-test">Take the test</a>
            </section>
        </div>
    </main>
    <script>
        window.OHSC_TYPING = <?php echo json_encode(array('passages' => $passages), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <script src="<?php echo ohsc_h($base_url); ?>/tools/assets/typing-test.js?v=4"></script>
    <?php include dirname(__FILE__) . '/../../includes/footer-new.php'; ?>
</html>
