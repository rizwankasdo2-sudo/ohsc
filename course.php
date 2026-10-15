<?php
session_start();
include("admin/classes/connection.php");
include("functions.php");
require_once('includes/functions.php');
require_once('includes/course-content-helpers.php');
if (isset($_GET['slug']) && isset($_GET['slug2'])) {
    $slug = $_GET['slug'];
    $catSlug = $_GET['slug2'];
} else {
    include('page-not-found.php');
    exit();
}
$diplomaqry = $db->query("SELECT * FROM `tbl_diploma` WHERE slug='$slug' AND IStatus = 1 AND `cid` IN (SELECT cid FROM tbl_courses WHERE IStatus = 1 AND slug = '$catSlug') ");
if ($diplomaqry->num_rows == 0) {
    // Check if course slug belongs to any active course
    $valid_slug_row = $db->query("SELECT cid FROM tbl_diploma WHERE slug = '$slug' AND IStatus = 1")->fetch_array();
    if ($valid_slug_row) {
        $valid_cat_row = $db->query("SELECT slug FROM tbl_courses WHERE IStatus = 1 AND cid = " . $valid_slug_row['cid'])->fetch_array();
        if ($valid_cat_row) {
            header('Location: ' . $base_url . '/courses/' . $valid_cat_row['slug'] . '/' . $slug, true, 301);
            exit;
        }
    }
    include('page-not-found.php');
    exit();
}
if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) {
    header('Location: ' . $base_url . '/courses/' . $catSlug . '/' . $slug, true, 301);
    exit;
}
$diplomaresult = $diplomaqry->fetch_array();
if ($diplomaresult['type'] == 3) {
    include('page-not-found-404.php');
    exit;
} else if ($diplomaresult['type'] == 1 || $diplomaresult['type'] > 3) {
    include('course_prev.php');
    exit;
}
$seo_result = $db->query("SELECT pagetitle, metadescription, metatitle, robots  FROM tbl_caourseseo WHERE diplomaid = '" . $diplomaresult['diplomaid'] . "' ");
$seo_row = $seo_result->fetch_array();
$cat_result = $db->query("SELECT * FROM tbl_courses WHERE cid = '" . $diplomaresult['cid'] . "' ");
$cat_row = $cat_result->fetch_array();
$subject_result = $db->query("SELECT course_duration, courseoverview, learning_outcomes, accreditation, coursecontent, course_level FROM tbl_subject WHERE diplomaid = '" . $diplomaresult['diplomaid'] . "' ");
$subject_row = prepareCourseSubjectRow($subject_result->fetch_array());
$course_duration_array = explode(' ', trim($subject_row['course_duration']));
$parent_cat_result = $db->query("SELECT * FROM tbl_diploma WHERE cid = '" . $diplomaresult['cid'] . "' AND type = '3' ");
$parent_cat_row = $parent_cat_result->fetch_array();
$related_blogs_result = getBlogsForRelatedDiploma($db, $diplomaresult['diplomaid']);
$related_courses_result = false;
$related_diploma_ids_raw = isset($diplomaresult['related_diploma_ids']) ? $diplomaresult['related_diploma_ids'] : '';
$related_diploma_id_list = array_filter(array_map('intval', array_map('trim', explode(',', $related_diploma_ids_raw))));
if (!empty($related_diploma_id_list)) {
    $related_courses_result = $db->query(
        "SELECT d.*, c.slug AS category_slug FROM tbl_diploma d LEFT JOIN tbl_courses c ON c.cid = d.cid WHERE d.IStatus = 1 AND d.diplomaid IN (" . implode(',', $related_diploma_id_list) . ") ORDER BY FIELD(d.diplomaid, " . implode(',', $related_diploma_id_list) . ")"
    );
}
$total_ratings_five_percent = ($diplomaresult['total_ratings'] == 0) ? 0 : ($diplomaresult['total_ratings_five'] * 100) / $diplomaresult['total_ratings'];
$total_ratings_four_percent = ($diplomaresult['total_ratings'] == 0) ? 0 : ($diplomaresult['total_ratings_four'] * 100) / $diplomaresult['total_ratings'];
$total_ratings_three_percent = ($diplomaresult['total_ratings'] == 0) ? 0 : ($diplomaresult['total_ratings_three'] * 100) / $diplomaresult['total_ratings'];
$total_ratings_two_percent = ($diplomaresult['total_ratings'] == 0) ? 0 : ($diplomaresult['total_ratings_two'] * 100) / $diplomaresult['total_ratings'];
$total_ratings_one_percent = ($diplomaresult['total_ratings'] == 0) ? 0 : ($diplomaresult['total_ratings_one'] * 100) / $diplomaresult['total_ratings'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $seo_row['pagetitle']; ?></title>
    <meta name="description" content="<?php echo strip_tags($seo_row['metadescription']); ?>" />
    <meta name="keywords" content="<?php echo strip_tags($seo_row['metatitle']); ?>" />
    <meta name="robots" content="<?php echo $seo_row['robots']; ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <?php include("includes/stylesheets.php");?>
    <!--tabs jquery stuff starts-->
    <!-- Begin course markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "<?php echo $diplomaresult['name']; ?>",
        "description": "<?php echo str_replace('"', '\'', strip_tags($subject_row['courseoverview'])); ?>",
        "image": "<?php echo $base_url; ?>/images/subject/<?php echo $diplomaresult['thumbnail']; ?>",
        "url": "<?php echo $base_url; ?>/courses/<?php echo $cat_row['slug']; ?>/<?php echo $diplomaresult['slug'];?>",
        "provider": {
            "@type": "Organization",
            "name": "Oxford Home Study Centre",
            "sameAs": ""
        },
        "hasCourseInstance": [
            {
                "@type": "CourseInstance",
                "courseMode": "Online",
                "courseWorkload": "PT<?php echo (is_numeric($course_duration_array['0'])) ? $course_duration_array['0'] : '400'; ?>H"
            }
        ],
        "offers": [{
            "@type": "Offer",
            "category": "Free"
        }]
    }
    </script>
    <!-- End course markup -->
    <?php include("includes/header.php"); ?>
    <!-- Begin form to send couse name to the portal -->
    <form method="post" name="portal_form" id="portal_form"
        action="<?php echo ($diplomaresult['added_on_portal'] == 2) ? '/lms/register' : 'https://www.elearningcollege.com/lms'; ?>">
        <?php if ($diplomaresult['added_on_portal'] == 2) { ?>
        <input type="hidden" name="portal_course_id" value="<?php echo $diplomaresult['portal_course_id']; ?>" />
        <?php } /*<input type="hidden" name="portal_course_name" value="<?php echo $diplomaresult['name']; ?>" />*/ ?>
    </form>
    <!-- End form to send couse name to the portal -->
    <main id="main-content">
        <!-- Begin purple section -->
        <div class="pbg">
            <div class="pbg_innercon">
                <!-- Begin left section -->
                <div class="pbg_leftcon">
                    <div class="d-flex">
                        <a href="<?php echo $base_url; ?>" class="breadlink gentxt_basic">Home</a>
                        <svg class="_ufjrdd" aria-hidden="true" focusable="false" viewBox="0 0 48 48" role="img"
                            aria-labelledby="ChevronRight9c218cce-b315-42ad-b3ba-6c9bc0d99467 ChevronRight9c218cce-b315-42ad-b3ba-6c9bc0d99467Desc"
                            xmlns="http://www.w3.org/2000/svg"
                            style="fill: rgb(255, 255, 255); height: 15px; width: 15px; display: flex; margin-left: 0.5rem; margin-right: 0.5rem;margin-top:4px;">
                            <polygon
                                transform="translate(23.999500, 24.000000) scale(-1, 1) translate(-23.999500, -24.000000)"
                                points="16 24 30.585 40 31.999 38.586 18.828 24 31.999 9.415 30.585 8" role="presentation">
                            </polygon>
                        </svg>
                        <?php if ($cat_row['previous_free_category'] == 0) { ?>
                        <a href="<?php echo $base_url; ?>/free-online-courses-with-certificates"
                            class="breadlink gentxt_basic">All Courses</a>
                        <svg class="_ufjrdd" aria-hidden="true" focusable="false" viewBox="0 0 48 48" role="img"
                            aria-labelledby="ChevronRight9c218cce-b315-42ad-b3ba-6c9bc0d99467 ChevronRight9c218cce-b315-42ad-b3ba-6c9bc0d99467Desc"
                            xmlns="http://www.w3.org/2000/svg"
                            style="fill: rgb(255, 255, 255); height: 15px; width: 15px; display: flex; margin-left: 0.5rem; margin-right: 0.5rem;margin-top:4px;">
                            <polygon
                                transform="translate(23.999500, 24.000000) scale(-1, 1) translate(-23.999500, -24.000000)"
                                points="16 24 30.585 40 31.999 38.586 18.828 24 31.999 9.415 30.585 8" role="presentation">
                            </polygon>
                        </svg>
                        <?php } ?>
                        <a href="<?php echo ($cat_row['previous_free_category'] == 0) ? $base_url . '/courses/' . $cat_row['slug'] : $base_url . '/' . $cat_row['slug']; ?>"
                            class="breadlink gentxt_basic"><?php echo $cat_row['cname']; ?></a>
                    </div>
                    <div class="cname_heading" style="margin-top: 20px;margin-bottom: 10px;"><?php echo $diplomaresult['name']; ?></div>
                    <div class="cratingcon">
                        <span class="fa <?php getRatingCSS(1, $diplomaresult['admin_rating']); ?> colorange"></span>
                        <span class="fa <?php getRatingCSS(2, $diplomaresult['admin_rating']); ?> colorange"></span>
                        <span class="fa <?php getRatingCSS(3, $diplomaresult['admin_rating']); ?> colorange"></span>
                        <span class="fa <?php getRatingCSS(4, $diplomaresult['admin_rating']); ?> colorange"></span>
                        <span class="fa <?php getRatingCSS(5, $diplomaresult['admin_rating']); ?> colorange"></span>
                        <span class="colorange">
                            &nbsp;&nbsp;&nbsp;<strong><?php echo number_format($diplomaresult['admin_rating'], 1, '.', ''); ?></strong></span>
                        <?php /*<span class="offertxt gentxt_basic">
                            &nbsp;<?php echo number_format($diplomaresult['total_ratings']); ?> ratings</span>*/ ?>
                    </div>
                    <div class="mrt30">
                        <button class="enrlbtn" onclick="document.portal_form.submit();">
                            <div class="enrlbtnbt">Enrol Now</div>
                            <div class="enrlbtnst mrt8">Starts Immediately</div>
                        </button>
                    </div>
                    <div class="mrt20 txtunbtn gentxt_basic">
                        <strong><?php echo number_format($diplomaresult['admin_total_enrolled']); ?></strong> already
                        enrolled
                    </div>
                </div>
                <!-- End left section -->
            </div>
            <!-- Begin white div for creating right section of purple section -->
            <div class="whitecoverdiv" style="display:none;"> &nbsp;</div>
            <!-- Begin white div for creating right section of purple section -->
        </div>
        <!-- End purple section -->
        <!-- Begin E-Learning Section -->
        <div class="container cdmaincon">
            <div class="row">
                <div class="col-md-7">
                    <div class="courses-details-left">
                        <div class="courese-over-view">
                            <div class="course-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a class="newitem gentxt" 
                                        href="#CourseOverview" 
                                        data-toggle="tab" 
                                        role="tab" 
                                        aria-controls="CourseOverview" 
                                        aria-selected="false">
                                        Course Overview
                                        </a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a class="newitem gentxt" 
                                        href="#LearningOutcomes" 
                                        data-toggle="tab" 
                                        role="tab" 
                                        aria-controls="LearningOutcomes" 
                                        aria-selected="false">
                                        Course Syllabus
                                        </a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a class="newitem gentxt" 
                                        href="#CourseAccreditation" 
                                        data-toggle="tab" 
                                        role="tab" 
                                        aria-controls="CourseAccreditation" 
                                        aria-selected="true">
                                        Certification
                                        </a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active gentxt" id="CourseOverview">
                                        <?php echo $subject_row['courseoverview']; ?>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade gentxt" id="LearningOutcomes">
                                        <?php echo $subject_row['learning_outcomes']; ?>
                                    </div>
                                    <div role="tabpanel" class="tab-pane gentxt" id="CourseAccreditation">
                                        <?php echo $subject_row['accreditation']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Begin remaining sections related to left column -->
                    <div style="background: #f6f6f6;">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="site-info-herder">
                                        <h2 class="gentxt_fbasic">Course Content</h2>
                                        <div class="header-divider">
                                            <div class="head-divider-box"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="faq-sec">
                                        <div class="panel-group" id="accordion">
                                            <div class="panel panel-default">
                                                <div class="panel-heading" id="headingOne"
                                                    onclick="setHeights('collapseOne25', 'icon25')">
                                                    <h2>
                                                        <div style="display:flex;" class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion"
                                                                href="#collapseOne25" aria-label="View Topics Included in the Course"> <i
                                                                    class="more-less glyphicon glyphicon-plus"
                                                                    id="icon25"></i></a><a role="button"
                                                                data-toggle="collapse" data-parent="#accordion"
                                                                href="#collapseOne25" class="gentxt" aria-label="Browse Course Topics"> Topics Covered
                                                                in the Course </a>
                                                            <div></div>
                                                        </div>
                                                    </h2>
                                                </div>
                                                <div id="collapseOne25" class="panel-collapse collapse" role="tabpanel"
                                                    aria-labelledby="headingOne"
                                                    style="max-height:0px;overflow:hidden;margin-top:-6px;">
                                                    <div class="panel-body gentxt">
                                                        <?php echo $subject_row['coursecontent']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End student feedback section -->
                    <!-- Begin how it works section -->
                    <div class="container-fluid">
                        <div class="col-12">
                            <div class="row text-center">
                                <h2 class="mrgnbt48 gentxt_fbasic">How It Works</h2>
                                <div class="header-divider">
                                    <div class="head-divider-box"></div>
                                </div>
                            </div>
                            <div>&nbsp; </div>
                            <div class="stepflex">
                                <div class="stepflexinnercon">
                                    <div class="stepflexinnernum">1</div>
                                    <p class="stepflexinnerdes gentxt">Build your knowledge through clear and accessible online learning.</p>
                                </div>
                                <div class="stepflexinnercon">
                                    <div class="stepflexinnernum">2</div>
                                    <p class="stepflexinnerdes gentxt">Complete the assignments and achieve the required marks.
                                    </p>
                                </div>
                                <div class="stepflexinnerconlast">
                                    <div class="stepflexinnernum">3</div>
                                    <p class="stepflexinnerdes gentxt">Choose an optional certificate after completing the course.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End how it works section -->
                    <!-- Begin why get certified section -->
                    <div class="container-fluid">
                        <div class="col-12">
                            <div class="row text-center">
                                <h2 class="mrgnbt48 gentxt_fbasic">Why Choose a Certificate?</h2>
                                <div class="header-divider">
                                    <div class="head-divider-box"></div>
                                </div>
                            </div>
                            <div>&nbsp; </div>
                            <div class="row">
                                <div class="col-md-4 text-center"><img
                                        src="<?php echo $base_url; ?>/images/endorsed-certificate.png" width="172" height="197" alt="Certificate Icon"></div>
                                <div class="col-md-8">
                                    <button class="wgcaccordion gentxt" id="wgcaccordion1"
                                        onclick="setWhyCertifiedHeight('wgcpanel1', 'wgcaccordion1');"><strong>Record Your Achievement</strong></button>
                                    <div class="wgcpanel gentxt" id="wgcpanel1">
                                        <p>An optional certificate provides a clear record of successful course completion. Add it to your learning portfolio and share it where relevant.</p>
                                    </div>
                                    <button class="wgcaccordion gentxt" id="wgcaccordion2"
                                        onclick="setWhyCertifiedHeight('wgcpanel2', 'wgcaccordion2');"><strong>Demonstrate Your Commitment</strong></button>
                                    <div class="wgcpanel gentxt" id="wgcpanel2">
                                        <p>Completing the course and its assessments shows your commitment to developing subject knowledge through focused, independent learning.</p>
                                    </div>
                                    <button class="wgcaccordion gentxt" id="wgcaccordion3"
                                        onclick="setWhyCertifiedHeight('wgcpanel3', 'wgcaccordion3');"><strong>Support Your Next Steps</strong></button>
                                    <div class="wgcpanel gentxt" id="wgcpanel3">
                                        <p>Use your new knowledge to support further study, workplace development or personal goals. A certificate records completion but does not guarantee employment or professional status.</p>
                                    </div>
                                    <div>&nbsp; </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End why get certified section -->
                    <!-- Begin FAQs section -->
                    <?php
                    $faqs_result = getFaqsForRelatedDiploma($db, $diplomaresult['diplomaid']);
                    if ($faqs_result && $faqs_result->num_rows > 0) { ?>
                    <div class="container-fluid">
                        <div class="col-12">
                            <div class="row text-center">
                                <h2 class="mrgnbt48 gentxt_fbasic">Frequently Asked Questions (FAQs)</h2>
                                <div class="header-divider">
                                    <div class="head-divider-box"></div>
                                </div>
                            </div>
                            <div>&nbsp; </div>
                            <div class="custom-accordion" id="faqAccordion">
                                <?php
                                $faq_index = 1;
                                while ($faq_row = $faqs_result->fetch_assoc()) {
                                    $collapse_id = "faqItem" . $faq_index;
                                ?>
                                <div class="custom-accordion-item">
                                    <button class="custom-accordion-header" data-target="<?php echo $collapse_id; ?>">
                                        <?php echo strip_tags($faq_row['title']); ?>
                                        <span class="custom-accordion-icon"></span>
                                    </button>

                                    <div id="<?php echo $collapse_id; ?>" class="custom-accordion-body">
                                        <div class="custom-accordion-content">
                                            <?php echo strip_tags($faq_row['detail']); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php $faq_index++; } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- End FAQs section -->
                    <!-- Begin related courses section -->
                    <?php if ($related_courses_result && $related_courses_result->num_rows > 0) { ?>
                    <div class="container-fluid">
                        <div class="col-12">
                            <div class="row text-center">
                                <h2 class="mrgnbt48 gentxt_fbasic">Related Courses</h2>
                                <div class="header-divider">
                                    <div class="head-divider-box"></div>
                                </div>
                            </div>
                            <div>&nbsp; </div>
                            <?php
                                    $i = 0;
                                    while ($related_row = $related_courses_result->fetch_array()) {
                                        $i++;
                                        $related_category_slug = !empty($related_row['category_slug']) ? $related_row['category_slug'] : $cat_row['slug'];
                                    ?>
                            <!-- Begin first course -->
                            <div class="row <?php echo ($i == 1) ? '' : 'mrtb20'; ?>">
                                <div class="pbg_related">
                                    <div class="d-flex" style="text-align:center;justify-content:center;">
                                        <div class="pbg_leftcon_related">
                                            <h2 class="cname_heading_related gentxt_fbasic">
                                                <?php echo $related_row['name']; ?></h2>
                                            <div class="cratingcon">
                                                <span
                                                    class="fa <?php getRatingCSS(1, $related_row['admin_rating']); ?> colorange"></span>
                                                <span
                                                    class="fa <?php getRatingCSS(2, $related_row['admin_rating']); ?> colorange"></span>
                                                <span
                                                    class="fa <?php getRatingCSS(3, $related_row['admin_rating']); ?> colorange"></span>
                                                <span
                                                    class="fa <?php getRatingCSS(4, $related_row['admin_rating']); ?> colorange"></span>
                                                <span
                                                    class="fa <?php getRatingCSS(5, $related_row['admin_rating']); ?> colorange"></span>
                                                <span class="colorange gentxt_basic">
                                                    &nbsp;&nbsp;&nbsp;<strong><?php echo $related_row['admin_rating']; ?></strong></span>
                                                <?php /*<span class="offertxt gentxt_basic">
                                                    &nbsp;<?php echo number_format($related_row['total_ratings']); ?>
                                                    ratings</span>*/ ?>
                                            </div>
                                            <div class="mrt30">
                                                <button class="enrlbtn_related gentxt"
                                                    onclick="window.location.href='<?php echo $base_url . '/courses/' . $related_category_slug . '/' . $related_row['slug']; ?>';">
                                                    <div class="enrlbtnbt gentxt">View Detail</div>
                                                </button>
                                            </div>
                                            <div class="mrt20 txtunbtn gentxt_basic">
                                                <strong><?php echo number_format($related_row['admin_total_enrolled']); ?></strong>
                                                already enrolled
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End first course -->
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- End related courses section -->
                    <!-- End remaining sections related to left column -->
                </div>
                <div class="col-md-1"> &nbsp;</div>
                <div class="col-md-4 rightfsec">
                    <div class="courses-details-ryt">
                        <div class="clearfix">
                            <!-- Begin benefits section -->
                            <div class="d-flex flex-col bencon">
                                <?php
                                        $benefit_result = $db->query("SELECT heading, description, img FROM benefits ORDER BY sort_order ASC");
                                        while ($benefit_row = $benefit_result->fetch_array()) {
                                            $benefit_heading = trim((string) $benefit_row['heading']);
                                            if ($benefit_heading === '') {
                                                continue;
                                            }
                                            list($temp_width, $temp_height) = getimagesize(dirname(__FILE__) . '/images/nd/benefits/' . $benefit_row['img']);
                                        ?>
                                <!-- Begin single benefit section -->
                                <div class="d-flex mrb35">
                                    <div class="benleft">
                                        <img src="<?php echo $base_url; ?>/images/nd/benefits/<?php echo $benefit_row['img']; ?>"
                                            width="<?php echo $temp_width; ?>" height="<?php echo $temp_height; ?>" alt="" />
                                    </div>
                                    <div class="benright">
                                        <div class="benhead"><?php echo htmlspecialchars(normalizeCourseUkSpelling($benefit_heading), ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="bendes"><?php echo prepareCourseHtml($benefit_row['description']); ?></div>
                                    </div>
                                </div>
                                <!-- End single benefit section -->
                                <?php } ?>
                            </div>
                            <!-- End benefits section -->
                            <!-- Begin Reviews Section -->
                            <div class="total-reting">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="reviews-title gentxt_fbasic"> Student Feedback</h3>
                                    </div>
                                    <div class="col-xs-12 col-md-6 text-center">
                                        <h2 class="rating-num gentxt_fbasic">
                                            <?php echo number_format($diplomaresult['admin_rating'], 1, '.', ''); ?></h2>
                                        <div class="rating">
                                            <div class="rating-sec2">
                                                <span
                                                    class="fa <?php getRatingCSS(1, $diplomaresult['admin_rating']); ?> checked"></span><span
                                                    class="fa <?php getRatingCSS(2, $diplomaresult['admin_rating']); ?> checked"></span><span
                                                    class="fa <?php getRatingCSS(3, $diplomaresult['admin_rating']); ?> checked"></span><span
                                                    class="fa <?php getRatingCSS(4, $diplomaresult['admin_rating']); ?> checked"></span><span
                                                    class=" fa <?php getRatingCSS(5, $diplomaresult['admin_rating']); ?> checked"></span>
                                            </div>
                                        </div>
                                        <div class="num-of-review">
                                            <span style="font-size:14px;" class="gentxt_fbasic"><strong>Course
                                                    Rating</strong></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row rating-desc" style="display:none;">
                                            <div class="col-xs-3 col-md-3 text-right gentxt_fbasic">
                                                <span class="glyphicon glyphicon-star"></span>5
                                            </div>
                                            <div class="col-xs-8 col-md-9">
                                                <div class="progress progress-striped">
                                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: <?php //echo number_format($total_ratings_five_percent, 1, '.', ''); ?>%">
                                                        <span
                                                            class="sr-only cb gentxt_fbasic"><?php //echo number_format($total_ratings_five_percent, 1, '.', ''); ?>%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end 5 -->
                                            <div class="col-xs-3 col-md-3 text-right gentxt_fbasic">
                                                <span class="glyphicon glyphicon-star"></span>4
                                            </div>
                                            <div class="col-xs-8 col-md-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: <?php echo number_format($total_ratings_four_percent, 1, '.', ''); ?>%">
                                                        <span
                                                            class="sr-only cb gentxt_fbasic"><?php echo number_format($total_ratings_four_percent, 1, '.', ''); ?>%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end 4 -->
                                            <div class="col-xs-3 col-md-3 text-right gentxt_fbasic">
                                                <span class="glyphicon glyphicon-star"></span>3
                                            </div>
                                            <div class="col-xs-8 col-md-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-info" role="progressbar"
                                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: <?php echo number_format($total_ratings_three_percent, 1, '.', ''); ?>%">
                                                        <span
                                                            class="sr-only cb gentxt_fbasic"><?php echo number_format($total_ratings_three_percent, 1, '.', ''); ?>%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end 3 -->
                                            <div class="col-xs-3 col-md-3 text-right gentxt_fbasic">
                                                <span class="glyphicon glyphicon-star"></span>2
                                            </div>
                                            <div class="col-xs-8 col-md-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: <?php echo number_format($total_ratings_two_percent, 1, '.', ''); ?>%">
                                                        <span
                                                            class="sr-only cb gentxt_fbasic"><?php echo number_format($total_ratings_two_percent, 1, '.', ''); ?>%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end 2 -->
                                            <div class="col-xs-3 col-md-3 text-right gentxt_fbasic">
                                                <span class="glyphicon glyphicon-star"></span>1
                                            </div>
                                            <div class="col-xs-8 col-md-9">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-danger" role="progressbar"
                                                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"
                                                        style="width: <?php echo number_format($total_ratings_one_percent, 1, '.', ''); ?>%">
                                                        <span
                                                            class="sr-only cb gentxt_fbasic"><?php echo number_format($total_ratings_one_percent, 1, '.', ''); ?>%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end 1 -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Reviews Section -->
                            <div class="col-md-12">
                                <h3 class="reviews-title gentxt_fbasic"> Course Info </h3>
                            </div>
                            <div class="col-md-12 courses-table cleafix">
                                <table class="table table-bordered" style="font-size:14px;">
                                    <tbody>
                                        <tr style="background:#f9f9f9;">
                                            <td class="gentxt_fbasic"><strong>Course Level</strong></td>
                                            <td class="gentxt_fbasic"><?php echo $subject_row['course_level']; ?></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" class="gentxt_fbasic"><strong>Awarding Body</strong></td>
                                            <td width="50%" class="gentxt_fbasic">OHSC</td>
                                        </tr>
                                        <tr style="background:#f9f9f9;display:none;">
                                            <td class="gentxt_fbasic"><strong>Study Method</strong></td>
                                            <td class="gentxt_fbasic">Online</td>
                                        </tr>
                                        <tr>
                                            <td class="gentxt_fbasic"><strong>Course Duration</strong></td>
                                            <td class="gentxt_fbasic"><?php echo $subject_row['course_duration']; ?></td>
                                        </tr>
                                        <tr style="background:#f9f9f9;">
                                            <td class="gentxt_fbasic"><strong>Entry Requirements</strong></td>
                                            <td class="gentxt_fbasic">Open to All</td>
                                        </tr>
                                        <tr>
                                            <td class="gentxt_fbasic"><strong>Start Date</strong></td>
                                            <td class="gentxt_fbasic">Ongoing</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="search-btn btn-cd" align="center">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="#">
                                    <input type="button" class="btn join-button gentxt_fbasic" value="Start Your Course Now"
                                        onclick="window.location.href='https://www.elearningcollege.com/join';">
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php /*<div align="center">
                        <a href="https://www.learnartificialintelligence.ai/" aria-label="Visit LAI Website"><img src="<?php echo $base_url; ?>/images/LAI_side_new.webp" width="269" height="269" alt="LAI Website" border="0" /></a>
                    </div>*/ ?>
                </div>
            </div>
            <?php include dirname(__FILE__) . '/includes/related-blogs-section.php'; ?>
        </div>
        <!-- End E-Learning Section -->
    </main>
    <?php include('includes/footer.php'); ?>
    <?php include dirname(__FILE__) . '/includes/related-blogs-assets.php'; ?>
    <script>
    function adjustBenefitsMargin() {
        var curwidth = $(window).width();
        if (curwidth > 974) {
            var double_height = $('.pbg').outerHeight() * 2;
            var newmrgn = $('.pbg').outerHeight() - double_height;
            $('.bencon').css('margin-top', newmrgn);
        } else {
            $('.bencon').css('margin-top', 0);
        }
    }
    adjustBenefitsMargin();
    $(window).resize(function() {
        adjustBenefitsMargin();
    });
    // Begin Bootstrap 5 style Accordion
    document.addEventListener("DOMContentLoaded", function () {
        const accordion = document.querySelectorAll(".custom-accordion-header");
        accordion.forEach(header => {
            header.addEventListener("click", function () {
                const targetId = this.getAttribute("data-target");
                const target = document.getElementById(targetId);
                // Close all other accordions
                document.querySelectorAll(".custom-accordion-body").forEach(body => {
                    if (body !== target) {
                        body.style.maxHeight = null;
                    }
                });
                document.querySelectorAll(".custom-accordion-header").forEach(h => {
                    if (h !== this) h.classList.remove("active");
                });
                // Toggle selected accordion
                if (target.style.maxHeight) {
                    target.style.maxHeight = null;
                    this.classList.remove("active");
                } else {
                    target.style.maxHeight = target.scrollHeight + "px";
                    this.classList.add("active");
                }
            });
        });
    });
    // End Bootstrap 5 style Accordion
    </script>
    </body>
</html>
