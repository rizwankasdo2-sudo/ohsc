<?php
require_once dirname(__FILE__) . '/includes/lock.php';
include dirname(__FILE__) . '/../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../includes/functions.php';
require_once dirname(__FILE__) . '/includes/registry.php';
ohsc_tools_ensure($db);
$tools = ohsc_tools_active($db);
$asset = rtrim($base_url, '/') . '/tools/assets/';
$toolBase = rtrim($base_url, '/') . '/tools/';

$cardMeta = array(
    'career-quiz' => array(
        'headline' => 'Find your path',
        'summary' => 'Explore careers that fit you.',
        'cta' => 'Explore Careers →',
        'tone' => 'grey',
    ),
    'cv-builder' => array(
        'headline' => 'Make a CV that stands out',
        'summary' => 'Create, edit and download your CV.',
        'cta' => 'Create CV →',
        'tone' => 'pink',
    ),
    'interview-prep' => array(
        'headline' => 'Feel ready to interview',
        'summary' => 'Practise questions and answers.',
        'cta' => 'Practise Interviews →',
        'tone' => 'orange',
    ),
    'typing-test' => array(
        'headline' => 'Type with confidence',
        'summary' => 'Check your speed and accuracy.',
        'cta' => 'Test Typing →',
        'tone' => 'green',
    ),
    'eq-test' => array(
        'headline' => 'Know your strengths',
        'summary' => 'Explore how you work with others.',
        'cta' => 'Explore Strengths →',
        'tone' => 'navy',
    ),
);

$orderedSlugs = array('career-quiz', 'cv-builder', 'interview-prep', 'typing-test', 'eq-test');
$bySlug = array();
foreach ($tools as $tool) {
    $bySlug[$tool['slug']] = $tool;
}
$cards = array();
foreach ($orderedSlugs as $slug) {
    if (isset($bySlug[$slug])) {
        $cards[] = $bySlug[$slug];
        unset($bySlug[$slug]);
    }
}
foreach ($bySlug as $tool) {
    $cards[] = $tool;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Career Tools | Oxford Home Study Centre</title>
    <meta name="description" content="Free career tools from Oxford Home Study Centre: career quiz, CV builder, interview prep, typing test, and strengths test.">
    <?php include dirname(__FILE__) . '/../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($asset); ?>tools.css?v=2">
    <main class="ohsc-tool">
        <div class="ohsc-wrap">
            <section class="ohsc-hub-hero">
                <div>
                    <h1>Your next step. <span>Free.</span></h1>
                    <ul class="ohsc-hub-list">
                        <li>Free CV maker</li>
                        <li>Free interview prep</li>
                        <li>Free career guides</li>
                        <li>Free career quiz</li>
                    </ul>
                    <a class="ohsc-open" href="#tools-list">Explore now →</a>
                </div>
                <div class="ohsc-hub-art" aria-hidden="true">
                    <img src="<?php echo ohsc_h($asset); ?>hero-typing.jpg" alt="">
                </div>
            </section>

            <?php if (!$cards) { ?>
                <div class="ohsc-empty">No tools are published right now.</div>
            <?php } else { ?>
            <div class="ohsc-hub-stack" id="tools-list">
                <?php foreach ($cards as $tool) {
                    $meta = isset($cardMeta[$tool['slug']]) ? $cardMeta[$tool['slug']] : array(
                        'headline' => $tool['title'],
                        'summary' => $tool['summary'],
                        'cta' => 'Open tool →',
                        'tone' => 'grey',
                    );
                    ?>
                <article class="ohsc-hub-card tone-<?php echo ohsc_h($meta['tone']); ?>">
                    <div>
                        <p class="ohsc-free">FREE</p>
                        <h2><?php echo ohsc_h($meta['headline']); ?></h2>
                        <p><?php echo ohsc_h($meta['summary']); ?></p>
                    </div>
                    <a class="ohsc-open" href="<?php echo ohsc_h($toolBase . $tool['slug'] . '/'); ?>"><?php echo ohsc_h($meta['cta']); ?></a>
                </article>
                <?php } ?>
            </div>
            <?php } ?>

            <section class="ohsc-hub-more">
                <h2>More free career resources</h2>
                <div class="ohsc-hub-links">
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/tools/cv-builder/'); ?>">CV Templates</a>
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/tools/interview-prep/'); ?>">Interview Checklists</a>
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/tools/career-quiz/'); ?>">Career Quiz</a>
                    <a href="<?php echo ohsc_h(rtrim($base_url, '/') . '/OHSC-Blog'); ?>">Career Advice</a>
                </div>
            </section>
        </div>
    </main>
    <?php include dirname(__FILE__) . '/../includes/footer-new.php'; ?>
</html>
