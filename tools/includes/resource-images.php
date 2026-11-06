<?php
function ohsc_resource_images_table($db) {
    static $ready=false;if ($ready) { return; }$db->query("CREATE TABLE IF NOT EXISTS tbl_resource_page_images(page_key VARCHAR(80) NOT NULL,slot VARCHAR(80) NOT NULL,filename VARCHAR(80) NOT NULL DEFAULT '',is_hidden TINYINT(1) NOT NULL DEFAULT 0,PRIMARY KEY(page_key,slot)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");$ready=true;
}
function ohsc_resource_featured_items() {
    return array(array('CV Templates','cv-templates','','featured-cv'),array('Interview Checklists','interview-checklists','','featured-interviews'),array('Career Planning Worksheets','career-planning-worksheets','','featured-planning'),array('Job Search Toolkit','toolkits','Job Search Toolkit','featured-toolkit'),array('Professional Development Planner','downloads','Professional Development Planner','featured-development'));
}
function ohsc_resource_image_specs($key) {
    $slots=array('hero'=>array('label'=>'Page hero image','width'=>640,'height'=>440),'card-default'=>array('label'=>'Default resource card image','width'=>640,'height'=>400));
    if ($key==='hub') { $slots['toolkit']=array('label'=>'Complete toolkit section image','width'=>640,'height'=>440);foreach (ohsc_resource_featured_items() as $f) { $slots[$f[3]]=array('label'=>'Featured: '.$f[0],'width'=>640,'height'=>400); } }return $slots;
}
function ohsc_resource_image_records($db,$key) {
    ohsc_resource_images_table($db);$stmt=$db->prepare('SELECT * FROM tbl_resource_page_images WHERE page_key=?');$stmt->bind_param('s',$key);$stmt->execute();$rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();return array_column($rows,null,'slot');
}
function ohsc_resource_image_url($records,$slot,$base,$fallback=null) {
    if (isset($records[$slot])) { $r=$records[$slot];if ($r['is_hidden']) { return ''; }if (preg_match('/^[a-f0-9]{32}\.webp$/',$r['filename'])&&is_file(dirname(__DIR__).'/uploads/'.$r['filename'])) { return rtrim($base,'/').'/tools/uploads/'.$r['filename']; } }
    $defaults=array('featured-cv'=>'cv','featured-interviews'=>'checklist','featured-planning'=>'plan','featured-toolkit'=>'toolkit','featured-development'=>'development');
    if (isset($defaults[$slot]) && ($fallback===null || strpos($fallback,'/tools/assets/resources-hero.svg')!==false)) { return rtrim($base,'/').'/tools/assets/resources-'.$defaults[$slot].'.svg'; }
    return $fallback!==null?$fallback:rtrim($base,'/').'/tools/assets/resources-hero.svg';
}
function ohsc_resource_image_store($db,$key,$slot,$filename,$hidden) {
    ohsc_resource_images_table($db);$stmt=$db->prepare('INSERT INTO tbl_resource_page_images(page_key,slot,filename,is_hidden) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE filename=VALUES(filename),is_hidden=VALUES(is_hidden)');$stmt->bind_param('sssi',$key,$slot,$filename,$hidden);$stmt->execute();$stmt->close();
}
