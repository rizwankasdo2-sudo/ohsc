<?php
require_once __DIR__.'/resources.php';
ohsc_resources_ensure($db);
$hubResourcePages=ohsc_resource_pages($db);
// Content for the career hub only. Existing tool/admin data stays authoritative.
if (!function_exists('ohsc_hub_icon')) {
    function ohsc_hub_icon($slug)
    {
        $paths = array(
            'career-quiz' => '<rect x="20" y="19" width="42" height="53" rx="5"/><path d="M32 19v-5h18v5M29 34l4 4 8-9m-12 20 4 4 8-9m5-9h8m-8 15h8"/><circle cx="64" cy="64" r="15" fill="#ffb638"/><path d="M60 59a5 5 0 0 1 9 3c0 4-5 4-5 7m0 4v1"/>',
            'cv-builder' => '<path d="M25 12h30l15 15v48H25zM55 12v17h15M35 53h24M35 63h16"/><text x="34" y="44" stroke="none" fill="currentColor" font-size="16" font-weight="700">CV</text>',
            'interview-prep' => '<rect x="28" y="9" width="36" height="27" rx="6"/><path d="m40 36-7 9v-9"/><circle cx="39" cy="22" r="1"/><circle cx="47" cy="22" r="1"/><circle cx="55" cy="22" r="1"/><circle cx="26" cy="54" r="9" fill="currentColor"/><circle cx="66" cy="54" r="9" fill="currentColor"/><path d="M11 77c0-18 30-18 30 0zm40 0c0-18 30-18 30 0z" fill="currentColor"/>',
            'typing-test' => '<rect x="9" y="29" width="67" height="40" rx="7"/><path d="M38 29v-9q0-8 10-8h18M20 40h2m10 0h2m10 0h2m10 0h2M20 50h2m10 0h2m10 0h2m10 0h2M23 60h30"/><circle cx="70" cy="68" r="16" fill="white" stroke="#ff9e15"/><path d="M70 57v12l7 4" stroke="#ff9e15"/>',
            'eq-test' => '<path d="M26 77V60L15 58l8-16c-1-33 47-34 48-5 0 13-10 18-12 25v15" fill="#dce0e7" stroke="none"/><path d="M45 51c-23-13-10-27 0-15 10-12 23 2 0 15" fill="#ffa51f" stroke="white"/><path d="M43 7V1M22 14l-5-6m47 6 5-6M12 30l-6-3m68 3 6-3"/>',
        );
        $path = isset($paths[$slug]) ? $paths[$slug] : $paths['cv-builder'];
        return '<svg viewBox="0 0 92 88" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" focusable="false">' . $path . '</svg>';
    }
}
$interviewItems = array();
foreach ($industries as $industry) {
    $interviewItems[] = array('title' => $industry['title'], 'image' => basename($industry['image']), 'url' => $siteBase . '/tools/interview-prep/?industry=' . rawurlencode($industry['id']) . '#iv-questions');
}
$guideItems = array(
    array('title' => 'How to Choose a Career Path', 'image' => 'career-service.jpg', 'url' => $siteBase . '/OHSC-Blog'),
    array('title' => 'How to Develop In-Demand Skills', 'image' => 'career-office.jpg', 'url' => $siteBase . '/OHSC-Blog'),
);
$adviceItems = array();
// Use real published article slugs, so cards follow the site's existing routes.
$postResult = $db->query("SELECT title, slug FROM blog WHERE status = 1 AND slug <> '' ORDER BY publish_date DESC LIMIT 8");
if ($postResult) {
    while ($post = $postResult->fetch_assoc()) {
        $adviceItems[] = array('title' => strip_tags($post['title']), 'image' => count($adviceItems) % 2 ? 'career-office.jpg' : 'career-service.jpg', 'url' => $siteBase . '/OHSC-Blog/' . rawurlencode($post['slug']));
    }
}
$guideResult = $db->query("SELECT title, slug FROM blog WHERE status = 1 AND slug <> '' AND (title LIKE '%career%' OR title LIKE '%skills%') ORDER BY publish_date DESC LIMIT 6");
if ($guideResult) {
    $publishedGuides = array();
    while ($post = $guideResult->fetch_assoc()) {
        $publishedGuides[] = array('title' => strip_tags($post['title']), 'image' => count($publishedGuides) % 2 ? 'career-office.jpg' : 'career-service.jpg', 'url' => $siteBase . '/OHSC-Blog/' . rawurlencode($post['slug']));
    }
    if ($publishedGuides) { $guideItems = $publishedGuides; }
}
$courseItems = array();
// Select each subject separately so neither is crowded out by the other.
$courseGroups = array();
foreach (array('project management', 'digital marketing') as $subject) {
    $group = array();
    $courseResult = $db->query("SELECT td.name, td.slug, tc.slug AS catslug FROM tbl_diploma td INNER JOIN tbl_courses tc ON tc.cid = td.cid WHERE td.IStatus = 1 AND tc.IStatus = 1 AND td.slug <> '' AND tc.slug <> '' AND td.name LIKE '%" . $subject . "%' ORDER BY td.diplomaid DESC LIMIT 4");
    if ($courseResult) {
        while ($course = $courseResult->fetch_assoc()) {
            $group[] = array('title' => strip_tags($course['name']), 'image' => $subject === 'project management' ? 'career-service.jpg' : 'career-office.jpg', 'url' => $siteBase . '/courses/' . rawurlencode($course['catslug']) . '/' . rawurlencode($course['slug']));
        }
    }
    $courseGroups[] = $group;
}
for ($i = 0; $i < 4; $i++) {
    foreach ($courseGroups as $group) {
        if (isset($group[$i])) { $courseItems[] = $group[$i]; }
    }
}
if (!$courseItems) {
    $courseItems = array(
        array('title' => 'Project Management', 'image' => 'career-service.jpg', 'url' => $siteBase . '/free-online-courses-with-certificates-in-project-management'),
        array('title' => 'Digital Marketing', 'image' => 'career-office.jpg', 'url' => $siteBase . '/free-digital-marketing-courses'),
    );
}
$sections = array();
if ($interviewItems) {
    $sections[] = array('id' => 'interview-industries', 'title' => 'Interview Questions by Industry', 'summary' => 'Practise real interview questions with sample answers.', 'view' => 'View all industries', 'url' => $siteBase . '/tools/interview-prep/#iv-professions', 'items' => $interviewItems);
}
$sections[] = array('id' => 'career-guides', 'title' => 'Career Guides', 'summary' => 'Step-by-step guidance to help you make informed career decisions.', 'view' => 'View all guides', 'url' => $siteBase . '/OHSC-Blog', 'items' => $guideItems);
$sections[] = array('id' => 'career-resources', 'title' => 'Free Career Resources', 'summary' => 'Download templates, checklists and practical worksheets.', 'view' => 'View all resources', 'url' => $siteBase . ohsc_resource_page_path($hubResourcePages, 'hub'), 'items' => array(
    array('title' => 'CV Templates', 'icon' => 'cv-builder', 'url' => $siteBase . ohsc_resource_page_path($hubResourcePages, 'templates')),
    array('title' => 'Interview Checklists', 'icon' => 'interview-prep', 'url' => $siteBase . ohsc_resource_page_path($hubResourcePages, 'checklists')),
    array('title' => 'Career Worksheets', 'icon' => 'career-quiz', 'url' => $siteBase . ohsc_resource_page_path($hubResourcePages, 'worksheets')),
    array('title' => 'Career Toolkits', 'icon' => 'eq-test', 'url' => $siteBase . ohsc_resource_page_path($hubResourcePages, 'toolkits')),
));
$sections[] = array('id' => 'featured-courses', 'title' => 'Featured Courses', 'summary' => 'Learn new skills with expert-designed online courses.', 'view' => 'View all courses', 'url' => $siteBase . '/free-online-courses-with-certificates', 'items' => $courseItems);
if ($adviceItems) {
    $sections[] = array('id' => 'career-advice', 'title' => 'Latest Career Advice', 'summary' => 'Practical ideas for your next step.', 'view' => 'View all articles', 'url' => $siteBase . '/OHSC-Blog', 'items' => $adviceItems);
}
return $sections;
