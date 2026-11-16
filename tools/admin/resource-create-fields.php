<?php
if (!function_exists('ohsc_tools_csrf_ok') || empty($_SESSION['adminid'])) { http_response_code(403);exit; }
$createValue=function($key,$fallback='') {return isset($_POST[$key])&&is_string($_POST[$key])?$_POST[$key]:$fallback;};
$owners=array_values(array_filter(ohsc_career_url_register(),function($r){return $r['type']==='Downloadable resource'||(strpos($r['path'],'/career-expert/cv-and-cover-letters/')===0&&preg_match('/template|example/i',$r['title']));}));
?>
<fieldset style="margin:24px 0;padding:18px;border:1px solid #ccc"><legend>Resource page &amp; SEO</legend>
<p>Create the page and upload its downloads together. It uses the reference preview/download layout. Draft resources stay private until published.</p>
<p><label>Preferred workbook URL <select name="preferred_owner" id="preferred-owner"><option value="">Custom resource</option><?php foreach ($owners as $owner) { ?><option value="<?php echo ohsc_h($owner['id']); ?>" <?php echo $createValue('preferred_owner')===$owner['id']?'selected':''; ?>><?php echo ohsc_h($owner['id'].' — '.$owner['title'].' — '.$owner['path']); ?></option><?php } ?></select></label></p>
<p><label>URL section <select name="url_section" id="resource-url-section"><?php foreach (ohsc_resource_url_sections() as $v=>$label) { ?><option value="<?php echo $v; ?>" <?php echo $createValue('url_section','resources')===$v?'selected':''; ?>><?php echo $label; ?></option><?php } ?></select></label></p>
<p><label>Custom slug <input name="slug" id="resource-page-slug" pattern="[a-z0-9]+(-[a-z0-9]+)*" maxlength="80" required value="<?php echo ohsc_h($createValue('slug')); ?>"></label></p>
<p>URL: /career-expert/<span id="resource-prefix">resources</span>/<span id="resource-slug-preview">your-slug</span>. Browsing categories do not add extra URL levels.</p>
<p><label>Meta title <input name="meta_title" maxlength="200" value="<?php echo ohsc_h($createValue('meta_title')); ?>"></label></p>
<p><label>Meta description <textarea name="meta_description" maxlength="500" style="min-height:80px"><?php echo ohsc_h($createValue('meta_description')); ?></textarea></label></p>
<p><label>Meta keywords <input name="meta_keywords" id="resource-meta-keywords" maxlength="500" value="<?php echo ohsc_h($createValue('meta_keywords')); ?>"></label></p>
<p><label>Meta robots <select name="meta_robots"><?php foreach (ohsc_resource_robots_options() as $v) { ?><option <?php echo $createValue('meta_robots','noindex, nofollow')===$v?'selected':''; ?>><?php echo ohsc_h($v); ?></option><?php } ?></select></label></p></fieldset>
<script>
(function(){var owners=<?php echo json_encode($owners,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>,select=document.getElementById('preferred-owner'),slug=document.getElementById('resource-page-slug'),section=document.getElementById('resource-url-section');function paint(){document.getElementById('resource-prefix').textContent=section.value;document.getElementById('resource-slug-preview').textContent=slug.value||'your-slug';}select.addEventListener('change',function(){var row=owners.find(function(r){return r.id===select.value;});if(row){var parts=row.path.split('/');section.value=parts.slice(2,-1).join('/');slug.value=parts[parts.length-1];document.getElementById('resource-meta-keywords').value=row.keywords;}paint();});slug.addEventListener('input',paint);section.addEventListener('change',paint);paint();})();
</script>
