<?php
require_once dirname(__FILE__) . '/registry.php';

if (!isset($db) || !($db instanceof mysqli) || !isset($base_url)) {
    return;
}

$ohscTools = ohsc_tools_active($db);
$ohscToolsHome = rtrim($base_url, '/') . '/tools/';
$ohscToolsNavStyle = isset($ohsc_tools_nav_style) ? $ohsc_tools_nav_style : 'bootstrap';
?>
<?php if ($ohscToolsNavStyle === 'legacy') { ?>
<div class="dropdown">
    <div class="vamiddle mrl40 menutxt bmoption curp">Tools</div>
    <div class="cm-e-menu">
        <ul>
            <li><a href="<?php echo ohsc_h($ohscToolsHome); ?>">All tools</a></li>
            <?php foreach ($ohscTools as $ohscTool) { ?>
            <li><a href="<?php echo ohsc_h($ohscToolsHome . $ohscTool['slug'] . '/'); ?>"><?php echo ohsc_h($ohscTool['title']); ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php } else { ?>
<li class="nav-item">
    <div class="custom-dropdown dropdown" style="margin-top:0px;">
        <a class="dropdown-toggle nav-link" href="<?php echo ohsc_h($ohscToolsHome); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">Tools</a>
        <ul class="dropdown-menu">
            <li class="dropdown-item">
                <div class="head">
                    <a href="<?php echo ohsc_h($ohscToolsHome); ?>">All tools</a>
                </div>
            </li>
            <?php foreach ($ohscTools as $ohscTool) { ?>
            <li class="dropdown-item">
                <div class="head">
                    <a href="<?php echo ohsc_h($ohscToolsHome . $ohscTool['slug'] . '/'); ?>"><?php echo ohsc_h($ohscTool['title']); ?></a>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
</li>
<?php } ?>
