<?php
function ohsc_resource_page_seed() {
    $tree=array(
        'templates'=>array('Templates','Ready-to-use professional templates.',array('cv-templates'=>'CV Templates','cover-letter-templates'=>'Cover Letter Templates','career-planning-templates'=>'Career Planning Templates','interview-templates'=>'Interview Templates','professional-development-templates'=>'Professional Development Templates')),
        'checklists'=>array('Checklists','Simple action-based resources.',array('job-search-checklists'=>'Job Search Checklists','interview-checklists'=>'Interview Checklists','career-change-checklists'=>'Career Change Checklists','remote-work-checklists'=>'Remote Work Checklists')),
        'examples'=>array('Examples','Practical examples to show you what good looks like.',array('cv-examples'=>'CV Examples','cover-letter-examples'=>'Cover Letter Examples','linkedin-examples'=>'LinkedIn Examples','interview-answer-examples'=>'Interview Answer Examples')),
        'worksheets'=>array('Worksheets','Interactive resources for your career and personal development.',array('career-planning-worksheets'=>'Career Planning Worksheets','goal-setting-worksheets'=>'Goal Setting Worksheets','skills-assessment-worksheets'=>'Skills Assessment Worksheets','interview-preparation-worksheets'=>'Interview Preparation Worksheets')),
        'faqs'=>array('FAQs','Answers to your career questions.',array('career-faqs'=>'Career FAQs','job-search-faqs'=>'Job Search FAQs','interview-faqs'=>'Interview FAQs','remote-work-faqs'=>'Remote Work FAQs','professional-development-faqs'=>'Professional Development FAQs')),
        'downloads'=>array('Downloads','Downloadable workbooks, planners and practical guides.',array()),
        'toolkits'=>array('Toolkits','Bundled resources to support your next career step.',array())
    );
    $rows=array(array('page_key'=>'hub','parent_key'=>'','slug'=>'resources','title'=>'Free Career Resources','summary'=>'Templates, examples, downloads, checklists, worksheets, planners and practical tools.'));
    foreach ($tree as $key=>$data) {
        $rows[]=array('page_key'=>$key,'parent_key'=>'hub','slug'=>$key,'title'=>$data[0],'summary'=>$data[1]);
        foreach ($data[2] as $child=>$title) { $rows[]=array('page_key'=>$child,'parent_key'=>$key,'slug'=>$child,'title'=>$title,'summary'=>'Browse '.strtolower($title).' to support your next career step.'); }
    }
    return $rows;
}
function ohsc_resource_pages_ensure($db) {
    $db->query("CREATE TABLE IF NOT EXISTS tbl_resource_pages (
        page_key VARCHAR(80) NOT NULL PRIMARY KEY,parent_key VARCHAR(80) NOT NULL,
        slug VARCHAR(80) NOT NULL,title VARCHAR(150) NOT NULL,summary VARCHAR(500) NOT NULL DEFAULT '',
        meta_title VARCHAR(200) NOT NULL,meta_description VARCHAR(500) NOT NULL DEFAULT '',
        meta_keywords VARCHAR(500) NOT NULL DEFAULT '',meta_robots VARCHAR(100) NOT NULL DEFAULT 'noindex, nofollow',
        UNIQUE KEY sibling_slug(parent_key,slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $db->query("CREATE TABLE IF NOT EXISTS tbl_resource_page_aliases (path VARCHAR(255) NOT NULL PRIMARY KEY,page_key VARCHAR(80) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $cols=array_column($db->query('SHOW COLUMNS FROM tbl_resource_pages')->fetch_all(MYSQLI_ASSOC),'Field');
    if (!in_array('resource_id',$cols,true)) { try { $db->query('ALTER TABLE tbl_resource_pages ADD resource_id INT UNSIGNED NOT NULL DEFAULT 0'); } catch (mysqli_sql_exception $e) { if ((int)$e->getCode()!==1060) throw $e; } }
    $stmt=$db->prepare("INSERT IGNORE INTO tbl_resource_pages(page_key,parent_key,slug,title,summary,meta_title,meta_description) VALUES(?,?,?,?,?,?,?)");
    foreach (ohsc_resource_page_seed() as $r) { $meta=$r['title'].' | OHSC Career Expert';$stmt->bind_param('sssssss',$r['page_key'],$r['parent_key'],$r['slug'],$r['title'],$r['summary'],$meta,$r['summary']);$stmt->execute(); }$stmt->close();
}
function ohsc_resource_pages($db) {
    $rows=$db->query('SELECT * FROM tbl_resource_pages')->fetch_all(MYSQLI_ASSOC);return array_column($rows,null,'page_key');
}
function ohsc_resource_page_path($pages,$key) {
    if (!isset($pages[$key])) { return ''; }
    if (in_array($key,array('cv-templates','cv-examples','cover-letter-templates','cover-letter-examples'),true)) { return '/career-expert/cv-and-cover-letters/'.$pages[$key]['slug']; }
    $isHub=$key==='hub';$segments=array();$guard=0;
    while ($key!=='' && isset($pages[$key]) && $guard++<4) { array_unshift($segments,$pages[$key]['slug']);$key=$pages[$key]['parent_key']; }
    return '/career-expert/'.implode('/',$segments);
}
function ohsc_resource_page_resolve($pages,$path) {
    foreach ($pages as $key=>$page) { if (rtrim($path,'/')===rtrim(ohsc_resource_page_path($pages,$key),'/')) { return $page; } }return null;
}
function ohsc_resource_robots_options() { return array('index, follow','noindex, follow','index, nofollow','noindex, nofollow'); }
function ohsc_resource_page_save($db,$pages,$key,$input,$validateOnly=false,$manageTransaction=true) {
    if (!isset($pages[$key])) { return 'Unknown resource page.'; }
    $limits=array('slug'=>80,'title'=>150,'summary'=>500,'meta_title'=>200,'meta_description'=>500,'meta_keywords'=>500,'meta_robots'=>100);
    foreach ($limits as $field=>$max) { if (!isset($input[$field]) || !is_string($input[$field]) || strlen($input[$field])>$max) { return 'Check the length of '.$field.'.'; } }
    if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D',$input['slug']) || $input['title']==='' || $input['meta_title']==='') { return 'Use a lowercase slug with letters, numbers and hyphens, and add a page title and meta title.'; }
    if (!in_array($input['meta_robots'],ohsc_resource_robots_options(),true)) { return 'Choose a valid meta robots option.'; }
    foreach ($pages as $otherKey=>$other) { if ($otherKey!==$key && $other['parent_key']===$pages[$key]['parent_key'] && $other['slug']===$input['slug']) { return 'That slug is already used in this section.'; } }
    $proposed=$pages;$proposed[$key]=array_merge($pages[$key],$input);
    foreach ($proposed as $pageKey=>$page) { if (strlen(ohsc_resource_page_path($proposed,$pageKey))>240) { return 'The full page URL is too long. Shorten this slug.'; } }
    // An old URL must never be reassigned to another page.
    $aliases=$db->query('SELECT path,page_key FROM tbl_resource_page_aliases')->fetch_all(MYSQLI_ASSOC);
    foreach ($proposed as $pageKey=>$page) { foreach ($aliases as $alias) { if (rtrim($alias['path'],'/')===rtrim(ohsc_resource_page_path($proposed,$pageKey),'/') && $alias['page_key']!==$pageKey) { return 'That URL belongs to a previous page address. Choose another slug.'; } } }
    if ($key==='hub' && $input['slug']!==$pages[$key]['slug']) {
        $root=dirname(__DIR__,2);$slug=$input['slug'];
        if (in_array($slug,array('admin','tools','career-expert','assets','images','courses','lms','fonts','css','js'),true) || ($slug!=='resources' && (is_dir($root.'/'.$slug) || is_file($root.'/'.$slug.'.php')))) { return 'That hub slug is reserved for the website.'; }
        $stmt=$db->prepare('SELECT 1 FROM pages WHERE slug=? UNION SELECT 1 FROM tbl_courses WHERE slug=? LIMIT 1');$stmt->bind_param('ss',$slug,$slug);$stmt->execute();$taken=$stmt->get_result()->num_rows>0;$stmt->close();if ($taken) { return 'That hub slug is already used on the website.'; }
    }
    if (function_exists('ohsc_tool_page_path') && $db->query("SHOW TABLES LIKE 'tbl_tool_pages'")->num_rows) {
        $toolPages=array_column($db->query('SELECT * FROM tbl_tool_pages')->fetch_all(MYSQLI_ASSOC),null,'page_key');
        foreach ($proposed as $pageKey=>$page) { foreach ($toolPages as $toolKey=>$toolPage) { if (rtrim(ohsc_resource_page_path($proposed,$pageKey),'/')===rtrim(ohsc_tool_page_path($toolPages,$toolKey),'/')) { return 'That URL is already used by a tool or career directory.'; } } }
    }
    if ($validateOnly) { return ''; }
    if ($manageTransaction) { $db->begin_transaction(); }
    try {
        foreach ($pages as $pageKey=>$page) {
            $old=rtrim(ohsc_resource_page_path($pages,$pageKey),'/');$new=rtrim(ohsc_resource_page_path($proposed,$pageKey),'/');
            if ($old!==$new) { $stmt=$db->prepare('INSERT INTO tbl_resource_page_aliases(path,page_key) VALUES(?,?) ON DUPLICATE KEY UPDATE page_key=VALUES(page_key)');$stmt->bind_param('ss',$old,$pageKey);$stmt->execute();$stmt->close(); }
        }
        $stmt=$db->prepare('UPDATE tbl_resource_pages SET slug=?,title=?,summary=?,meta_title=?,meta_description=?,meta_keywords=?,meta_robots=? WHERE page_key=?');
        $stmt->bind_param('ssssssss',$input['slug'],$input['title'],$input['summary'],$input['meta_title'],$input['meta_description'],$input['meta_keywords'],$input['meta_robots'],$key);$stmt->execute();$stmt->close();if ($manageTransaction) { $db->commit(); }
    } catch (Throwable $e) { if ($manageTransaction) { $db->rollback(); }return 'Could not save page settings. Please retry.'; }
    return '';
}

function ohsc_resource_page_create($db,$pages,$input,$parent,$resourceId) {
    if (!isset($pages[$parent]) || !empty($pages[$parent]['resource_id']) || ($parent!=='hub' && $pages[$parent]['parent_key']!=='hub')) { return array('Choose the hub or a category as parent.',null); }
    $stmt=$db->prepare('SELECT resource_id FROM tbl_career_resources WHERE resource_id=? AND is_deleted=0');$stmt->bind_param('i',$resourceId);$stmt->execute();$valid=$stmt->get_result()->num_rows;$stmt->close();
    if (!$valid) return array('Choose an existing resource for this page. Add the resource and its files first.',null);
    $key='page-'.bin2hex(random_bytes(12));$next=$pages;$next[$key]=array('page_key'=>$key,'parent_key'=>$parent,'slug'=>$input['slug'],'title'=>$input['title'],'summary'=>$input['summary'],'resource_id'=>$resourceId);
    $error=ohsc_resource_page_save($db,$next,$key,$input,true);if ($error) return array($error,null);
    foreach ($pages as $p) { if (!empty($p['resource_id'])&&(int)$p['resource_id']===$resourceId) return array('This resource already has a page. Edit its existing page instead.',null); }
    try { $stmt=$db->prepare('INSERT INTO tbl_resource_pages(page_key,parent_key,slug,title,summary,meta_title,meta_description,meta_keywords,meta_robots,resource_id) VALUES(?,?,?,?,?,?,?,?,?,?)');$stmt->bind_param('sssssssssi',$key,$parent,$input['slug'],$input['title'],$input['summary'],$input['meta_title'],$input['meta_description'],$input['meta_keywords'],$input['meta_robots'],$resourceId);$stmt->execute();$stmt->close(); }
    catch (mysqli_sql_exception $e) { return array('The page could not be created. Check the slug is unique.',null); }
    return array('',$key);
}

/** Publish preferred URLs only where a real, existing starter resource is available. */
function ohsc_resource_preferred_pages($db) {
    $manifest=json_decode(file_get_contents(__DIR__.'/preferred-resource-pages.json'),true);
    foreach ($manifest as $r) {
        $key=strtolower($r['id']);$stmt=$db->prepare('SELECT r.* FROM tbl_resource_starter_seeds s JOIN tbl_career_resources r ON r.resource_id=s.resource_id WHERE s.seed_key=? AND r.is_deleted=0');$stmt->bind_param('s',$r['seed']);$stmt->execute();$resource=$stmt->get_result()->fetch_assoc();$stmt->close();if (!$resource) continue;
        $meta=$resource['title'].' | OHSC Career Expert';$robots='noindex, nofollow';
        $stmt=$db->prepare("INSERT IGNORE INTO tbl_resource_pages(page_key,parent_key,slug,title,summary,meta_title,meta_description,meta_keywords,meta_robots,resource_id) VALUES(?,'hub',?,?,?,?,?,?,?,?)");$stmt->bind_param('ssssssssi',$key,$r['slug'],$resource['title'],$resource['summary'],$meta,$resource['summary'],$r['keywords'],$robots,$resource['resource_id']);$stmt->execute();$stmt->close();
    }
}

function ohsc_resource_owner_migrate($db) {
    $db->query("CREATE TABLE IF NOT EXISTS tbl_career_updates(version VARCHAR(80) PRIMARY KEY) ENGINE=InnoDB");
    if ($db->query("SELECT version FROM tbl_career_updates WHERE version='cv-resource-owners-2026-11'")->num_rows) return;
    $pages=ohsc_resource_pages($db);$db->begin_transaction();try {
        $db->query("INSERT IGNORE INTO tbl_career_updates VALUES('cv-resource-owners-2026-11')");if (!$db->affected_rows) {$db->rollback();return;}
        foreach (array('cv-templates','cv-examples','cover-letter-templates','cover-letter-examples') as $key) {
            $p=$pages[$key];$category=$pages[$p['parent_key']]['slug'];
            foreach (array('/resources/','/career-expert/resources/') as $prefix) { $old=str_replace('/resources/','/'.$pages['hub']['slug'].'/',$prefix).$category.'/'.$p['slug'];$stmt=$db->prepare('INSERT IGNORE INTO tbl_resource_page_aliases(path,page_key) VALUES(?,?)');$stmt->bind_param('ss',$old,$key);$stmt->execute();$stmt->close(); }
        }$db->commit();
    } catch (Throwable $e) { $db->rollback();throw $e; }
}
