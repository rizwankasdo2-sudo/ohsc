<?php
$inventory = ohsc_image_inventory($db, $tool['slug']);
$overrides = $tool['tool_id'] ? ohsc_image_overrides($db, $tool['slug']) : array();
if ($tool['tool_id'] && $inventory) { ?>
<section style="clear:both;padding-top:20px">
<h3>Tool images</h3>
<p>Replace the images used on this tool. WebP only, maximum 5 MB. Exact dimensions are required; images are not cropped or resized. Repeated uses of the same image on this tool update together. Original images remain available as defaults.</p>
<?php if ($imageMessage !== '') { ?><p role="status" style="color:#17652c;font-weight:bold"><?php echo ohsc_h($imageMessage); ?></p><?php } ?>
<?php foreach ($inventory as $source => $spec) { $url = rtrim($base_url, '/') . (isset($overrides[$source]) ? $overrides[$source] : $source); ?>
<form method="post" enctype="multipart/form-data" style="border:1px solid #ddd;padding:16px;margin:12px 0;background:#fafafa">
<input type="hidden" name="csrf" value="<?php echo ohsc_h($_SESSION['ohsc_tools_csrf']); ?>">
<input type="hidden" name="image_action" value="upload">
<input type="hidden" name="image_source" value="<?php echo ohsc_h($source); ?>">
<h4><?php echo ohsc_h($spec['label']); ?></h4>
<p><img src="<?php echo ohsc_h($url); ?>" alt="Current <?php echo ohsc_h($spec['label']); ?>" style="max-width:240px;max-height:150px;object-fit:contain;background:#eee"></p>
<p>Required: <strong><?php echo $spec['width']; ?> × <?php echo $spec['height']; ?> px</strong> · WebP only · Maximum 5 MB</p>
<p><label>Replacement image <input type="file" name="tool_image" accept="image/webp,.webp" required></label></p>
<button type="submit" class="save-btn">Upload / Replace image</button>
</form>
<?php } ?></section>
<?php } ?>
