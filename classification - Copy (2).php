<?php
include("admin/classes/connection.php");
require_once('includes/functions.php');
$classification_row = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1 AND id = " . $_GET['id'])->fetch_array();
                    
if (!$classification_row) {
    header('location:/');
    exit;
}

// Begin diploma results query creation
$course_query = "SELECT 
    d.diplomaid,
    d.cid,
    d.name,
    d.slug,
    d.square_icon,
    d.square_icon_alt,
    d.square_icon_title,
    d.square_icon_description,
    d.admin_rating,
    d.admin_total_enrolled,
    c.slug AS catslug,
    s.subjectid,
    s.course_duration 
FROM tbl_diploma AS d, tbl_courses AS c, tbl_subject AS s WHERE c.cid = d.cid AND s.diplomaid = d.diplomaid  AND c.IStatus = 1 AND d.IStatus = 1 AND d.classification = " . $classification_row['id'] . " ";
$items_count = 25;
$pagenum = 1;
if (isset($_POST['course_name'])) {
    $course_name = trim($_POST['course_name']);
    $free_courses = $_POST['free_courses'];
    $levels = $_POST['levels'];
    $certification = $_POST['certification'];
    $items_count = $_POST['items_count'];
    $pagenum = $_POST['pagenum'];
    if ($course_name) {
        $course_query .= " AND d.name LIKE '%$course_name%' ";
    }
    if ($free_courses) {
        $course_query .= " AND d.type <> 3 ";
    } else {
        $course_query .= " AND (d.type = 1 OR d.type > 3) ";
    }
    if (is_array($levels) && count($levels) > 0) {
        $levels_array = implode(',', $levels);
        $course_query .= " AND s.course_level IN ($levels_array) ";
    }
    if (is_array($certification) && count($certification) > 0) {
        $certification_array = implode(',', $certification);
        $course_query .= " AND d.certification IN ($certification_array) ";
    }
} else {
    $course_query .= " AND (d.type = 1 OR d.type > 3) ";
}
$offset = ($pagenum - 1) * $items_count;
$course_num = $db->query($course_query)->num_rows;
$course_query .= " ORDER BY d.ordernumber LIMIT $offset, $items_count ";
$course_result = $db->query($course_query);
$total_pages = ceil($course_num / $items_count);
// End diploma results query creation
?>
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $classification_row['page_title']; ?></title>
    <meta name="description" content="<?php echo strip_tags($classification_row['meta_desc']); ?>" />
    <meta name="keywords" content="<?php echo $classification_row['meta_key']; ?>" />
    <meta name="robots" content="<?php echo $classification_row['robots']; ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <?php include 'includes/header-new.php'; ?>
    <main id="main-content">
        <!-- Begin main section -->
        <form method="post" name="filterFrm" id="filterFrm">
            <input type="hidden" name="items_count" id="items_count" value="<?php echo $items_count; ?>" />
            <input type="hidden" name="pagenum" id="pagenum" value="<?php echo $pagenum; ?>" />
            <div class="mainformcon">
                <div class="flxcen">
                    <div class="formcon">Find a course</div>
                    <div class="search-flex">
                        <input type="text" name="course_name" id="search-input" autocomplete="off" placeholder="Subject or qualification, e.g. IT" value="<?php echo (!empty($_POST['course_name'])) ? $_POST['course_name'] : ''; ?>" onkeydown="if(event.key==='Enter'){ submitCourseForm('pagenum', 1); }" class="stxtbox" oninput="document.getElementById('search-input2').value=this.value;" />
                        <p id="results-list" class="results-hidden"></p>
                    </div>
                    <div style="padding-left:12px;">
                        <input type="submit" value="Search Courses" onclick="submitCourseForm('pagenum', 1);" class="gsubmit" />
                    </div>
                </div>
                <div class="flxcen" style="margin-top:16px;">
                    <?php
                    $classification_result_temp = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1 ORDER BY sort_order ");
                    while ($classification_row_temp = $classification_result_temp->fetch_array()) {
                    ?>
                    <div class="blcon">
                        <a href="<?php echo $base_url . '/' . $classification_row_temp['slug']; ?>" class="bluelink"><?php echo $classification_row_temp['name']; ?></a>
                    </div>
                    <?php } ?>
                    <div class="pdl10">
                        <a href="<?php echo $base_url; ?>/free-online-courses-with-certificates" class="bluelink">Free courses</a>
                    </div>
                </div>
            </div>
            <div style="background-color: #f8f8f8;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 pdtb24 smallnone">
                            <!-- Begin refine results -->
                            <div class="srhmodal srhmodaltop d-none">
                                <div class="reftxt">Refine results</div>
                                <div class="srhmodalclose curp" onclick="hideSearchSec();">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                        <path d="M2 2L12 12M12 2L2 12" stroke="#0D151A" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- End refine results -->
                            <div class="srhmodal d-none key2con">
                                <div class="keytxt mrgnbt5">Keywords</div>
                                <div class="posrel">
                                    <input type="text" name="course_name2" id="search-input2" autocomplete="off" placeholder="Subject or qualification, e.g. IT" value="<?php echo (!empty($_POST['course_name2'])) ? $_POST['course_name2'] : ''; ?>" onkeydown="if(event.key==='Enter'){ submitCourseForm('pagenum', 1); }" oninput="document.getElementById('search-input').value=this.value;" class="stxtbox" />
                                    <p id="results-list2" class="results-hidden"></p>
                                </div>
                            </div>
                            <div class="srhmodal d-none matchcon">
                                <div class="matchbtn" onclick="hideSearchSec();">View <?php echo $course_num; ?> matching courses</div>
                            </div>
                            <div class="whtcon smallnone">
                                <div class="sechead">Browse by</div>
                                <div class="whtline"></div>
                                <div class="secsubhead mrt20">Subjects</div>
                                <ul class="blinkcon">
                                    <?php
                                    $i = 0;
                                    $catgories_result = $db->query("SELECT cname, slug FROM tbl_courses WHERE IStatus = 1 ORDER BY ordernumber ASC");
                                    $catgories_count = $catgories_result->num_rows;
                                    while ($catgories_row = $catgories_result->fetch_array()) {
                                        $i++;
                                    ?>
                                    <li class="pdtb4 <?php echo ($i > 7) ? 'd-none fullCon' : 'shortCon'; ?>"><a href="<?php echo $base_url . '/courses/' . $catgories_row['slug']; ?>" class="bluelink"><?php echo $catgories_row['cname']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <?php if ($catgories_count > 7) { ?>
                                <div class="more-row">
                                    <button class="more-btn bluelink" id="moreBtn" aria-expanded="false" aria-controls="extraItems" type="button">
                                        <span class="bluelink" id="moreLabel">Show more</span>
                                        <svg class="chevron" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="whtcon mrt15 smallnone">
                                <div class="sechead">Filter by</div>
                                <div class="whtline"></div>
                                <div class="secsubhead mrt20">Qualification</div>
                                <ul class="blinkcon">
                                    <li class="pdtb4"><input type="checkbox" name="free_courses" value="1" onclick="submitCourseForm('pagenum', 1);" <?php echo (!empty($_POST['free_courses'])) ? 'checked' : ''; ?> /> <span class="bluelink curp" onclick="var cb = this.previousElementSibling;cb.checked = !cb.checked;submitCourseForm('pagenum', 1);">Free Courses</span></li>
                                    <li class="pdtb4"><input type="checkbox" name="classification[]" value="<?php echo $classification_row['id']; ?>" disabled="disabled" checked /> <span class="bluelinkna"><?php echo $classification_row['name']; ?></span></li>
                                </ul>
                                <div class="whtlineinner"></div>
                                <div class="secsubhead mrt20">Certification</div>
                                <ul class="blinkcon">
                                    <li class="pdtb4"><input type="checkbox" name="certification[]" value="1" onclick="submitCourseForm('pagenum', 1);" <?php echo (isset($_POST['certification']) &&
                        is_array($_POST['certification']) && in_array(1, $_POST['certification'])) ? 'checked' : ''; ?> /> <span class="bluelink curp" onclick="var cb = this.previousElementSibling;cb.checked = !cb.checked;submitCourseForm('pagenum', 1);">Certificate</span></li>
                                    <li class="pdtb4"><input type="checkbox" name="certification[]" value="2" onclick="submitCourseForm('pagenum', 1);" <?php echo (isset($_POST['certification']) &&
                        is_array($_POST['certification']) && in_array(2, $_POST['certification'])) ? 'checked' : ''; ?> /> <span class="bluelink curp" onclick="var cb = this.previousElementSibling;cb.checked = !cb.checked;submitCourseForm('pagenum', 1);">Diploma</span></li>
                                </ul>
                                <div class="whtlineinner"></div>
                                <div class="secsubhead mrt20">Course Level</div>
                                <ul class="blinkcon smallpd10">
                                    <?php
                                    $level_result = $db->query("SELECT course_level FROM tbl_subject WHERE course_level <> 0 AND diplomaid IN (SELECT diplomaid FROM tbl_diploma WHERE IStatus = 1 AND classification = " . $classification_row['id'] . ") GROUP BY course_level");
                                    while ($level_row = $level_result->fetch_array()) {
                                    ?>
                                    <li class="pdtb4"><input type="checkbox" name="levels[]" value="<?php echo $level_row['course_level']; ?>" onclick="submitCourseForm('pagenum', 1);" <?php echo (!empty($_POST['levels']) && in_array($level_row['course_level'], $_POST['levels'])) ? 'checked' : ''; ?> /> <span class="bluelink curp" onclick="var cb = this.previousElementSibling;cb.checked = !cb.checked;submitCourseForm('pagenum', 1);">Level <?php echo $level_row['course_level']; ?></span></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-9 pdtb24" id="condiv">
                            <div class="whtcon">
                                <div class="crsav"><?php echo $course_num; ?> course(s) found.</div>
                                <div class="newtxt mrt15">
                                    <p id="shortText">
                                        <?php echo $classification_row['header_excerpt']; ?>
                                    </p>
                                </div>
                                <div class="newtxt d-none" id="fullText">
                                    <p>
                                        <?php echo $classification_row['header_description']; ?>
                                    </p>
                                </div>
                                <div class="read-btn">
                                    <span id="toggleText" class="read-btn-text curp">Read More +</span>
                                </div>
                                <div class="fbtn curp mrt10 bignone" onclick="showSearchSec();">
                                    <div class="lricon" style="margin-top:3px;">
                                        <span class="filter-ico" aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" shape-rendering="crispEdges">
                                            <rect x="12" y="0" width="1" height="1" fill="#364576"/><rect x="13" y="0" width="1" height="1" fill="#3a4978"/>
                                            <rect x="12" y="1" width="2" height="1" fill="#001451"/>
                                            <rect x="0" y="2" width="1" height="1" fill="#364576"/><rect x="1" y="2" width="8" height="1" fill="#001451"/><rect x="9" y="2" width="1" height="1" fill="#364576"/><rect x="12" y="2" width="6" height="1" fill="#001451"/>
                                            <rect x="0" y="3" width="1" height="1" fill="#364576"/><rect x="1" y="3" width="8" height="1" fill="#001451"/><rect x="9" y="3" width="1" height="1" fill="#364576"/><rect x="12" y="3" width="6" height="1" fill="#001451"/>
                                            <rect x="12" y="4" width="2" height="1" fill="#001451"/>
                                            <rect x="12" y="5" width="1" height="1" fill="#364576"/><rect x="13" y="5" width="1" height="1" fill="#3a4978"/>
                                            <rect x="4" y="6" width="1" height="1" fill="#364576"/><rect x="5" y="6" width="1" height="1" fill="#3a4978"/>
                                            <rect x="4" y="7" width="2" height="1" fill="#001451"/>
                                            <rect x="0" y="8" width="10" height="1" fill="#001451"/><rect x="10" y="8" width="1" height="1" fill="#364576"/><rect x="12" y="8" width="6" height="1" fill="#001451"/>
                                            <rect x="0" y="9" width="10" height="1" fill="#001451"/><rect x="10" y="9" width="1" height="1" fill="#364576"/><rect x="12" y="9" width="6" height="1" fill="#001451"/>
                                            <rect x="4" y="10" width="2" height="1" fill="#001451"/>
                                            <rect x="4" y="11" width="1" height="1" fill="#364576"/><rect x="5" y="11" width="1" height="1" fill="#3a4978"/>
                                            <rect x="12" y="12" width="1" height="1" fill="#364576"/><rect x="13" y="12" width="1" height="1" fill="#3a4978"/>
                                            <rect x="12" y="13" width="2" height="1" fill="#001451"/>
                                            <rect x="0" y="14" width="10" height="1" fill="#001451"/><rect x="10" y="14" width="1" height="1" fill="#364576"/><rect x="12" y="14" width="6" height="1" fill="#001451"/>
                                            <rect x="0" y="15" width="10" height="1" fill="#001451"/><rect x="10" y="15" width="1" height="1" fill="#364576"/><rect x="12" y="15" width="6" height="1" fill="#001451"/>
                                            <rect x="12" y="16" width="2" height="1" fill="#001451"/>
                                            <rect x="12" y="17" width="1" height="1" fill="#364576"/><rect x="13" y="17" width="1" height="1" fill="#3a4978"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="lrbutton">Filter</div>
                                </div>
                            </div>
                            <ul class="clistbox">
                                <?php
                                $i = 1;
                                while ($course_row = $course_result->fetch_arraY()) {
                                    $i++;
                                    $img = !empty($course_row['square_icon']) ? $course_row['square_icon'] : 'dipimg.webp';
                                    $course_price = getCoursePrice($course_row['diplomaid']);
                                ?>
                                <li class="course-list">
                                    <div class="restop">
                                        <div class="restopimg">
                                            <img src="<?php echo $pdots; ?>images/subject/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($course_row['square_icon_alt']) ?>" title="<?= htmlspecialchars($course_row['square_icon_title']) ?>" width="248" height="140" class="rimg" <?php if ($i == 2) { ?>fetchpriority="high"<?php } ?>>
                                        </div>
                                        <div class="mrl16">
                                            <p class="m0">
                                                <a href="<?= $base_url ?>/courses/<?= htmlspecialchars($course_row['catslug']) ?>/<?= htmlspecialchars($course_row['slug']) ?>" class="bluelink18"><?php echo $course_row['name']; ?></a>
                                            </p>
                                            <p class="mt8 cfeatures">Distance learning | Tutor support via learning portal | Self paced course | Study materials available</p>
                                        </div>
                                    </div>
                                    <div class="bullet-outercon">
                                        <div>
                                            <div class="bullet-point">
                                                <div class="svgcon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21c0-4-4-7-8-7s-8 3-8 7"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                    </svg>
                                                </div>
                                                <div class="bulletxt"><?php echo number_format($course_row['admin_total_enrolled']); ?> students</div>
                                            </div>
                                            <div class="bullet-point">
                                                <div class="svgcon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="4" width="18" height="12" rx="2"/>
                                                    <path d="M2 20h20"/>
                                                    </svg>
                                                </div>
                                                <div class="bulletxt">Online</div>
                                            </div>
                                            <div class="bullet-point">
                                                <div class="svgcon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path d="M12 7v5l3 3"/>
                                                    </svg>
                                                </div>
                                                <div class="bulletxt"><?php echo $course_row['course_duration']; ?> - Self-paced</div>
                                            </div>
                                            <div class="bullet-point">
                                                <div class="svgcon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="4" y="3" width="16" height="14" rx="2"/>
                                                    <circle cx="12" cy="10" r="2"/>
                                                    <path d="M12 12v6l-2-1-2 1"/>
                                                    </svg>
                                                </div>
                                                <div class="bulletxt">Certificate(s) available</div>
                                            </div>
                                            <div class="bullet-point">
                                                <div class="svgcon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="4" width="18" height="14" rx="2"/>
                                                    <circle cx="9" cy="10" r="2"/>
                                                    <path d="M7 14c1.5-2 4.5-2 6 0"/>
                                                    </svg>
                                                </div>
                                                <div class="bulletxt">Tutor support</div>
                                            </div>
                                            <div class="bullet-point">
                                                <div class="svgcon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="16" height="16" rx="2"/>
                                                    <path d="M9 12l2 2 4-4"/>
                                                    </svg>
                                                </div>
                                                <div class="bulletxt">Examination(s) included</div>
                                            </div>
                                            <div class="bullet-point">
                                                <div class="svgcon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="8" r="5"/>
                                                    <path d="M8.5 13.5 7 21l5-3 5 3-1.5-7.5"/>
                                                    </svg>
                                                </div>
                                                <div class="bulletxt">Updated course</div>
                                            </div>
                                        </div>
                                        <div class="smben">
                                            <div class="benefitbadge greenbadge">Great service</div>
                                            <div class="benefitbadge redbadge">Highly rated</div>
                                            <div class="benefitbadge pinkbadge">Popular</div>
                                            <div class="benefitbadge greenbadge2">Trending</div>
                                        </div>
                                        <div class="pricecon">
                                            <?php if ($discount_row['amount'] && is_numeric($course_price) && !empty($course_price)) { ?>
                                            <div class="savetxt">SAVE <?php echo $discount_row['amount']; ?>%</div>
                                            <?php } ?>
                                            <div class="priceinfo">Â£<?php echo is_numeric($course_price) ? number_format($course_price) : 0; ?>
                                                <?php
                                                $prev_price = getCoursePricePrev($course_row['diplomaid']);
                                                if (!empty($prev_price)) { ?> <span class="oldprice">Â£<?php echo number_format($prev_price, 2); ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Begin last row -->
                                    <div class="lrcon">
                                        <div class="lgben">
                                            <div class="benefitbadge greenbadge">Great service</div>
                                            <div class="benefitbadge redbadge">Highly rated</div>
                                            <div class="benefitbadge pinkbadge">Popular</div>
                                            <div class="benefitbadge greenbadge2">Trending</div>
                                        </div>
                                        <div class="enqbtncon">
                                            <div class="lrbtn curp" onclick="document.location.href='<?= $base_url ?>/courses/<?= htmlspecialchars($course_row['catslug']) ?>/<?= htmlspecialchars($course_row['slug']) ?>';">
                                                <div class="lricon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                                    <path d="M3 7l9 6 9-6"/>
                                                    </svg>
                                                </div>
                                                <div class="lrbutton">View Course</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End last row -->
                                </li>
                                <?php } ?>
                            </ul>
                            <!-- Begin pagination -->
                            <?php if ($total_pages > 1) { ?>
                            <div class="pager-wrap">
                                <div class="pager">
                                    <div class="pages" id="pages">
                                        <?php if ($pagenum > 1) { ?>
                                        <span class="nav-link" id="prevBtn" onclick="submitCourseForm('pagenum', <?php echo $pagenum - 1; ?>);">
                                            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Previous
                                        </span>
                                        <?php } ?>
                                        <span class="page-link <?php echo ($pagenum == 1) ? 'active' : ''; ?>" <?php if ($pagenum != 1) { ?>onclick="submitCourseForm('pagenum', 1);"<?php } ?>>1</span>
                                        <?php if ($pagenum >= 5) { ?>
                                        <span class="ellipsis">...</span>
                                        <?php if ($pagenum == $total_pages) { ?>
                                        <span class="page-link" onclick="submitCourseForm('pagenum', <?php echo $pagenum - 2; ?>);"><?php echo $pagenum - 2; ?></span>
                                        <?php } ?>
                                        <span class="page-link" onclick="submitCourseForm('pagenum', <?php echo $pagenum - 1; ?>);"><?php echo $pagenum - 1; ?></span>
                                        <span class="page-link active"><?php echo $pagenum; ?></span>
                                        <?php if ($pagenum < $total_pages) { ?>
                                        <span class="page-link" onclick="submitCourseForm('pagenum', <?php echo $pagenum + 1; ?>);"><?php echo $pagenum + 1; ?></span>
                                        <?php
                                        }
                                        if (($total_pages - 3) >= $pagenum) { ?>
                                        <span class="ellipsis">...</span>
                                        <?php } ?>
                                        <?php if (($total_pages - 2) >= $pagenum) { ?>
                                        <span class="page-link" onclick="submitCourseForm('pagenum', <?php echo $total_pages; ?>);"><?php echo $total_pages; ?></span>
                                        <?php
                                        }} else {
                                            for ($i = 2; $i <= 5 && $i <= $total_pages; $i++) {
                                        ?>
                                        <span class="page-link <?php echo ($pagenum == $i) ? 'active' : ''; ?>" <?php if ($pagenum != $i) { ?>onclick="submitCourseForm('pagenum', <?php echo $i; ?>);"<?php } ?>><?php echo $i; ?></span>
                                        <?php
                                        }
                                        if ($total_pages == 6) { ?>
                                        <span class="page-link <?php echo ($pagenum == 6) ? 'active' : ''; ?>" <?php if ($pagenum != 6) { ?>onclick="submitCourseForm('pagenum', 6);"<?php } ?>>6</span>
                                        <?php } else if ($total_pages > 6) { ?>
                                        <span class="ellipsis">...</span>
                                        <span class="page-link <?php echo ($pagenum == $total_pages) ? 'active' : ''; ?>" <?php if ($pagenum != $total_pages) { ?>onclick="submitCourseForm('pagenum', <?php echo $total_pages; ?>);"<?php } ?>><?php echo $total_pages; ?></span>
                                        <?php
                                        }}
                                        if ($pagenum < $total_pages) {
                                        ?>
                                        <span class="nav-link" id="nextBtn" onclick="submitCourseForm('pagenum', <?php echo $pagenum + 1; ?>);">
                                            Next
                                            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <?php } ?>
                                    </div>
                                    <div class="page-size">
                                        <button class="size-btn" id="sizeBtn" type="button" aria-expanded="false" aria-controls="sizeMenu">
                                            <span id="sizeLabel" class="pctxt">Show me: <?php echo $items_count; ?></span>
                                            <svg class="caret" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M6 9l6 6 6-6" fill="currentColor"/>
                                            </svg>
                                        </button>
                                        <div class="menu" id="sizeMenu" role="menu" aria-label="Page size">
                                            <div class="menu-item <?php echo ($items_count == 10) ? 'selected' : ''; ?>" data-size="10" role="menuitem" onclick="document.getElementById('items_count').value=10;document.filterFrm.submit();">10</div>
                                            <div class="menu-item <?php echo ($items_count == 25) ? 'selected' : ''; ?>" data-size="25" role="menuitem" onclick="document.getElementById('items_count').value=25;document.filterFrm.submit();">25</div>
                                            <div class="menu-item <?php echo ($items_count == 50) ? 'selected' : ''; ?>" data-size="50" role="menuitem" onclick="document.getElementById('items_count').value=50;document.filterFrm.submit();">50</div>
                                            <div class="menu-item <?php echo ($items_count == 100) ? 'selected' : ''; ?>" data-size="100" role="menuitem" onclick="document.getElementById('items_count').value=100;document.filterFrm.submit();">100</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <!-- End pagination -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- End main section -->
        <section class="certificate">
            <div class="container">
                <div class="certificate-data">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="heading">
                                <h2>Benefits of Free Courses</h2>
                            </div>
                            <div class="left-data">
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-file-doc.png" alt="File" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>CPD & Employer Approved Qualifications</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-crown.png" alt="Crown" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>Fully Endorsed by Leading Accrediting Bodies</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bx-run.png" alt="Running person" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>Globally Recognised and Career-Focused</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-file-doc.png" alt="File" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>Flexible, Self-Paced Online Learning</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-crown.png" alt="Crown" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>Enhance Your Skills with Accredited Study</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bx-run.png" alt="Running person" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>Earn Prestigious Certificates or Diplomas</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-file-doc.png" alt="File" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>High-Quality Materials Designed by Experts</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-crown.png" alt="Crown" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h2>Affordable, Trusted, and Professionally Respected</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="img">
                                <img src="<?php echo $pdots; ?>assets/img/OHSC-Certificate.webp" alt="certificate" width="608" height="431">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="learner-sider slider-border">
            <div class="container">
                <div class="learner-carousel">
                    <div class="heading">
                        <h2>Our Learner Stories</h2>
                    </div>
                    <div class="slider">
                        <div class="row">
                            <div class="owl-carousel owl-theme learner_carousel">
                                <?php
                                $review_result = $db->query("SELECT about_course, name, surname, photo, rating FROM `review_form` WHERE LENGTH(`about_course`) < 250 AND find_in_set(3,share) ORDER BY IF(ordernumber=0, 99999999999, ordernumber) ASC");
                                while ($review_row = $review_result->fetch_array()) {
                                ?>
                                    <div class="item">
                                        <div class="card">
                                            <div class="card-data">
                                                <div class="card-head">
                                                    <div class="learner">
                                                        <div class="img">
                                                            <?php if ($review_row['photo'] && file_exists('images/form_photo/' . $review_row['photo'])) { ?>
                                                                <img src="images/form_photo/<?php echo $review_row['photo']; ?>" alt="learner" width="64" height="64" style="border-radius:50%;">
                                                            <?php } else { ?>
                                                                <div class="black-circle">
                                                                    <?php echo substr(trim($review_row['name']), 0, 1) . substr(trim($review_row['surname']), 0, 1); ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="name w-50">
                                                            <h2><?php echo $review_row['name'] . ' ' . $review_row['surname']; ?></h2>
                                                            <span>Verified Learner</span>
                                                        </div>
                                                        <div class="learn-mark">
                                                            <img src="<?php echo $pdots; ?>assets/img/learn-mark.png" alt="Learning">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-data-detail">
                                                    <div class="card-text">
                                                        <p><?php echo $review_row['about_course']; ?></p>
                                                    </div>
                                                    <div class="card-stars">
                                                        <ul>
                                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                <li>
                                                                    <img src="<?php echo $pdots; ?>assets/img/<?php echo ($i <= $review_row['rating']) ? 'Star-3.png' : 'star.png'; ?>" alt="Image of star">
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="online-course">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text">
                            <div class="para">
                                <p><?php echo $classification_row['excerpt']; ?></p>
                                <div class="text read-more-text d-none">
                                    <p><?php echo $classification_row['description']; ?></p>
                                </div>
                            </div>
                            <div class="read-more-btn">
                                <button type="button" class="toggle-btn-more" aria-expanded="false" aria-controls="more-1">Read more +</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" id="CYSec"></div>
                </div>
            </div>
        </section>
        <?php /*<section class="course-slider Categories course-categories">
            <div class="container">
                <div class="heading">
                    <h2>Explore Other Categories</h2>
                </div>
                <?php
                $catgories_result = $db->query("SELECT cname, slug, icon FROM tbl_courses WHERE IStatus = 1 AND classification_id = " . $classification_row['id'] . " ORDER BY ordernumber ASC");
                // Prepare category items array
                $categories = [];
                while ($catgories_row = $catgories_result->fetch_array()) {
                    $categories[] = $catgories_row;
                }
                // Split categories into two groups for two carousels
                $half = ceil(count($categories) / 2);
                $first_half = array_slice($categories, 0, $half);
                $second_half = array_slice($categories, $half);
                ?>
                <!-- First Owl Carousel -->
                <div class="owl-carousel owl-theme categories-slider" id="carousel1">
                    <?php foreach ($first_half as $category) { ?>
                        <div class="category-item">
                            <a href="/<?php echo $classification_row['slug'] . '/' . $category['slug']; ?>">
                                <span><?php echo $category['cname']; ?></span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <!-- Second Owl Carousel -->
                <div class="owl-carousel owl-theme categories-slider" id="carousel2">
                    <?php foreach ($second_half as $category) { ?>
                        <div class="category-item">
                            <a href="/<?php echo $classification_row['slug'] . '/' . $category['slug']; ?>">
                                <span><?php echo $category['cname']; ?></span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <!-- Shared Navigation Buttons -->
                <div class="c-nav" aria-label="Course categories slider navigation">
                    <button class="carousel-nav1" type="button" aria-label="Previous categories">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button class="carousel-nav" type="button" aria-label="Next categories">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </section>*/ ?>
        <?php
            $classification_id = intval($_GET['id']); // Sanitize input
            
            $faq_query = "SELECT * FROM tbl_faq WHERE IStatus = 1 and FIND_IN_SET($classification_id, show_in_classifications) > 0 ORDER BY ordernumber ASC";
            
            $faq_result = $db->query($faq_query);
            
            if ($faq_result->num_rows > 0) {
            ?>
                <section class="faq">
                    <div class="container">
                        <div class="heading">
                            <h2>Frequently Asked Questions (FAQs)</h2>
                        </div>
                        <div class="faq-data">
                            <div class="accordion" id="accordionExample">
                                <?php
                                $faq_index = 1;
                                while ($faq_row = $faq_result->fetch_array()) {
                                    $collapse_id = "collapse" . $faq_index;
                                ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#<?php echo $collapse_id; ?>" aria-expanded="false" aria-controls="<?php echo $collapse_id; ?>">
                                                <?php echo htmlspecialchars($faq_row['title']); ?>
                                            </button>
                                        </h2>
                                        <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p><?php echo $faq_row['detail']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                    $faq_index++;
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php
            } // End if condition (FAQ exists)
            ?>
        <?php /*<section class="blogs pb-blogs">
            <div class="container">
                <div class="heading">
                    <h2>Related Blog Posts</h2>
                </div>
                <div class="blogs-data">
                    <div class="slider">
                        <div class="row">
                            <div class="owl-carousel owl-theme blogs-carousel">
                                <?php
                                $recentBlogsSql = "SELECT * FROM blog WHERE status = '1' ORDER BY publish_date DESC LIMIT 8"; // Get latest 8 blogs
                                $recentBlogsResult = $db->query($recentBlogsSql);
                                if ($recentBlogsResult->num_rows > 0) {
                                    while ($blog = $recentBlogsResult->fetch_assoc()) { ?>
                                        <div class="item">
                                            <div class="blog-box">
                                                <div class="img">
                                                    <figure style="margin:0;">
                                                        <img src="<?php echo $pdots; ?>images/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['alt']); ?>" title="<?php echo htmlspecialchars($blog['image_title']); ?>">
                                                        <figcaption class="visually-hidden"><?php echo htmlspecialchars($blog['image_description']); ?></figcaption>
                                                    </figure>
                                                </div>
                                                <div class="text">
                                                    <div class="blog-heading">
                                                        <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                                                    </div>
                                                    <div class="read-btn">
                                                        <a href="<?php echo $base_url; ?>/OHSC-Blog/<?php echo htmlspecialchars($blog['slug']); ?>">Read More +</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php }
                                } else {
                                    echo "<p>No recent blogs available.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>*/ ?>
    </main>
    <?php include 'includes/footer-new.php'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        $("#toggleText").click(function() {
            $("#shortText").toggleClass("d-none");
            $("#fullText").toggleClass("d-none");

            if ($("#fullText").hasClass("d-none")) {
                $("#toggleText").text("Read More +");
            } else {
                $("#toggleText").text("Read Less -");
            }
        });
        $(".toggle-btn").click(function(e) {
            e.preventDefault();
            var textDiv = $(this).closest(".read-btn").prev(".text.read-more");
            textDiv.toggleClass("d-none"); // Toggle visibility
            // Change button text
            if (textDiv.hasClass("d-none")) {
                $(this).text("Read More +");
            } else {
                $(this).text("Read Less -");
            }
        });
        $(".toggle-btn-more").click(function(e) {
            e.preventDefault();
            var moreTextDiv = $(this).closest(".read-more-btn").prev(".para").find(".read-more-text");
            moreTextDiv.toggleClass("d-none"); // Toggle visibility
            // Change button text
            if (moreTextDiv.hasClass("d-none")) {
                $(this).text("Read More +");
            } else {
                $(this).text("Read Less -");
            }
        });
    });
    // Begin handling course and diploma suggestions
    let debounceTimer;
    if (window.innerWidth > 992) {
        searchInput = document.getElementById('search-input');
        resultsList = document.getElementById('results-list');
    } else {
        searchInput = document.getElementById('search-input2');
        resultsList = document.getElementById('results-list2');
    }
    window.addEventListener('resize', function () {
        if (window.innerWidth > 992) {
            searchInput = document.getElementById('search-input');
            resultsList = document.getElementById('results-list');
        } else {
            searchInput = document.getElementById('search-input2');
            resultsList = document.getElementById('results-list2');
        }
        searchInput.addEventListener('input', () => {
            var query = searchInput.value.trim();
            clearTimeout(debounceTimer);
            resultsList.innerHTML = '';
            // Wait 300ms after user stops typing to call server
            debounceTimer = setTimeout(() => {
                fetchData(query);
            }, 300);
        });
    });
    searchInput.addEventListener('input', () => {
        var query = searchInput.value.trim();
        clearTimeout(debounceTimer);
        resultsList.innerHTML = '';
        // Wait 300ms after user stops typing to call server
        debounceTimer = setTimeout(() => {
            fetchData(query);
        }, 300);
    });
    async function fetchData(query) {
        try {
            // Calling the PHP file with the search query as a parameter
            const response = await fetch(`<?php echo $pdots; ?>ajax/general.php?task=searchSuggest&q=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (data.length > 0) {
                renderResults(data);
            } else {
                resultsList.classList.add('results-hidden');
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    }
    function renderResults(data) {
        resultsList.innerHTML = '';
        data.forEach(item => {
            const div = document.createElement('div');
            div.classList.add('result-item');
            // 'course_name' and 'course_link' match the keys in our PHP JSON response
            div.textContent = item.course_name;
            div.addEventListener('click', () => {
                window.location.href = item.course_link;
            });
            resultsList.appendChild(div);
        });
        resultsList.classList.remove('results-hidden');
    }
    // End handling course and diploma suggestions
    // Begin handling subject read more
    const btn = document.getElementById("moreBtn");
    const label = document.getElementById("moreLabel");
    btn.addEventListener("click", () => {
    const isOpen = btn.getAttribute("aria-expanded") === "true";
    btn.setAttribute("aria-expanded", String(!isOpen));
    label.textContent = isOpen ? "Show more" : "Show less";
    const elements = document.getElementsByClassName("fullCon");
    for (let i = 0; i < elements.length; i++) {
        if (isOpen) {
        elements[i].classList.add("d-none");
        } else {
        elements[i].classList.remove("d-none");
        }
    }
    });
    // End handling subject read more
    // Begin paging count handling
    const sizeBtn = document.getElementById('sizeBtn');
    const sizeMenu = document.getElementById('sizeMenu');
    const sizeLabel = document.getElementById('sizeLabel');
    function closeMenu(){
        sizeMenu.classList.remove('open');
        sizeBtn.setAttribute('aria-expanded', 'false');
    }
    sizeBtn.addEventListener('click', (e) => {
        const isOpen = sizeBtn.getAttribute('aria-expanded') === 'true';
        sizeBtn.setAttribute('aria-expanded', String(!isOpen));
        sizeMenu.classList.toggle('open', !isOpen);
        e.stopPropagation();
    });
    sizeMenu.addEventListener('click', (e) => {
        const item = e.target.closest('.menu-item');
        if (!item) return;
        [...sizeMenu.querySelectorAll('.menu-item')].forEach(el => el.classList.remove('selected'));
        item.classList.add('selected');
        const size = item.getAttribute('data-size');
        sizeLabel.textContent = 'Show me: ' + size;
        closeMenu();
    });
    document.addEventListener('click', closeMenu);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMenu();
    });
    // Pagination active state (optional)
    const pages = document.getElementById('pages');
    pages.addEventListener('click', (e) => {
        const p = e.target.closest('.page-link');
        if (!p) return;
        [...pages.querySelectorAll('.page-link')].forEach(el => el.classList.remove('active'));
        p.classList.add('active');
    });
    // End paging count handling
    function showSearchSec() {
        document.getElementsByClassName('clistbox')[0].classList.add('d-none');
        document.getElementsByClassName('learner-sider')[0].classList.add('d-none');
        document.getElementsByClassName('footer')[0].classList.add('d-none');
        document.getElementById('condiv').classList.add('d-none');
        document.getElementsByClassName('certificate')[0].classList.add('d-none');
        document.getElementsByClassName('online-course')[0].classList.add('d-none');
        var elements = document.getElementsByClassName('faq');
        if (elements.length > 0) {
            elements[0].classList.add('d-none');
        }
        var elements = document.getElementsByClassName('pager-wrap');
        if (elements.length > 0) {
            elements[0].classList.add('d-none');
        }
        var elements = document.getElementsByClassName('smallnone');
        for (i = 0; i < elements.length; i++) {
            elements[i].classList.add('smallnoneTemp');
        }
        for (i = 0; i < elements.length; i++) {
            elements[i].classList.remove('smallnone');
        }
        var elements = document.getElementsByClassName('srhmodal');
        for (i = 0; i < elements.length; i++) {
            elements[i].classList.remove('d-none');
        }
        window.scrollTo(0, 0);
    }
    function hideSearchSec() {
        document.getElementsByClassName('clistbox')[0].classList.remove('d-none');
        document.getElementsByClassName('learner-sider')[0].classList.remove('d-none');
        document.getElementsByClassName('footer')[0].classList.remove('d-none');
        document.getElementById('condiv').classList.remove('d-none');
        document.getElementsByClassName('certificate')[0].classList.remove('d-none');
        document.getElementsByClassName('online-course')[0].classList.remove('d-none');
        var elements = document.getElementsByClassName('faq');
        if (elements.length > 0) {
            elements[0].classList.remove('d-none');
        }
        var elements = document.getElementsByClassName('pager-wrap');
        if (elements.length > 0) {
            elements[0].classList.remove('d-none');
        }
        var elements = document.getElementsByClassName('smallnoneTemp');
        for (i = 0; i < elements.length; i++) {
            elements[i].classList.add('smallnone');
        }
        for (i = 0; i < elements.length; i++) {
            elements[i].classList.remove('smallnoneTemp');
        }
        var elements = document.getElementsByClassName('srhmodal');
        for (i = 0; i < elements.length; i++) {
            elements[i].classList.add('d-none');
        }
    }
    // Begin code to reload on window resize
    let previousWidth = window.innerWidth;
    window.addEventListener('resize', function () {
        const currentWidth = window.innerWidth;
        if (previousWidth <= 991 && currentWidth > 991) {
            location.reload();
        }
        previousWidth = currentWidth;
    });
    // End code to reload on window resize
    </script>
    </body>
</html>
