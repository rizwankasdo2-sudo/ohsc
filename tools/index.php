<?php
require_once dirname(__FILE__) . '/includes/lock.php';
include dirname(__FILE__) . '/../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../includes/functions.php';
require_once dirname(__FILE__) . '/includes/registry.php';
ohsc_tools_ensure($db);
$tools = ohsc_tools_active($db);
$industries = ohsc_iv_industries($db);
$siteBase = rtrim($base_url, '/');
$hubSections = require dirname(__FILE__) . '/includes/hub-content.php';
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
    <link rel="stylesheet" href="<?php echo ohsc_h($asset); ?>tools.css?v=10">
    <main class="ohsc-tool ohsc-career-hub">
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
                    <img src="<?php echo ohsc_h($asset); ?>career-hero.svg" alt="">
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
                    <span class="ohsc-card-icon" aria-hidden="true"><?php echo ohsc_hub_icon($tool['slug']); ?></span>
                    <div class="ohsc-card-copy">
                        <div class="ohsc-card-title"><h2><?php echo ohsc_h($meta['headline']); ?></h2><span class="ohsc-free">FREE</span></div>
                        <p><?php echo ohsc_h($meta['summary']); ?></p>
                    </div>
                    <a class="ohsc-open" href="<?php echo ohsc_h($toolBase . $tool['slug'] . '/'); ?>"><?php echo ohsc_h($meta['cta']); ?></a>
                </article>
                <?php } ?>
            </div>
            <?php } ?>

            <?php foreach ($hubSections as $section) { ?>
            <section class="ohsc-hub-section" aria-labelledby="<?php echo ohsc_h($section['id']); ?>">
                <div class="ohsc-section-heading">
                    <div><h2 id="<?php echo ohsc_h($section['id']); ?>"><?php echo ohsc_h($section['title']); ?></h2><p><?php echo ohsc_h($section['summary']); ?></p></div>
                    <a href="<?php echo ohsc_h($section['url']); ?>"><?php echo ohsc_h($section['view']); ?> <span aria-hidden="true">→</span></a>
                </div>
                <?php if ($section['id'] === 'career-resources') { ?>
                <div class="ohsc-hub-links">
                    <?php foreach ($section['items'] as $item) { ?>
                    <a href="<?php echo ohsc_h($item['url']); ?>"><span class="ohsc-resource-icon" aria-hidden="true"><?php echo ohsc_hub_icon($item['icon']); ?></span><span><?php echo ohsc_h($item['title']); ?></span><span aria-hidden="true">→</span></a>
                    <?php } ?>
                </div>
                <?php } else { ?>
                <div class="ohsc-carousel" data-hub-carousel>
                    <button class="ohsc-carousel-prev" type="button" aria-label="Previous <?php echo ohsc_h($section['title']); ?>" hidden>‹</button>
                    <div class="ohsc-carousel-track" tabindex="0" role="group" aria-label="<?php echo ohsc_h($section['title']); ?>">
                        <?php foreach ($section['items'] as $item) { ?>
                        <a class="ohsc-story-card" href="<?php echo ohsc_h($item['url']); ?>">
                            <img src="<?php echo ohsc_h($asset . $item['image']); ?>" alt="" loading="lazy" width="560" height="240">
                            <span><?php echo ohsc_h($item['title']); ?><span aria-hidden="true">→</span></span>
                        </a>
                        <?php } ?>
                    </div>
                    <button class="ohsc-carousel-next" type="button" aria-label="Next <?php echo ohsc_h($section['title']); ?>" hidden>›</button>
                    <div class="ohsc-carousel-dots" aria-hidden="true"></div>
                </div>
                <?php } ?>
            </section>
            <?php } ?>
        </div>
    </main>
    <script src="<?php echo ohsc_h($asset); ?>hub.js?v=1" defer></script>
    <?php include dirname(__FILE__) . '/../includes/footer-new.php'; ?>
</html>
