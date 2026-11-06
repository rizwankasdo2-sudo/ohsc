<?php
if (!isset($detailResource)) { http_response_code(404);exit; }
$files=ohsc_resource_files($db,(int)$detailResource['resource_id']);$preview=!empty($detailResource['image_hidden'])?'':($detailResource['image']?$site.'/tools/uploads/'.basename($detailResource['image']):$defaultCardImage);
?>
<section class="resource-panel resource-detail" id="resource-list">
<h2><?php echo ohsc_h($detailResource['title']); ?></h2><p class="resource-sub"><?php echo ohsc_h($detailResource['summary']); ?></p>
<?php if ($preview) { ?><img class="resource-detail-preview" src="<?php echo ohsc_h($preview); ?>" alt="<?php echo ohsc_h($detailResource['title']); ?> preview" width="640" height="400"><?php } ?>
<div class="resource-detail-downloads"><?php foreach ($files as $file) { ?><a class="resource-btn" href="<?php echo ohsc_h($site.'/tools/resources/download.php?id='.$file['file_id']); ?>">Download <?php echo ohsc_h($formats[$file['extension']]??strtoupper($file['extension'])); ?> ↓</a><?php } ?></div>
<h3>How to use this resource</h3><ol><li>Choose PDF to print or Word to edit.</li><li>Replace the prompts with details relevant to your career.</li><li>Review your completed document and use it to plan your next step.</li></ol>
<?php if (!empty($detailResource['answer'])) { ?><p><?php echo nl2br(ohsc_h($detailResource['answer'])); ?></p><?php } ?>
<a href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,'hub')); ?>">Browse more career resources →</a></section>
