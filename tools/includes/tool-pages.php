<?php
require_once __DIR__.'/resource-pages.php';
function ohsc_tool_pages_ensure($db) {
    static $ready=false;if ($ready) { return; }
    $db->query("CREATE TABLE IF NOT EXISTS tbl_tool_pages(page_key VARCHAR(100) NOT NULL PRIMARY KEY,tool_slug VARCHAR(80) NOT NULL,industry_id INT UNSIGNED NOT NULL DEFAULT 0,slug VARCHAR(80) NOT NULL,title VARCHAR(150) NOT NULL,meta_title VARCHAR(200) NOT NULL,meta_description VARCHAR(500) NOT NULL DEFAULT '',meta_keywords VARCHAR(500) NOT NULL DEFAULT '',meta_robots VARCHAR(100) NOT NULL DEFAULT 'noindex, nofollow') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $db->query("CREATE TABLE IF NOT EXISTS tbl_tool_page_aliases(path VARCHAR(255) NOT NULL PRIMARY KEY,page_key VARCHAR(100) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    if (!$db->query("SHOW COLUMNS FROM tbl_tool_pages LIKE 'route_group'")->num_rows) { try { $db->query("ALTER TABLE tbl_tool_pages ADD route_group VARCHAR(30) NOT NULL DEFAULT ''"); } catch (mysqli_sql_exception $e) { if ((int)$e->getCode()!==1060) throw $e; } }
    $seeds=array(array('tools-hub','hub',0,'','Career Tools','Free career tools, resources and practical support for your next step.'));
    foreach (array('career-guides'=>'Career Guides','career-advice'=>'Career Advice','cv-and-cover-letters'=>'CV and Cover Letters') as $slug=>$title) { $seeds[]=array($slug,'editorial',0,$slug,$title,'Practical guidance and resources for your next career step.'); }
    $tools=$db->query("SELECT slug,title,summary FROM tbl_site_tools WHERE slug IN ('typing-test','eq-test','career-quiz','cv-builder','interview-prep')")->fetch_all(MYSQLI_ASSOC);
    foreach ($tools as $t) { $seeds[]=array($t['slug'],$t['slug'],0,$t['slug'],$t['title'],$t['summary']); }
    $industries=$db->query('SELECT industry_id,slug,title,summary FROM tbl_iv_industries')->fetch_all(MYSQLI_ASSOC);
    foreach ($industries as $i) { $seeds[]=array('profession-'.$i['industry_id'],'interview-prep',(int)$i['industry_id'],$i['slug'],$i['title'].' Interview Questions',$i['summary']); }
    $stmt=$db->prepare('INSERT IGNORE INTO tbl_tool_pages(page_key,tool_slug,industry_id,slug,title,meta_title,meta_description) VALUES(?,?,?,?,?,?,?)');
    foreach ($seeds as $s) { $meta=$s[4].' | OHSC Career Expert';$stmt->bind_param('ssissss',$s[0],$s[1],$s[2],$s[3],$s[4],$meta,$s[5]);$stmt->execute(); }$stmt->close();ohsc_tool_route_migrate($db);ohsc_interview_route_migrate($db);$ready=true;
}
function ohsc_tool_pages($db) { ohsc_tool_pages_ensure($db);return array_column($db->query('SELECT * FROM tbl_tool_pages ORDER BY industry_id,page_key')->fetch_all(MYSQLI_ASSOC),null,'page_key'); }
function ohsc_tool_page_path($pages,$key) {
    if (!isset($pages[$key])) { return ''; }$p=$pages[$key];
    if ($key==='tools-hub') { return '/career-expert'.($p['slug']!==''?'/'.$p['slug']:''); }
    if ($p['industry_id']) { return '/career-expert/'.$pages['interview-prep']['slug'].'/'.(!empty($p['route_group'])?$p['route_group'].'/':'').$p['slug']; }
    return '/career-expert/'.$p['slug'];
}
function ohsc_tool_url($db,$base,$key) { return rtrim($base,'/').ohsc_tool_page_path(ohsc_tool_pages($db),$key); }
function ohsc_tool_page_save($db,$pages,$key,$input,$validateOnly=false,$manageTransaction=true) {
    if (!isset($pages[$key])) { return 'Unknown tool page.'; }
    foreach (array('slug'=>80,'meta_title'=>200,'meta_description'=>500,'meta_keywords'=>500,'meta_robots'=>100) as $field=>$max) { if (!isset($input[$field]) || !is_string($input[$field]) || strlen($input[$field])>$max) { return 'Check '.$field.'.'; } }
    if (($key!=='tools-hub'||$input['slug']!=='') && !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D',$input['slug'])) { return 'Use a lowercase slug with letters, numbers and hyphens.'; }
    if ($input['meta_title']==='' || !in_array($input['meta_robots'],ohsc_resource_robots_options(),true)) { return 'Add a meta title and choose a valid robots option.'; }
    $next=$pages;$next[$key]=array_merge($pages[$key],$input);$seen=array();$aliases=$db->query('SELECT path,page_key FROM tbl_tool_page_aliases')->fetch_all(MYSQLI_ASSOC);
    foreach ($next as $k=>$p) {
        $path=ohsc_tool_page_path($next,$k);
        if (isset($seen[$path])) { return 'That slug is already used by another tool page.'; }$seen[$path]=$k;
        if (strlen($path)>240) { return 'Shorten the slug.'; }
        foreach ($aliases as $alias) { if ($alias['path']===$path && $alias['page_key']!==$k) { return 'That URL is reserved for a previous tool address.'; } }
        if (!$p['industry_id'] && $p['slug']!=='' && in_array($p['slug'],array('assets','admin','includes','resources','uploads','resource-files','seed-resources','scripts','tests'),true)) { return 'That slug is reserved.'; }
        if (!$p['industry_id']) { foreach (array('typing-test','eq-test','cv-builder','career-quiz','interview-prep') as $physical) { if ($p['slug']===$physical && $p['tool_slug']!==$physical) { return 'That slug is reserved for its original tool.'; } } }
    }
    if ($db->query("SHOW TABLES LIKE 'tbl_resource_pages'")->num_rows) {
        $resourcePages=array_column($db->query('SELECT * FROM tbl_resource_pages')->fetch_all(MYSQLI_ASSOC),null,'page_key');
        foreach ($next as $toolKey=>$toolPage) { foreach ($resourcePages as $resourceKey=>$resourcePage) { if (rtrim(ohsc_tool_page_path($next,$toolKey),'/')===rtrim(ohsc_resource_page_path($resourcePages,$resourceKey),'/')) { return 'That URL is already used by a resource page.'; } } }
    }
    if ($validateOnly) { return ''; }
    if ($manageTransaction) { $db->begin_transaction(); }try {
        foreach ($pages as $k=>$p) { $old=ohsc_tool_page_path($pages,$k);if ($old!==ohsc_tool_page_path($next,$k)) { $stmt=$db->prepare('INSERT INTO tbl_tool_page_aliases(path,page_key) VALUES(?,?) ON DUPLICATE KEY UPDATE page_key=VALUES(page_key)');$stmt->bind_param('ss',$old,$k);$stmt->execute();$stmt->close(); } }
        $stmt=$db->prepare('UPDATE tbl_tool_pages SET slug=?,meta_title=?,meta_description=?,meta_keywords=?,meta_robots=? WHERE page_key=?');$stmt->bind_param('ssssss',$input['slug'],$input['meta_title'],$input['meta_description'],$input['meta_keywords'],$input['meta_robots'],$key);$stmt->execute();$stmt->close();if ($manageTransaction) { $db->commit(); }
    } catch (Throwable $e) { if ($manageTransaction) { $db->rollback(); }return 'Could not save tool SEO.'; }return '';
}
function ohsc_tool_seo_init($db,$tool,$base,$industry=0) {
    ohsc_tools_ensure($db);$pages=ohsc_tool_pages($db);$key=$industry?'profession-'.(int)$industry:($tool==='hub'?'tools-hub':$tool);$page=isset($pages[$key])?$pages[$key]:null;
    if (!$page) { return null; }$path=ohsc_tool_page_path($pages,$key);$requested=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));
    if ($requested!==$path || ($industry && isset($_GET['industry']) && empty($_SERVER['OHSC_TOOL_ROUTED_INDUSTRY']))) {
        $query=$_GET;unset($query['industry']);header('Location: '.rtrim($base,'/').$path.($query?'?'.http_build_query($query):''),true,301);exit;
    }
    $page['canonical']=rtrim($base,'/').$path;return $page;
}
function ohsc_tool_seo_head($page) {
    if (!$page) { return; }
    echo '<title>'.ohsc_h($page['meta_title']).'</title><meta name="description" content="'.ohsc_h($page['meta_description']).'"><meta name="keywords" content="'.ohsc_h($page['meta_keywords']).'"><meta content="'.ohsc_h($page['meta_robots']).'" name="robots"><link rel="canonical" href="'.ohsc_h($page['canonical']).'">';
}

function ohsc_tool_route_migrate($db) {
    $db->query("CREATE TABLE IF NOT EXISTS tbl_career_updates(version VARCHAR(80) PRIMARY KEY) ENGINE=InnoDB");
    if ($db->query("SELECT version FROM tbl_career_updates WHERE version='routes-2026-11'")->num_rows) { return; }
    $db->begin_transaction();try {
        $db->query("INSERT IGNORE INTO tbl_career_updates VALUES('routes-2026-11')");
        if (!$db->affected_rows) { $db->rollback();return; }
        $rows=$db->query('SELECT * FROM tbl_tool_pages')->fetch_all(MYSQLI_ASSOC);$pages=array_column($rows,null,'page_key');
        foreach ($rows as $r) {
            $old=$r['page_key']==='tools-hub'?'/tools/':('/tools/'.($r['industry_id']?$pages['interview-prep']['slug'].'/':'').$r['slug'].'/');
            $stmt=$db->prepare('INSERT IGNORE INTO tbl_tool_page_aliases(path,page_key) VALUES(?,?)');$stmt->bind_param('ss',$old,$r['page_key']);$stmt->execute();$stmt->close();
        }
        foreach (array('typing-test'=>'typing-speed-test','eq-test'=>'emotional-intelligence-test','interview-prep'=>'interview-questions') as $old=>$new) {
            $stmt=$db->prepare('UPDATE tbl_tool_pages SET slug=? WHERE page_key=? AND slug=?');$stmt->bind_param('sss',$new,$old,$old);$stmt->execute();$stmt->close();
        }
        $db->commit();
    } catch (Throwable $e) { $db->rollback();throw $e; }
}

function ohsc_interview_route_migrate($db) {
    if ($db->query("SELECT version FROM tbl_career_updates WHERE version='interview-routes-2026-11'")->num_rows) return;
    $map=array('business'=>array('business-and-administration','industries'),'healthcare'=>array('healthcare-and-allied-health','industries'),'technology'=>array('technology-data-and-ai','industries'),'education'=>array('education-and-childcare','industries'),'project-management'=>array('project-and-programme-management','industries'),'customer-service'=>array('customer-service-interview-questions',''));
    $rows=$db->query('SELECT * FROM tbl_tool_pages')->fetch_all(MYSQLI_ASSOC);$pages=array_column($rows,null,'page_key');
    $db->begin_transaction();try {
        $db->query("INSERT IGNORE INTO tbl_career_updates VALUES('interview-routes-2026-11')");if (!$db->affected_rows) {$db->rollback();return;}
        foreach ($rows as $r) { if (!$r['industry_id']||!isset($map[$r['slug']])) continue;
            $old=ohsc_tool_page_path($pages,$r['page_key']);$stmt=$db->prepare('INSERT IGNORE INTO tbl_tool_page_aliases(path,page_key) VALUES(?,?)');$stmt->bind_param('ss',$old,$r['page_key']);$stmt->execute();$stmt->close();
            $next=$map[$r['slug']];$stmt=$db->prepare('UPDATE tbl_tool_pages SET slug=?,route_group=? WHERE page_key=?');$stmt->bind_param('sss',$next[0],$next[1],$r['page_key']);$stmt->execute();$stmt->close();
        }$db->commit();
    } catch (Throwable $e) { $db->rollback();throw $e; }
}
