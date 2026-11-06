<?php
require_once __DIR__.'/registry.php';
if (!isset($db) || !($db instanceof mysqli) || !isset($base_url)) { return; }
$ohscToolsHome=ohsc_tool_url($db,$base_url,'tools-hub');
?>
<?php if (isset($ohsc_tools_nav_style) && $ohsc_tools_nav_style==='legacy') { ?>
<a class="vamiddle mrl40 menutxt" href="<?php echo ohsc_h($ohscToolsHome); ?>">Tools</a>
<?php } else { ?><li class="nav-item"><a class="nav-link" href="<?php echo ohsc_h($ohscToolsHome); ?>">Tools</a></li><?php } ?>
