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
    $stmt=$db->prepare("INSERT IGNORE INTO tbl_resource_pages(page_key,parent_key,slug,title,summary,meta_title,meta_description) VALUES(?,?,?,?,?,?,?)");
    foreach (ohsc_resource_page_seed() as $r) { $meta=$r['title'].' | OHSC Career Expert';$stmt->bind_param('sssssss',$r['page_key'],$r['parent_key'],$r['slug'],$r['title'],$r['summary'],$meta,$r['summary']);$stmt->execute(); }$stmt->close();
}
function ohsc_resource_pages($db) {
    $rows=$db->query('SELECT * FROM tbl_resource_pages')->fetch_all(MYSQLI_ASSOC);return array_column($rows,null,'page_key');
}
function ohsc_resource_page_path($pages,$key) {
    if (!isset($pages[$key])) { return ''; }
    $segments=array();$guard=0;
    while ($key!=='' && isset($pages[$key]) && $guard++<4) { array_unshift($segments,$pages[$key]['slug']);$key=$pages[$key]['parent_key']; }
    return '/career-expert/'.implode('/',$segments);
}
function ohsc_resource_page_resolve($pages,$path) {
    foreach ($pages as $key=>$page) { if (rtrim($path,'/')===ohsc_resource_page_path($pages,$key)) { return $page; } }return null;
}
function ohsc_resource_robots_options() { return array('index, follow','noindex, follow','index, nofollow','noindex, nofollow'); }
function ohsc_resource_page_save($db,$pages,$key,$input) {
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
    foreach ($proposed as $pageKey=>$page) { foreach ($aliases as $alias) { if ($alias['path']===ohsc_resource_page_path($proposed,$pageKey) && $alias['page_key']!==$pageKey) { return 'That URL belongs to a previous page address. Choose another slug.'; } } }
    $db->begin_transaction();
    try {
        foreach ($pages as $pageKey=>$page) {
            $old=ohsc_resource_page_path($pages,$pageKey);$new=ohsc_resource_page_path($proposed,$pageKey);
            if ($old!==$new) { $stmt=$db->prepare('INSERT INTO tbl_resource_page_aliases(path,page_key) VALUES(?,?) ON DUPLICATE KEY UPDATE page_key=VALUES(page_key)');$stmt->bind_param('ss',$old,$pageKey);$stmt->execute();$stmt->close(); }
        }
        $stmt=$db->prepare('UPDATE tbl_resource_pages SET slug=?,title=?,summary=?,meta_title=?,meta_description=?,meta_keywords=?,meta_robots=? WHERE page_key=?');
        $stmt->bind_param('ssssssss',$input['slug'],$input['title'],$input['summary'],$input['meta_title'],$input['meta_description'],$input['meta_keywords'],$input['meta_robots'],$key);$stmt->execute();$stmt->close();$db->commit();
    } catch (Throwable $e) { $db->rollback();return 'Could not save page settings. Please retry.'; }
    return '';
}
