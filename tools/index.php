<?php
require_once dirname(__FILE__) . '/includes/lock.php';
include dirname(__FILE__) . '/../admin/classes/connection.php';
require_once dirname(__FILE__) . '/../includes/functions.php';
require_once dirname(__FILE__) . '/includes/registry.php';
ohsc_tools_ensure($db);
$tools = ohsc_tools_active($db);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Tools | Oxford Home Study Centre</title>
    <meta name="description" content="Free tools from Oxford Home Study Centre.">
    <?php include dirname(__FILE__) . '/../includes/header-home-new.php'; ?>
    <link rel="stylesheet" href="<?php echo ohsc_h($base_url); ?>/tools/assets/tools.css?v=1">
    <main class="ohsc-tool">
        <div class="ohsc-wrap">
            <div class="ohsc-page-head">
                <span class="ohsc-kicker">FREE TOOLS</span>
                <h1>Tools</h1>
                <p>Short practice tools you can open without an account. New tools appear here when they are published.</p>
            </div>
            <?php if (!$tools) { ?>
                <div class="ohsc-empty">No tools are published right now.</div>
            <?php } else { ?>
            <div class="ohsc-tool-grid">
                <?php foreach ($tools as $tool) { ?>
                <article class="ohsc-tool-card">
                    <div class="ohsc-tool-icon" aria-hidden="true">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M6 9h.01M10 9h.01M14 9h.01M18 9h.01M8 13h8"/></svg>
                    </div>
                    <h2><?php echo ohsc_h($tool['title']); ?></h2>
                    <p><?php echo ohsc_h($tool['summary']); ?></p>
                    <a class="ohsc-open" href="<?php echo ohsc_h(rtrim($base_url, '/') . '/tools/' . $tool['slug'] . '/'); ?>">Open tool</a>
                </article>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </main>
    <?php include dirname(__FILE__) . '/../includes/footer-new.php'; ?>
</html>
