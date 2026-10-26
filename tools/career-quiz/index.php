<?php
require_once dirname(__FILE__) . '/../includes/lock.php';
include dirname(__FILE__) . '/../../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../../includes/functions.php';
require_once dirname(__FILE__) . '/../includes/registry.php';
ohsc_tools_ensure($db);
$asset = rtrim($base_url, '/') . '/tools/assets/';
$seed = require dirname(__FILE__) . '/../includes/career-quiz-seed.php';
$paths = $seed['paths'];
$questions = $seed['questions'];
$questionCount = count($questions);
$pathCards = array(
    array('healthcare', 'Healthcare'),
    array('business', 'Business & Administration'),
    array('technology', 'Technology'),
    array('education', 'Education'),
);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Career Quiz | Oxford Home Study Centre</title>
    <meta name="description" content="Take a free career quiz. Answer 10 short questions and explore careers that could suit you.">
    <?php include dirname(__FILE__) . '/../../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($asset); ?>career-quiz.css?v=5">
    <main class="ohsc-cq" id="ohsc-cq">
        <div class="cq-wrap">
            <section class="cq-hero">
                <div class="cq-hero-copy">
                    <span class="cq-badge">FREE CAREER QUIZ</span>
                    <h1>Which career could <span>suit you?</span></h1>
                    <p>Answer a few short questions and explore paths that match your interests, strengths and work style.</p>
                    <a class="cq-go" href="#cq-quiz">Start Free Quiz <span aria-hidden="true">→</span></a>
                    <p class="cq-meta"><?php echo (int) $questionCount; ?> questions · A few minutes</p>
                </div>
                <div class="cq-hero-art" aria-hidden="true">
                    <img src="<?php echo ohsc_h($asset); ?>eq-course-people.jpg" alt="">
                </div>
            </section>

            <section class="cq-section">
                <h2>Discover a path worth exploring</h2>
                <p class="cq-sub">See what the quiz helps you notice about yourself.</p>
                <div class="cq-discover">
                    <div class="cq-disc-card cq-tone-pink">
                        <div class="cq-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1.2" fill="currentColor" stroke="none"/></svg>
                        </div>
                        <h3>Your Interests</h3>
                        <p>Explore what subjects and activities you enjoy.</p>
                    </div>
                    <div class="cq-disc-card cq-tone-gold">
                        <div class="cq-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3.5l2.2 5.1 5.5.5-4.2 3.7 1.3 5.3L12 15.7 7.2 18.1l1.3-5.3L4.3 9.1l5.5-.5L12 3.5z"/></svg>
                        </div>
                        <h3>Your Strengths</h3>
                        <p>See how your natural strengths can guide you.</p>
                    </div>
                    <div class="cq-disc-card cq-tone-purple">
                        <div class="cq-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="9" r="2.4"/><circle cx="16" cy="10" r="2.1"/><path d="M4.8 18c.6-2.4 2.2-3.6 4.2-3.6s3.6 1.2 4.2 3.6M13.2 18c.4-1.8 1.6-2.8 3.2-2.8 1.4 0 2.5.8 3 2.4"/></svg>
                        </div>
                        <h3>Your Work Style</h3>
                        <p>Discover the environments where you thrive.</p>
                    </div>
                    <div class="cq-disc-card cq-tone-green">
                        <div class="cq-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 18h6M10 21h4M12 3a6 6 0 0 1 3.6 10.8c-.7.5-1.1 1.2-1.2 2H9.6c-.1-.8-.5-1.5-1.2-2A6 6 0 0 1 12 3z"/></svg>
                        </div>
                        <h3>Career Ideas</h3>
                        <p>Get personalised suggestions to explore further.</p>
                    </div>
                </div>
            </section>

            <section class="cq-section" id="cq-quiz">
                <p class="cq-kicker">QUIZ PREVIEW</p>
                <h2>A glimpse of the quiz</h2>
                <p class="cq-sub">Answer simple questions about what energises you at work.</p>
                <div class="cq-quiz" id="cqQuiz">
                    <p class="cq-progress" id="cqProgress">Question 1 of <?php echo (int) $questionCount; ?></p>
                    <h3 id="cqPrompt">Loading the first question...</h3>
                    <div class="cq-choices" id="cqChoices"></div>
                    <p class="cq-need" id="cqNeed" hidden>Choose an answer to continue.</p>
                    <button type="button" class="cq-go wide" id="cqNext">Start Free Quiz <span aria-hidden="true">→</span></button>
                </div>
                <div class="cq-result" id="cqResult" hidden>
                    <p class="cq-kicker">Your result</p>
                    <h3>Careers that could suit you</h3>
                    <p class="cq-result-lead" id="cqResultLead"></p>
                    <div class="cq-result-grid" id="cqResultGrid"></div>
                    <button type="button" class="cq-outline" id="cqAgain">Take the quiz again</button>
                </div>
            </section>

            <section class="cq-section">
                <h2>How it works</h2>
                <p class="cq-sub">Find career ideas in three simple steps.</p>
                <div class="cq-how">
                    <article>
                        <span>1</span>
                        <h3>Answer Questions</h3>
                        <p>Complete <?php echo (int) $questionCount; ?> simple questions about your interests and work style.</p>
                    </article>
                    <article>
                        <span>2</span>
                        <h3>Get Your Results</h3>
                        <p>We match your answers to career paths that fit you best.</p>
                    </article>
                    <article>
                        <span>3</span>
                        <h3>Explore Careers</h3>
                        <p>Open course ideas and guides linked to your top matches.</p>
                    </article>
                </div>
            </section>

            <section class="cq-section">
                <h2>Explore career paths</h2>
                <p class="cq-sub">Browse popular areas while you decide.</p>
                <div class="cq-paths">
                    <?php foreach ($pathCards as $card) {
                        $key = $card[0];
                        $label = $card[1];
                        $img = isset($paths[$key]['image']) ? $paths[$key]['image'] : 'career-office.jpg';
                        ?>
                    <a class="cq-path" href="<?php echo ohsc_h(rtrim($base_url, '/') . '/courses'); ?>">
                        <img src="<?php echo ohsc_h($asset . $img); ?>" alt="">
                        <span><?php echo ohsc_h($label); ?> <i aria-hidden="true">→</i></span>
                    </a>
                    <?php } ?>
                </div>
            </section>

            <section class="cq-guides">
                <article>
                    <img src="<?php echo ohsc_h($asset); ?>career-office.jpg" alt="">
                    <div>
                        <h3>How to Choose a Career Path</h3>
                        <p>Simple steps to compare options and pick a direction that fits you.</p>
                        <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/OHSC-Blog'); ?>">Read guide →</a>
                    </div>
                </article>
                <article>
                    <img src="<?php echo ohsc_h($asset); ?>career-service.jpg" alt="">
                    <div>
                        <h3>How to Develop In-Demand Skills</h3>
                        <p>Build skills that employers look for with short, focused practice.</p>
                        <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/courses'); ?>">Explore courses →</a>
                    </div>
                </article>
            </section>

            <section class="cq-cta">
                <div>
                    <h2>Ready to explore your <span>future?</span></h2>
                    <p>Take the free quiz and see which careers could suit you.</p>
                </div>
                <a class="cq-btn-gold" href="#cq-quiz">Start Free Quiz <span aria-hidden="true">→</span></a>
            </section>
        </div>
    </main>
    <script>
        window.OHSC_CQ = <?php echo json_encode(array(
            'questions' => $questions,
            'paths' => $paths,
            'asset' => $asset,
            'courses' => rtrim($base_url, '/') . '/courses',
        ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    </script>
    <script src="<?php echo ohsc_h($asset); ?>career-quiz.js?v=2"></script>
    <?php include dirname(__FILE__) . '/../../includes/footer-new.php'; ?>
</html>
