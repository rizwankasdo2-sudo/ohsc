<?php
require_once dirname(__FILE__) . '/../includes/lock.php';
include dirname(__FILE__) . '/../../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../../includes/functions.php';
require_once dirname(__FILE__) . '/../includes/registry.php';
ohsc_tools_ensure($db);
$asset = rtrim($base_url, '/') . '/tools/assets/';
$industries = require dirname(__FILE__) . '/../includes/interview-seed.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Interview Prep | Oxford Home Study Centre</title>
    <meta name="description" content="Practise free interview questions by industry. Read sample answers and feel ready for your next interview.">
    <?php include dirname(__FILE__) . '/../../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($asset); ?>interview-prep.css?v=1">
    <main class="ohsc-iv" id="ohsc-iv">
        <div class="iv-wrap">
            <section class="iv-hero">
                <div class="iv-hero-copy">
                    <span class="iv-badge">FREE INTERVIEW PREP</span>
                    <h1>Feel ready to <span>interview.</span></h1>
                    <p>Practise common questions, review sample answers, and build confidence before the real conversation.</p>
                    <a class="iv-go" href="#iv-practice">Practise Interviews <span aria-hidden="true">→</span></a>
                    <p class="iv-meta"><?php echo count($industries); ?> industries · Short practice sets</p>
                </div>
                <div class="iv-hero-art" aria-hidden="true">
                    <img src="<?php echo ohsc_h($asset); ?>eq-course-talk.jpg" alt="">
                </div>
            </section>

            <section class="iv-section" id="iv-practice">
                <h2>Interview questions by industry</h2>
                <p class="iv-sub">Pick an area, then practise one question at a time.</p>
                <div class="iv-industries" id="ivIndustries">
                    <?php foreach ($industries as $i => $industry) { ?>
                    <button type="button" class="iv-industry<?php echo $i === 0 ? ' is-on' : ''; ?>" data-industry="<?php echo ohsc_h($industry['id']); ?>">
                        <img src="<?php echo ohsc_h($asset . $industry['image']); ?>" alt="">
                        <strong><?php echo ohsc_h($industry['title']); ?></strong>
                        <span><?php echo ohsc_h($industry['summary']); ?></span>
                    </button>
                    <?php } ?>
                </div>

                <div class="iv-board" id="ivBoard">
                    <div class="iv-board-head">
                        <p class="iv-progress" id="ivProgress">Question 1</p>
                        <p class="iv-industry-label" id="ivIndustryLabel"></p>
                    </div>
                    <h3 id="ivQuestion">Loading…</h3>
                    <button type="button" class="iv-outline" id="ivToggle">Show sample answer</button>
                    <div class="iv-answer" id="ivAnswer" hidden></div>
                    <div class="iv-actions">
                        <button type="button" class="iv-outline" id="ivPrev" hidden>Previous</button>
                        <button type="button" class="iv-go" id="ivNext">Next question</button>
                    </div>
                </div>
            </section>

            <section class="iv-section">
                <h2>How it works</h2>
                <p class="iv-sub">Build interview confidence in three steps.</p>
                <div class="iv-how">
                    <article>
                        <span>1</span>
                        <h3>Choose an industry</h3>
                        <p>Open the set that matches the role you want.</p>
                    </article>
                    <article>
                        <span>2</span>
                        <h3>Practise out loud</h3>
                        <p>Answer each question, then compare with the sample tip.</p>
                    </article>
                    <article>
                        <span>3</span>
                        <h3>Keep improving</h3>
                        <p>Repeat until your answers feel clear and calm.</p>
                    </article>
                </div>
            </section>

            <section class="iv-guides">
                <article>
                    <img src="<?php echo ohsc_h($asset); ?>career-office.jpg" alt="">
                    <div>
                        <h3>Interview checklists</h3>
                        <p>What to prepare the day before and what to bring on the day.</p>
                        <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/OHSC-Blog'); ?>">Read tips →</a>
                    </div>
                </article>
                <article>
                    <img src="<?php echo ohsc_h($asset); ?>career-service.jpg" alt="">
                    <div>
                        <h3>Build a stronger CV first</h3>
                        <p>Use the free CV builder so your application matches your interview story.</p>
                        <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/tools/cv-builder/'); ?>">Create CV →</a>
                    </div>
                </article>
            </section>

            <section class="iv-cta">
                <div>
                    <h2>Ready for your next interview?</h2>
                    <p>Practise a few questions today and walk in more prepared.</p>
                </div>
                <a class="iv-gold" href="#iv-practice">Practise now <span aria-hidden="true">→</span></a>
            </section>
        </div>
    </main>
    <script>
        window.OHSC_IV = <?php echo json_encode(array('industries' => $industries), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    </script>
    <script src="<?php echo ohsc_h($asset); ?>interview-prep.js?v=1"></script>
    <?php include dirname(__FILE__) . '/../../includes/footer-new.php'; ?>
</html>
