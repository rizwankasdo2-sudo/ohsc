<?php
require_once __DIR__.'/../includes/lock.php';
require __DIR__.'/../../admin/classes/connection.php';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/registry.php';
require_once __DIR__.'/../includes/resources.php';
ohsc_resources_ensure($db);
$pages=ohsc_resource_pages($db);
$site=rtrim($base_url,'/');$asset=$site.'/tools/assets/';
$q=isset($_GET['q'])&&is_string($_GET['q'])?substr(trim($_GET['q']),0,150):'';
$goal=isset($_GET['goal'])&&is_string($_GET['goal'])&&isset(ohsc_resource_goals()[$_GET['goal']])?$_GET['goal']:'';
$requestPath=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));
if (preg_match('~^/tools/resources(?:/index\.php)?/?$~',$requestPath)) {
    $legacyCategory=isset($_GET['category'])&&is_string($_GET['category'])&&isset(ohsc_resource_categories()[$_GET['category']])?$_GET['category']:'';
    $target=$site.ohsc_resource_page_path($pages,$legacyCategory?:'hub');$query=array();if ($q!=='') { $query['q']=$q; }if ($goal!=='') { $query['goal']=$goal; }
    header('Location: '.$target.($query?'?'.http_build_query($query):''),true,301);exit;
}
if (strpos($requestPath,'/career-expert/')===0) {
    $legacy=ohsc_resource_page_resolve($pages,substr($requestPath,14));
    if ($legacy) { $query=$_GET;header('Location: '.$site.ohsc_resource_page_path($pages,$legacy['page_key']).($query?'?'.http_build_query($query):''),true,301);exit; }
}
$page=ohsc_resource_page_resolve($pages,$requestPath);
if (!$page) {
    $aliasPath=rtrim($requestPath,'/');$stmt=$db->prepare('SELECT page_key FROM tbl_resource_page_aliases WHERE path=?');$stmt->bind_param('s',$aliasPath);$stmt->execute();$alias=$stmt->get_result()->fetch_assoc();$stmt->close();
    if (!$alias && strpos($aliasPath,'/career-expert/')===0) { $legacyAlias=substr($aliasPath,14);$stmt=$db->prepare('SELECT page_key FROM tbl_resource_page_aliases WHERE path=?');$stmt->bind_param('s',$legacyAlias);$stmt->execute();$alias=$stmt->get_result()->fetch_assoc();$stmt->close(); }
    if ($alias && isset($pages[$alias['page_key']])) { $query=array();if ($q!=='') { $query['q']=$q; }if ($goal!=='') { $query['goal']=$goal; }header('Location: '.$site.ohsc_resource_page_path($pages,$alias['page_key']).($query?'?'.http_build_query($query):''),true,301);exit; }
    http_response_code(404);header('Content-Type: text/html; charset=UTF-8');echo '<h1>Resource page not found</h1><p><a href="'.ohsc_h($site.ohsc_resource_page_path($pages,'hub')).'">Browse career resources</a></p>';exit;
}
$canonical=$site.ohsc_resource_page_path($pages,$page['page_key']);
if ($requestPath!==ohsc_resource_page_path($pages,$page['page_key'])) { header('Location: '.$canonical.($_SERVER['QUERY_STRING']?'?'.$_SERVER['QUERY_STRING']:''),true,301);exit; }
$isHub=$page['page_key']==='hub';
$category=$isHub?'':($page['parent_key']==='hub'?$page['page_key']:$page['parent_key']);
$subcategory=$isHub||$page['parent_key']==='hub'?'':$page['page_key'];
$resources=ohsc_resources_list($db,$q,$category,$goal,$subcategory);
$toolkits=ohsc_resources_list($db,'','toolkits','');
$goals=ohsc_resource_goals();$formats=ohsc_resource_formats();
$ohsc_resource_meta=true;
$staging=isset($_SERVER['HTTP_HOST'])&&preg_match('/^(?:(?:www\.)?tools\.kasdo\.org\.?|localhost|127\.0\.0\.1)(?::[0-9]+)?$/i',$_SERVER['HTTP_HOST']);
$robots=$staging?'noindex, nofollow':$page['meta_robots'];
$resourceImages=ohsc_resource_image_records($db,$page['page_key']);
$defaultResourceImage=$asset.'resources-hero.svg';$legacyResourceImages=ohsc_image_overrides($db,'resources');if (isset($legacyResourceImages['/tools/assets/resources-hero.svg'])) { $defaultResourceImage=$site.$legacyResourceImages['/tools/assets/resources-hero.svg']; }
$heroImage=ohsc_resource_image_url($resourceImages,'hero',$site,$defaultResourceImage);
$toolkitImage=ohsc_resource_image_url($resourceImages,'toolkit',$site,$defaultResourceImage);
$defaultCardImage=ohsc_resource_image_url($resourceImages,'card-default',$site,$defaultResourceImage);
?>
<!doctype html><html lang="en"><head><meta charset="UTF-8">
<meta name="robots" content="<?php echo ohsc_h($robots); ?>">
<title><?php echo ohsc_h($page['meta_title']); ?></title>
<meta name="description" content="<?php echo ohsc_h($page['meta_description']); ?>">
<meta name="keywords" content="<?php echo ohsc_h($page['meta_keywords']); ?>">
<link rel="canonical" href="<?php echo ohsc_h($canonical); ?>">
<?php include __DIR__.'/../../includes/header-home-new.php'; ?>
<link rel="stylesheet" href="<?php echo ohsc_h($asset); ?>resources.css?v=6">
<main class="career-resources" id="career-resources"><div class="resource-wrap">
<?php if (!$isHub) { ?><nav class="resource-breadcrumbs" aria-label="Breadcrumb"><a href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,'hub')); ?>">Resources</a> <span>›</span> <?php if ($subcategory) { ?><a href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,$category)); ?>"><?php echo ohsc_h($pages[$category]['title']); ?></a> <span>›</span><?php } ?><strong><?php echo ohsc_h($page['title']); ?></strong></nav><?php } ?>
<section class="resource-hero<?php echo $heroImage===''?' no-image':''; ?>"><div><h1><?php if ($isHub && $page['title']==='Free Career Resources') { ?>Practical tools.<br><span>Your next step.</span><?php } else { echo ohsc_h($page['title']); } ?></h1><p><?php echo ohsc_h($page['summary']); ?></p><strong class="resource-badge">FREE CAREER RESOURCES</strong><a class="resource-btn" href="#resource-search">Explore resources <span aria-hidden="true">→</span></a></div><?php if ($heroImage!=='') { ?><img src="<?php echo ohsc_h($heroImage); ?>" alt="CV, checklist and career plan documents ready to download" width="640" height="440"><?php } ?></section>
<section class="resource-panel resource-search" id="resource-search"><div class="resource-search-heading"><span aria-hidden="true"><?php echo ohsc_resource_icon('search'); ?></span><h2>Find your next resource</h2></div>
<form method="get" action="<?php echo ohsc_h($canonical); ?>#resource-list"><label class="resource-sr" for="resource-query">Search resources</label><div class="resource-search-row"><input id="resource-query" name="q" value="<?php echo ohsc_h($q); ?>" maxlength="150" placeholder="Search templates, checklists and more..."><button type="submit">Search</button></div>

<?php if ($goal) { ?><input type="hidden" name="goal" value="<?php echo ohsc_h($goal); ?>"><?php } ?>
</form><nav class="resource-categories" aria-label="Resource categories"><a href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,'hub')); ?>" class="<?php echo $isHub?'active':''; ?>" <?php echo $isHub?'aria-current="page"':''; ?>>All resources</a><?php foreach (ohsc_resource_categories() as $key=>$label) { ?><a href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,$key)); ?>" class="<?php echo $category===$key?'active':''; ?>" <?php echo $page['page_key']===$key?'aria-current="page"':''; ?>><span class="resource-category-icon <?php echo ohsc_h($key); ?>" aria-hidden="true"><?php echo ohsc_resource_icon($key); ?></span><?php echo ohsc_h($pages[$key]['title']); ?></a><?php } ?></nav></section>
<?php if ($isHub) { ?>
<section class="resource-section" id="featured-resources"><h2>Featured Resources</h2><p class="resource-sub">Practical resources for your next step.</p><div class="resource-featured">
<?php foreach (ohsc_resource_featured_items() as $feature) { $featuredImage=ohsc_resource_image_url($resourceImages,$feature[3],$site,$defaultResourceImage); ?><a class="<?php echo $featuredImage===''?'no-image':''; ?>" href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,$feature[1]).($feature[2]?'?q='.rawurlencode($feature[2]):'')); ?>"><?php if ($featuredImage!=='') { ?><img src="<?php echo ohsc_h($featuredImage); ?>" alt="" width="640" height="400"><?php } ?><strong><?php echo ohsc_h($feature[0]); ?></strong><span aria-hidden="true">→</span></a><?php } ?></div></section>
<?php } else { $children=array_filter($pages,function($p)use($page){return $p['parent_key']===$page['page_key'];});if ($children) { ?>
<section class="resource-section"><h2>Browse <?php echo ohsc_h($page['title']); ?></h2><div class="resource-goals"><?php foreach ($children as $child) { ?><a href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,$child['page_key'])); ?>"><div><h3><?php echo ohsc_h($child['title']); ?></h3><p><?php echo ohsc_h($child['summary']); ?></p></div><span aria-hidden="true">→</span></a><?php } ?></div></section>
<?php } } ?>
<?php if (!$isHub || $q!=='' || $goal!=='') { ?>
<section id="resource-list" class="resource-section"><h2><?php echo $category==='faqs'?'Questions & answers':($q||$category||$goal?'Your resources':'Popular resources'); ?></h2><p class="resource-sub"><?php echo $goal?'Resources to help you '.strtolower($goals[$goal]).'.':'Useful downloads. Ready when you are.'; ?></p>
<?php if ($q||$category||$goal) { ?><p class="resource-filter-note"><?php echo count($resources); ?> resource<?php echo count($resources)===1?'':'s'; ?> found<?php if ($q) { echo ' for “'.ohsc_h($q).'”'; } ?>. <a href="<?php echo ohsc_h($canonical); ?>#resource-list">Clear filters</a></p><?php } ?>
<div class="resource-list">
<?php if (!$resources) { ?><div class="resource-panel resource-empty"><h3><?php echo $q||$category||$goal?'No matching resources':'New resources are on their way'; ?></h3><p><?php echo $q||$category||$goal?'Try a different search or clear your filters.':'Templates, checklists and worksheets will appear here as they are published.'; ?></p></div><?php } ?>
<?php foreach ($resources as $resource) { if ($resource['category']==='faqs' && trim((string)$resource['answer'])!=='') { ?><details class="resource-question"><summary><?php echo ohsc_h($resource['title']); ?></summary><p><?php echo nl2br(ohsc_h($resource['answer'])); ?></p><?php foreach (ohsc_resource_files($db,(int)$resource['resource_id']) as $faqFile) { ?><a class="resource-btn" href="<?php echo ohsc_h($site.'/tools/resources/download.php?id='.$faqFile['file_id']); ?>">Download <?php echo ohsc_h($formats[$faqFile['extension']]); ?></a><?php } ?></details><?php continue; } $files=ohsc_resource_files($db,(int)$resource['resource_id']);$thumbnail=!empty($resource['image_hidden'])?'':($resource['image']&&preg_match('/^[a-f0-9]{32}\.webp$/',$resource['image'])?$site.'/tools/uploads/'.$resource['image']:$defaultCardImage); ?>
<article class="resource-card<?php echo $thumbnail===''?' no-image':''; ?>" id="resource-<?php echo (int)$resource['resource_id']; ?>"><?php if ($thumbnail!=='') { ?><img src="<?php echo ohsc_h($thumbnail); ?>" alt="" loading="lazy" width="640" height="400"><?php } ?><div class="resource-card-body"><span class="resource-category-label"><?php echo ohsc_h(ohsc_resource_categories()[$resource['category']]); ?></span><h3><?php echo ohsc_h($resource['title']); ?></h3><p><?php echo ohsc_h($resource['summary']); ?></p><div class="resource-file-badges"><?php foreach (array_unique(array_column($files,'extension')) as $ext) { ?><span>▧ <?php echo ohsc_h($formats[$ext]); ?></span><?php } ?><strong>FREE</strong></div></div><div class="resource-downloads"><?php foreach ($files as $file) { ?><a class="resource-btn" href="<?php echo ohsc_h($site.'/tools/resources/download.php?id='.$file['file_id']); ?>" aria-label="<?php echo ohsc_h('Download '.$resource['title'].' — '.$file['original_name']); ?>">Download <?php echo ohsc_h($formats[$file['extension']]); ?> <span aria-hidden="true">↓</span></a><small><?php echo ohsc_h($file['original_name']); ?> · <?php echo number_format($file['bytes']/1024,0); ?> KB</small><?php } ?></div></article>
<?php } ?></div></section>
<?php } ?>
<?php if ($isHub) { ?>
<section class="resource-section"><h2>Browse by your goal</h2><p class="resource-sub">Find resources to support your next step.</p><div class="resource-goals"><?php $goalText=array('find-job'=>'CV templates, application support and more.','interviews'=>'Checklists, practice sheets and examples.','plan-career'=>'Goal planners, worksheets and guidance.','develop-skills'=>'Learning planners, skill builders and templates.');$goalIcons=array('find-job'=>'▣','interviews'=>'♧','plan-career'=>'▥','develop-skills'=>'◇');foreach ($goals as $key=>$label) { ?><a href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,'hub').'?goal='.$key.'#resource-list'); ?>"><span class="resource-goal-icon <?php echo ohsc_h($key); ?>" aria-hidden="true"><?php echo ohsc_resource_icon($key); ?></span><div><h3><?php echo ohsc_h($label); ?></h3><p><?php echo ohsc_h($goalText[$key]); ?></p></div><span aria-hidden="true">→</span></a><?php } ?></div></section>
<section class="resource-toolkit<?php echo $toolkitImage===''?' no-image':''; ?>"><h2>Start with a complete toolkit</h2><p class="resource-sub">Everything you need to support your job search.</p><div><?php if ($toolkitImage!=='') { ?><img src="<?php echo ohsc_h($toolkitImage); ?>" alt="A collection of career planning documents" width="640" height="440"><?php } ?><div><h3><?php echo $toolkits?ohsc_h($toolkits[0]['title']):'Job search toolkits'; ?></h3><p><?php echo $toolkits?ohsc_h($toolkits[0]['summary']):'Find practical resources to organise your applications and prepare for your next step.'; ?></p><a class="resource-gold" href="<?php echo ohsc_h($site.ohsc_resource_page_path($pages,'toolkits').'#resource-list'); ?>">Explore toolkits <span aria-hidden="true">→</span></a></div></div></section>
<section class="resource-section"><h2>Make these resources work for you</h2><p class="resource-sub">Get the most out of our free career resources.</p><div class="resource-steps"><article><span>1</span><div><h3>Choose a resource</h3><p>Find a template, checklist or worksheet that suits your needs.</p></div></article><article><span>2</span><div><h3>Edit or print</h3><p>Download and customise it, or print it out.</p></div></article><article><span>3</span><div><h3>Put it into practice</h3><p>Use it to take action in your career.</p></div></article></div></section>
<section class="resource-section resource-faq"><h2>Your questions, answered</h2><p class="resource-sub">Quick answers to common questions about our free resources.</p><details><summary>Are the resources free?</summary><p>Yes. All resources published on this page are free to download.</p></details><details><summary>Can I edit the templates?</summary><p>Word, Excel, PowerPoint and text files can be edited with compatible software. PDFs can be printed; editing or filling a PDF depends on the individual file.</p></details><details><summary>Which file format should I choose?</summary><p>Choose PDF for printing, Word for written documents, Excel for spreadsheets, or ZIP for a collection of files. Available formats are listed on each resource.</p></details></section>
<section class="resource-cta"><div><h2>Need help getting started?</h2><p>Explore practical advice for your next career step.</p></div><a class="resource-gold" href="<?php echo ohsc_h($site); ?>/OHSC-Blog">Explore career advice <span aria-hidden="true">→</span></a></section>
<?php } ?>
</div></main>
<?php include __DIR__.'/../../includes/footer-new.php'; ?></html>
