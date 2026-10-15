<?php
header("X-Robots-Tag: noindex, nofollow", true);
include "../admin/classes/connection.php";
require_once('../includes/functions.php');
require_once('../includes/blog-landing-helpers.php');
$discount_result = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ");
$discount_row = $discount_result->fetch_array();
// Begin showing why choose us
if ($_GET['task'] == 'show_why_choose_us') {
    echo '<div class="container">
    <div class="row d-flex">
        <div class="col-md-5 order-md-last wrap-about wrap-about d-flex align-items-stretch">
            <div class="img ldright" style="background-image: url(images/nd/about.webp); border"></div>
        </div>
        <div class="col-md-7 wrap-about pr-md-4 ftco-animate fadeInUp ftco-animated">
            <h2 class="mb-4 gentxt_fbasic">Why Choose Us</h2>
            <p class="gentxt">At Oxford Home Study Centre, we offer an extensive range of fully endorsed courses in a variety of popular subject areas. But what makes us different from most conventional distance learning specialists?
</p>
            <div class="row mt-5">
                <div class="col-lg-6">
                    <div class="services-2 d-flex">
                        <div class="icon mt-2 d-flex justify-content-center align-items-center"><span><img src="images/nd/icons/quality.png" height="32" width="32" alt="" /></span></div>
                        <div class="text pl-3">
                            <h3 class="gentxt_fbasic">Premium Quality Courses</h3>
                            <p class="gentxt">Every course at Oxford Home Study Centre is created by a team of noted academics and experienced industry experts. This enables us to maintain the highest possible quality standards and provide the ultimate online learning experience for every candidate.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services-2 d-flex">
                        <div class="icon mt-2 d-flex justify-content-center align-items-center"><span><img src="images/nd/icons/valueformoney.png" height="32" width="32" alt="" /></span></div>
                        <div class="text pl-3">
                            <h3 class="gentxt_fbasic">Unbeatable Value for Money</h3>
                            <p class="gentxt">Upon enrolment, every learner gains access to our dynamic digital learning platform and all the materials needed to complete their course. All study aids and digital textbooks are included in the initial course fee for unbeatable value for money.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services-2 d-flex">
                        <div class="icon mt-2 d-flex justify-content-center align-items-center"><span><img src="images/nd/icons/support.png" height="32" width="32" alt="" /></span></div>
                        <div class="text pl-3">
                            <h3 class="gentxt_fbasic">Expert Tutor Support</h3>
                            <p class="gentxt">Our extensive bank of experienced tutors provides every candidate with the support and guidance needed to gain maximum value from their studies. Whatever you need and whenever you need us, we\'re standing by to help.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services-2 d-flex">
                        <div class="icon mt-2 d-flex justify-content-center align-items-center"><span><img src="images/nd/icons/flexibility.png" height="32" width="32" alt="" /></span></div>
                        <div class="text pl-3">
                            <h3 class="gentxt_fbasic">Total Flexibility</h3>
                            <p class="gentxt">We impose no deadlines or time restrictions whatsoever on any of our courses. Oxford Home Study Centre welcomes learners from all over the world, allowing candidates to study from any location at a time and pace to suit their lifestyle.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services-2 d-flex">
                        <div class="icon mt-2 d-flex justify-content-center align-items-center"><span><img src="images/nd/icons/break.png" height="32" width="32" alt="" /></span></div>
                        <div class="text pl-3">
                            <h3 class="gentxt_fbasic">Optional Study Breaks</h3>
                            <p class="gentxt">If you\'d like to take any number of breaks at any time during your course, you\'re free to do so - no questions asked. However busy your lifestyle, you\'ll find it easy and enjoyable to enhance your education with Oxford Home Study Centre.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services-2 d-flex">
                        <div class="icon mt-2 d-flex justify-content-center align-items-center"><span><img src="images/nd/icons/year.png" height="32" width="32" alt="" /></span></div>
                        <div class="text pl-3">
                            <h3 class="gentxt_fbasic">Year-Round Enrolment</h3>
                            <p class="gentxt">For maximum convenience and accessibility, our complete catalogue of courses is open for enrolment throughout the year. When you\'re ready to get started, so are we - sign up online in just a few clicks and get your success story started!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services-2 d-flex">
                        <div class="icon mt-2 d-flex justify-content-center align-items-center"><span class="flaticon-diploma"></span></div>
                        <div class="text pl-3">
                            <h3 class="gentxt_fbasic">Fully Endorsed Course</h3>
                            <p class="gentxt">By completing a course with Oxford Home Study Centre, you\'ll have the chance to boost your CV and your career prospects with a fully endorsed certificate or diploma. Whatever your long-term goals, you\'ll be a step closer to reaching them with the help of OHSC.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
}
// End showing why choose us
// Begin showing blog section
if ($_GET['task'] == 'blogsection') {
    $send = '<div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section ftco-animate fadeInUp ftco-animated">
                <h2 class="mb-4 gentxt_fbasic"><span>Recent</span> Blog</h2>
            </div>
        </div>
        <div class="row">';
    $blog_result = $db->query("SELECT title, excerpt, image, publish_date, slug FROM blog WHERE " . getBlogLandingPublishedStatusCondition() . " ORDER BY id DESC LIMIT 0, 3");
    while ($blog_row = $blog_result->fetch_array()) {
        $send .= '<div class="col-md-6 col-lg-4 ftco-animate fadeInUp ftco-animated">
                <div class="blog-entry">
                    <a href="' . $base_url . '/OHSC-Blog/' . $blog_row['slug'] . '" class="block-20 d-flex align-items-end" style="background-image: url(\'images/blog/' . $blog_row['image'] . '\');">
                        <div class="meta-date text-center p-2 gentxt_fbasic">
                            <span class="day">' . date('d', strtotime($blog_row['publish_date'])) . '</span>
                            <span class="mos">' . date('M', strtotime($blog_row['publish_date'])) . '</span>
                            <span class="yr">' . date('Y', strtotime($blog_row['publish_date'])) . '</span>
                        </div>
                    </a>
                    <div class="text bg-white p-4">
                        <h3 class="heading"><a href="' . $base_url . '/OHSC-Blog/' . $blog_row['slug'] . '" class="gentxt_fbasic">' . $blog_row['title'] . '</a></h3>
                        <p class="gentxt">' . substr(strip_tags($blog_row['excerpt']), 0, 60) . '...</p>
                        <div class="d-flex align-items-center mt-4">
                            <p class="mb-0 palncen"><a href="' . $base_url . '/OHSC-Blog/' . $blog_row['slug'] . '" class="btn2 btn-primary2 gentxt_fbasic">Read More &rarr;</a></p>
                        </div>
                    </div>
                </div>
            </div>';
    }
    $send .= '</div>
    </div>';
    echo $send;
}
// End showing blog section
// Begin showing logos section
if ($_GET['task'] == 'show_logos_section') {
    echo '<div class="row certificate-row">
    <div class="col-6 col-sm-4 col-lg-2 certificate-col">
        <div class="certificate-container">
            <a href="https://www.cpdcourses.com/" class="gtmCouHomeCertLogo">
                <img src="images/nd/cpd_logo.webp" alt="CPD Logo" class="img-fluid lazyload" width="79" height="84">
            </a>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2 certificate-col">
        <div class="certificate-container">
            <a href="https://www.abcawards.co.uk/" class="gtmCouHomeCertLogo" >
                <img src="images/nd/abc.webp" alt="ABC Awards & Certa Awards Logo" class="img-fluid lazyload" width="150" height="80">
            </a>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2 certificate-col">
        <div class="certificate-container">
            <a href="https://www.bolc.co.uk/" class="gtmCouHomeCertLogo">
                <img src="images/nd/BOLC_logo.webp" alt="BOLC Logo" class="img-fluid lazyload" width="119" height="75">
            </a>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2 certificate-col">
        <div class="certificate-container">
            <a href="https://www.ukrlp.co.uk/" class="gtmCouHomeCertLogo">
                <img src="images/nd/ukrlp-.webp" alt="UKRLP Logo" class="img-fluid lazyload" width="270" height="60">
            </a>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2 certificate-col">
        <div class="certificate-container">
            <a href="https://www.ocrmglobal.com/" class="gtmCouHomeCertLogo">
                <img src="images/nd/ocrm_logo.webp" alt="OCRM Logo" class="img-fluid lazyload" width="299" height="158">
            </a>
        </div>
    </div>
    <div class="col-6 col-sm-4 col-lg-2 certificate-col">
        <div class="certificate-container">
            <a href="https://www.stafftrainingsolutions.co.uk/" class="gtmCouHomeCertLogo">
                <img src="images/nd/sts_logo.webp" alt="STS Logo" class="img-fluid lazyload" width="149" height="70">
            </a>
        </div>
    </div>
</div>';
}
// End showing logos section
// Begin showing registration section
if ($_GET['task'] == 'show_registration') {
    $category_array = array();
    $i = 0;
    $category_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE IStatus = '1' GROUP BY cname ORDER BY ordernumber ASC");
    while ($category_row = $category_result->fetch_array()) {
        $category_array[$i]['cid'] = $category_row['cid'];
        $category_array[$i]['cname'] = $category_row['cname'];
        $i++;
    }
    $send = '<div class="container-fluid">
    <div class="row row-eq-height">
        <div class="col-lg-6 nopadding">
            <div class="register_section d-flex flex-column align-items-center justify-content-center">
                <div class="register_content text-center">
                    <p class="register_title registerTitleHd gentxt_fbasic">Summer Mega Sale - Register now and get <span>' . $discount_row['amount'] . '%</span> discount on all Courses</p>
                    <div class="button button_1 register_button mx-auto trans_200 gentxt_fbasic"><a href="/applyonline">register now</a></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 nopadding">
            <div class="search_section d-flex flex-column align-items-center justify-content-center">
                <div class="search_background"></div>
                <div class="search_content text-center">
                    <p class="search_title searchTitleHd gentxt_fbasic">Search for your course</p>
                    <form id="search_form" class="search_form" action="/search" method="post">
                        <input id="course_name" name="course_name" class="input_field search_form_name gentxt" type="text" placeholder="Course Name" autocomplete="off">
                        <select id="cid" name="cid" class="input_field search_form_category gentxt">
                            <option value="">Select Category</option>';
    for ($i = 0; $i < count($category_array); $i++) {
        $send .= '<option value="' . $category_array[$i]['cid'] . '">' . $category_array[$i]['cname'] . '</option>';
    }
    $send .= '</select>
                        <select id="level" name="level" class="input_field search_form_degree gentxt">
                            <option value="">Select Course Level</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                            <option value="3">Level 3</option>
                            <option value="4">Level 4</option>
                            <option value="5">Level 5</option>
                        </select>
                        <button id="search_submit_button" type="submit" class="search_submit_button trans_200 gentxt_fbasic" value="Submit">search course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>';
    echo $send;
}
// End showing registration section
// Begin displaying main menu
if ($_GET['task'] == 'show_main_menu') {?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Main Menu</title>
    </head>
    <body>
        <div>
            <?php if (empty($_GET['dontshowimg'])) {?>
            <div class="modal-header" style="background-color:#E8E8E8;">
                <div style="position: relative; width: 100%;">
                    <div style="display:table-row">
                        <div style="display:table-cell;vertical-align:middle;"><img id="popheadicon" width="70" height="70"
                                alt="View Popular Courses" src="<?php echo $base_url ?>/images/nd/Forensic Accounting5581568807054.png" /></div>
                        <div style="display:table-cell;vertical-align:middle;">
                            <h1 class="modal-title" style="display:inline-block;font-size:14px;" id="popheadname">Main Menu</h1>
                        </div>
                    </div>
                </div>
                <a href="#" class="close" rel="modal:close">X</a>
            </div>
            <?php } ?>
            <div class="modal-body">
                <ul class="child-subjects list-unstyled twocol">
                    <li><a href="<?php echo $base_url ?>/OHSC-Blog">Blog</a></li>
                    <hr />
                    <li><a href="<?php echo $base_url ?>/contact">Contact Us</a></li>
                    <hr />
                    <?php
                    // TOOLS: mobile menu lists published tools. The module lives in /tools.
                    require_once dirname(__FILE__) . '/../tools/includes/registry.php';
                    $ohscMobileTools = (isset($db) && $db instanceof mysqli) ? ohsc_tools_active($db) : array();
                    ?>
                    <li><a href="<?php echo $base_url ?>/tools/">Tools</a></li>
                    <?php foreach ($ohscMobileTools as $ohscMobileTool) { ?>
                    <li><a href="<?php echo $base_url . '/tools/' . $ohscMobileTool['slug'] . '/'; ?>"><?php echo ohsc_h($ohscMobileTool['title']); ?></a></li>
                    <?php } ?>
                    <hr />
                    <li><a href="<?php echo $base_url ?>/lms/">Login</a></li>
                    <hr />
                    <li><a href="<?php echo $base_url ?>/applyonline">Sign Up</a></li>
                    <hr />
                    <li><strong>Courses:<strong></li>
                    <hr />
                    <?php
        $cat_result = $db->query("SELECT cname, slug FROM tbl_courses WHERE IStatus = 1 AND previous_free_category = 0 ORDER BY cname ASC");
            while ($cat_row = $cat_result->fetch_array()) {
                ?>
                    <li><a href="<?php echo $base_url . '/courses/' . $cat_row['slug']; ?>"><?php echo $cat_row['cname']; ?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </body>
</html>
<?php
}
// End displaying main menu

// Begin adding Google Analytics
if ($_GET['task'] == 'addGA') {
?>
    <!-- google analytics code start -->
    <script async="async"  type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-69791928-1', 'auto');
    ga('send', 'pageview');
    </script>
    <script></script>
    <!-- google analytics code end -->
<?php
}
// End adding Google Analytics

// Begin displaying remaing free courses
// ORIGINAL: if ($_POST['task'] == 'loadRestOfFreeCourses') {
// LOCAL CHANGE: the homepage calls this file without a POST task, which warned "Undefined array key task" on PHP 8.
if (isset($_POST['task']) && $_POST['task'] == 'loadRestOfFreeCourses') {
    $cid = $_POST['cid'];
    $catdip_row = $db->query("SELECT td.cid, td.slug, tc.slug AS catslug, tc.cname, tc.icon FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.type = '3' AND tc.cid = $cid AND td.cid = tc.cid ORDER BY tc.ordernumber ASC ")->fetch_array();
    $diploma_result = $db->query("SELECT name, slug, square_icon, admin_rating, admin_total_enrolled FROM tbl_diploma WHERE cid = '" . $catdip_row['cid'] . "' AND type = '2' LIMIT 3, 999999999999 ");
    while ($diploma_row = $diploma_result->fetch_array()) {
        $square_icon = ($diploma_row['square_icon']) ? $diploma_row['square_icon'] : 'dipimg.webp';
    ?>
    <div class="col-xs-12 col-sm-6 col-lg-4 myresp inrow">
        <a href="<?php echo $base_url; ?>/courses/<?php echo $catdip_row['catslug']; ?>/<?php echo $diploma_row['slug']; ?>" style="display:block;">
            <div class="maincatdiv catdivadj">
                <div><img src="images/subject/<?php echo $square_icon; ?>" width="70" height="70" style="border-radius:12px;" /></div>
                <div class="rbencon">
                    <div class="text-left pdl15 rbenconch"><?php echo (strlen($diploma_row['name']) <= 36) ? $diploma_row['name'] : substr($diploma_row['name'], 0, 33) . '...'; ?></div>
                    <div class="d-flex">
                        <div class="pdl15 freecr"><?php echo number_format($diploma_row['admin_total_enrolled']); ?> Graduates</div>
                        <div class="text-right freecrr">
                            <span class="freecrrsp"><?php echo $diploma_row['admin_rating']; ?></span>
                            <span class="fa fa-star freecrrspstar"></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php
    }
}
// End displaying remaing free courses

// Begin showing FAQs on home page
if ($_GET['task'] == 'show_home_faqs') {
?>
<p class="clheading txtlft">FAQs</p>
<?php
$faq_result = $db->query("SELECT * FROM tbl_faq WHERE IStatus = 1 ORDER BY ordernumber ASC LIMIT 0, 6");
while ($faq_row = $faq_result->fetch_array()) {
    ?>
<p class="faqq txtlft"><?php echo $faq_row['title']; ?></p>
<p class="faqa txtlft"><?php echo $faq_row['detail']; ?></p>
<?php }?>
<p class="txtcen"><a href="<?php echo $base_url ?>/faq" class="clbtn"><span>View All</span></a></p>
<?php
}
// End showing FAQs on home page

// Begin showing FAQs on new home page
if ($_GET['task'] == 'show_new_home_faqs') {
    ?>
    <div class="heading">
        <h3>Frequently Asked Questions (FAQs)</h3>
    </div>
    <div class="faq-data">
        <div class="accordion" id="accordionExample">
            <?php
            $faq_result = $db->query("SELECT * FROM tbl_faq WHERE IStatus = 1 AND show_on_home_page = 1 ORDER BY ordernumber ASC ");
            $i = 0;
            while ($faq_row = $faq_result->fetch_array()) {
                $headingId = 'heading' . $i;
                $collapseId = 'collapse' . $i;
                /*$collapsedClass = $i === 0 ? '' : 'collapsed';
                $showClass = $i === 0 ? 'show' : '';
                $expanded = $i === 0 ? 'true' : 'false';*/
                $collapsedClass = 'collapsed';
                $showClass = '';
                $expanded = 'false';
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="<?php echo $headingId; ?>">
                        <button class="accordion-button <?php echo $collapsedClass; ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>"
                            aria-expanded="<?php echo $expanded; ?>" aria-controls="<?php echo $collapseId; ?>">
                            <?php echo htmlspecialchars($faq_row['title']); ?>
                        </button>
                    </h2>
                    <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse <?php echo $showClass; ?>"
                        aria-labelledby="<?php echo $headingId; ?>" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p><?php echo $faq_row['detail']; ?></p>
                        </div>
                    </div>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>
        <p class="txtcen" style="margin-top:10px">
            <a  href="<?php echo $base_url ?>/faq" class="clbtn"><span>View All</span></a>
        </p>
    </div>
    <?php
}
// End showing FAQs on new home page

// Begin displaying Stripe form
if ($_GET['task'] == 'show_stripe_form') {
    require(dirname(__FILE__) . '/../stripe_config.php');
?>
<form method="post" action="<?php echo $base_url ?>/paynow">
    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="<?php echo $publishable_key; ?>" data-amount="<?php echo $_GET['stripe_pay']; ?>" data-name="OHSC" data-description="About OHSC" data-image="" data-currency="gbp"></script>
    <input type="hidden" name="portal_course_id" value="<?php echo $_GET['portal_course_id']; ?>" />
    <input type="hidden" name="stripe_pay" value="<?php echo $_GET['stripe_pay']; ?>" />
    <input type="hidden" name="stripe_pay_actual" value="<?php echo $_GET['stripe_pay_actual']; ?>" />
</form>
<?php
}
// End displaying Stripe form

// Begin displaying content for contact us page
if ($_GET['task'] == 'contactus_content') {
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php
}
// End displaying content for contact us page

// Begin displaying Youtube video
if ($_GET['task'] == 'Youtube_content') {
?>
    <iframe width="100%" height="200" src="https://www.youtube.com/embed/<?php echo $_GET['videoId']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<?php
}
// End displaying Youtube video

// Begin displaying home page Youtube video
if ($_GET['task'] == 'Youtube_homepage') {
?>
    <iframe class="mx-auto d-block" width="400" height="315" src="https://www.youtube.com/embed/kDMNrObeOzg?si=g2D2LcXVztom-MG8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
<?php
}
// End displaying home page Youtube video

// Begin displaying main courses page Youtube video
if ($_GET['task'] == 'Youtube_classification') {
?>
    <iframe class="mx-auto d-block" width="400" height="315" src="https://www.youtube.com/embed/rXJS9bGfJg8?si=NTO7C-3t_V16lxPW" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
<?php
}
// End displaying main courses page Youtube video

// Begin displaying id based Youtube video
if ($_GET['task'] == 'show_youtube') {
    $video_id = filter_var($_GET['video_id'], FILTER_SANITIZE_NUMBER_INT);
    $videorow = $db->query("SELECT video FROM `video` WHERE video_id = $video_id")->fetch_array();
    if ($videorow) {
    ?>
    <iframe class="mx-auto d-block" width="400" height="315" src="<?php echo $videorow['video']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    <?php
    }
}
// End displaying id based Youtube video

// Begin displaynig free course page section
if ($_GET['task'] == 'show_free_course_page') {
    $limit = 8;
    $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;
    $sql = "SELECT 
                d.cid,
                d.name,
                d.slug,
                d.square_icon,
                d.square_icon_alt,
                d.square_icon_title,
                d.square_icon_description,
                d.admin_rating,
                d.admin_total_enrolled,
                c.slug AS catslug
            FROM tbl_diploma d
            LEFT JOIN tbl_courses c ON c.cid = d.cid
            WHERE d.type = 2
            ORDER BY d.ordernumber
            LIMIT $offset, $limit";
    $res = $db->query($sql);
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $img = !empty($row['square_icon']) ? $row['square_icon'] : 'dipimg.webp';
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card-data" style="max-width:295px;margin-left:auto;margin-right:auto;">
                    <div class="cource-box">
                        <div class="img">
                            <figure style="margin:0;">
                                <img src="images/subject/<?= htmlspecialchars($img) ?>"
                                    alt="<?= htmlspecialchars($row['square_icon_alt']) ?>"
                                    title="<?= htmlspecialchars($row['square_icon_title']) ?>" width="295" height="165">
                                <figcaption class="visually-hidden">
                                    <?= htmlspecialchars($row['square_icon_description']) ?>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="data">
                            <div class="data-text">
                                <div class="head">
                                    <h2><?php echo (strlen(htmlspecialchars($row['name'])) <= 30) ? htmlspecialchars($row['name']) : substr(htmlspecialchars($row['name']), 0, 30) . '...'; ?></h2>
                                </div>
                                <div class="stars">
                                    <ul>
                                        <?php for ($i = 0; $i < round($row['admin_rating']); $i++): ?>
                                            <li><img src="./assets/img/Star-3.png" alt="" width="20" height="19"></li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                                <div class="cource-data">
                                    <div class="data-point">
                                        <div class="icon"><img src="./assets/img/bx-user.png" alt="" width="24" height="24"></div>
                                        <div class="name">
                                            <strong><?= number_format($row['admin_total_enrolled']) ?> Enrolled</strong>
                                        </div>
                                    </div>
                                    <div class="data-point">
                                        <div class="icon"><img src="./assets/img/bx-laptop.png" alt="" width="24" height="24"></div>
                                        <div class="name"><strong>Online</strong></div>
                                    </div>
                                    <div class="data-point">
                                        <div class="icon"><img src="./assets/img/bx-time-five.png" alt="" width="24" height="24"></div>
                                        <div class="name"><strong>No timeframe - Self Paced</strong></div>
                                    </div>
                                </div>
                                <div class="price">
                                    <div class="current-fee"><strong>FREE</strong></div>
                                    <div class="previous-fee"><strike>£130.00</strike></div>
                                </div>
                                <div class="view-btn">
                                    <a href="<?= $base_url ?>/courses/<?= htmlspecialchars($row['catslug']) ?>/<?= htmlspecialchars($row['slug']) ?>">
                                        View Course &gt;&gt;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="col-12">
            <p>No diplomas found.</p>
        </div>
        <?php
    }
}
// End displaynig free course page section

// Begin displaynig free course pagination
if ($_GET['task'] == 'show_free_course_pagination') {
    $limit = 8;
    $total_res = $db->query("SELECT COUNT(*) AS cnt FROM tbl_diploma WHERE type = 2");
    $total_row = $total_res->fetch_assoc();
    $total_pages = ceil($total_row['cnt'] / $limit);
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($total_pages < 1) exit;
    ?>
    <ul class="pagination diploma-pagination justify-content-center mt-4">

        <!-- First -->
        <li class="page-item <?= ($current_page == 1 ? 'disabled' : '') ?>">
            <a href="#" class="page-link" data-page="1">&laquo; First</a>
        </li>

        <?php
        $max_links = 5;
        $start = max(1, $current_page - 2);
        $end   = min($total_pages, $start + $max_links - 1);

        if ($end - $start < $max_links - 1) {
            $start = max(1, $end - $max_links + 1);
        }

        for ($p = $start; $p <= $end; $p++): ?>
            <li class="page-item <?= $p == $current_page ? 'active' : '' ?>">
                <a href="#" class="page-link" data-page="<?= $p ?>"><?= $p ?></a>
            </li>
        <?php endfor; ?>

        <!-- Last -->
        <li class="page-item <?= ($current_page == $total_pages ? 'disabled' : '') ?>">
            <a href="#" class="page-link" data-page="<?= $total_pages ?>">Last &raquo;</a>
        </li>

    </ul>
<?php
}
// End displaynig free course pagination

// Begin displaynig free course category page diploma listing
if ($_GET['task'] == 'show_free_course_category_page') {
    $limit = 8;
    $page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;
    $cid = $_GET['cid'];
    $sql = "SELECT 
                d.cid,
                d.name,
                d.slug,
                d.square_icon,
                d.square_icon_alt,
                d.square_icon_title,
                d.square_icon_description,
                d.admin_rating,
                d.admin_total_enrolled,
                c.slug AS catslug
            FROM tbl_diploma d
            LEFT JOIN tbl_courses c ON c.cid = d.cid
            WHERE d.type = 2 
            AND d.cid = $cid 
            ORDER BY d.ordernumber
            LIMIT $offset, $limit";
    $res = $db->query($sql);
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $img = !empty($row['square_icon']) ? $row['square_icon'] : 'dipimg.webp';
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6" style="margin-top:20px;">
                <div class="card-data" style="max-width:295px;margin-left:auto;margin-right:auto;">
                    <div class="cource-box">
                        <div class="img">
                            <figure style="margin:0;">
                                <img src="<?php echo $base_url; ?>/images/subject/<?php echo $img; ?>" alt="<?php echo htmlspecialchars($row['square_icon_alt']); ?>" title="<?php echo htmlspecialchars($row['square_icon_title']); ?>" width="295" height="165">
                                <figcaption class="visually-hidden"><?php echo htmlspecialchars($row['square_icon_description']); ?></figcaption>
                            </figure>
                        </div>
                        <div class="data">
                            <div class="data-text">
                                <div class="head coname">
                                    <h2><?php echo (strlen(htmlspecialchars($row['name'])) <= 30) ? htmlspecialchars($row['name']) : substr(htmlspecialchars($row['name']), 0, 30) . '...'; ?></h2>
                                </div>
                                <div class="stars">
                                    <ul>
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <li><img src="<?php echo $pdots; ?>/assets/img/Star-3.png" width="20" height="19" alt=""></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="cource-data">
                                    <div class="data-point">
                                        <div class="icon">
                                            <img src="<?php echo $pdots; ?>/assets/img/bx-user.png" width="24" height="24" alt="">
                                        </div>
                                        <div class="name">
                                            <strong><?php echo (int)$row['admin_total_enrolled']; ?> Enrolled</strong>
                                        </div>
                                    </div>
                                    <div class="data-point">
                                        <div class="icon">
                                            <img src="<?php echo $pdots; ?>/assets/img/bx-laptop.png" width="24" height="24" alt="">
                                        </div>
                                        <div class="name">
                                            <strong>Online</strong>
                                        </div>
                                    </div>
                                    <div class="data-point">
                                        <div class="icon">
                                            <img src="<?php echo $pdots; ?>/assets/img/bx-time-five.png" width="24" height="24" alt="">
                                        </div>
                                        <div class="name">
                                            <strong>No timeframe - Self Paced</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="price">
                                    <div class="current-fee">
                                        <strong>FREE</strong>
                                    </div>
                                </div>
                                <div class="view-btn">
                                    <a href="<?php echo htmlspecialchars($base_url . '/courses/' . $row['catslug'] . '/' . $row['slug']); ?>">View Course >></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="col-12">
            <p>No diplomas found.</p>
        </div>
        <?php
    }
}
// End displaynig free course category page diploma listing

// Begin displaynig free course category pagination
if ($_GET['task'] == 'show_free_course_category_pagination') {
    $limit = 8;
    $cid = $_GET['cid'];
    $total_res = $db->query("SELECT COUNT(*) AS cnt FROM tbl_diploma WHERE type = 2 AND cid = $cid");
    $total_row = $total_res->fetch_assoc();
    $total_pages = ceil($total_row['cnt'] / $limit);
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($total_pages < 1) exit;
    ?>
    <ul class="pagination diploma-pagination justify-content-center mt-4">

        <!-- First -->
        <li class="page-item <?= ($current_page == 1 ? 'disabled' : '') ?>">
            <a href="#" class="page-link" data-page="1">&laquo; First</a>
        </li>

        <?php
        $max_links = 5;
        $start = max(1, $current_page - 2);
        $end   = min($total_pages, $start + $max_links - 1);

        if ($end - $start < $max_links - 1) {
            $start = max(1, $end - $max_links + 1);
        }

        for ($p = $start; $p <= $end; $p++): ?>
            <li class="page-item <?= $p == $current_page ? 'active' : '' ?>">
                <a href="#" class="page-link" data-page="<?= $p ?>"><?= $p ?></a>
            </li>
        <?php endfor; ?>

        <!-- Last -->
        <li class="page-item <?= ($current_page == $total_pages ? 'disabled' : '') ?>">
            <a href="#" class="page-link" data-page="<?= $total_pages ?>">Last &raquo;</a>
        </li>

    </ul>
<?php
}
// End displaynig free course category pagination

// Begin loading main free courses blog content
if ($_GET['task'] == 'load_mfcblgcon') {
    $related_blog_result = $db->query("SELECT title, slug, image, alt, image_title, image_description, publish_date 
        FROM blog 
        WHERE status = '1' AND show_in_free_courses = '1' 
        ORDER BY publish_date DESC ");
    if ($related_blog_result->num_rows > 0) {
?>
<div class="container">
    <div class="heading">
        <h3>Related Blog Posts</h3>
    </div>
    <div class="blogs-data">
        <div class="slider">
            <div class="row">
                <div class="owl-carousel owl-theme blogs-carousel">
                    <?php while ($blog = $related_blog_result->fetch_assoc()) { ?>
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
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    }
}
// End loading main free courses blog content

// Begin displaying course categories on main free courses page
if ($_GET['task'] == 'show_freecoursecatsmain') {
    $catdip_result = $db->query("SELECT td.cid, td.slug, tc.slug AS catslug, tc.cname, tc.icon, tc.icon_alt, tc.icon_title, tc.icon_description FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.type = '3' AND td.cid = tc.cid ORDER BY tc.ordernumber ASC");
    $count = 0;
    while ($catdip_row = $catdip_result->fetch_array()) {
        $category_image = (!empty($catdip_row['icon'])) ? "images/nd/category/" . $catdip_row['icon'] : "images/nd/category/default_cat_icon.webp";
        $count++;
    ?>
    <div class="col-lg-3 category-item <?php echo ($count > 8) ? 'hidden-course' : ''; ?>">
        <a href="<?php echo $base_url; ?>/courses/<?php echo $catdip_row['catslug']; ?>/<?php echo $catdip_row['slug']; ?>">
            <div class="free-box">
                <div class="img">
                    <figure>
                        <img style="width: 30px !important" src="<?php echo $category_image; ?>" alt="" title = "<?php echo $catdip_row['icon_title']; ?>" width="37" height="33">
                        <figcaption class="vis-hid"><?php echo $catdip_row['icon_description']; ?></figcaption>
                    </figure>
                </div>
                <div class="name">
                    <h2><?php echo $catdip_row['cname']; ?></h2>
                </div>
            </div>
        </a>
    </div>
    <?php
    }
}
// End displaying course categories on main free courses page

// Begin displaying course categories on free course category page
if ($_GET['task'] == 'showcrssldrsec') {
?>
<div class="container">
    <div class="heading">
        <h3>Explore Related <span>Course Categories</span></h3>
    </div>
    <div class="slider">
        <?php
        $categories_result = $db->query("
            SELECT 
                c.cname, 
                c.slug, 
                c.icon,
                c.icon_alt,
                c.icon_title,
                c.icon_description, 
                COUNT(d.diplomaid) AS course_count 
            FROM tbl_courses AS c
            LEFT JOIN tbl_diploma AS d ON c.cid = d.cid
            WHERE c.IStatus = 1)
            GROUP BY c.cid
            ORDER BY c.ordernumber ASC
        ");
        $categories = [];
        while ($row = $categories_result->fetch_array()) {
            $categories[] = $row;
        }
        ?>
        <div class="row">
            <div class="owl-carousel owl-theme home-carousel">
                <?php for ($i = 0; $i < count($categories); $i += 2) { ?>
                    <div class="item">
                        <?php for ($j = 0; $j < 2; $j++) {
                            if (isset($categories[$i + $j])) {
                                $category = $categories[$i + $j]; ?>
                                <div class="card">
                                    <a href="<?php echo $base_url; ?>/courses/<?php echo $category['slug']; ?>">
                                        <div class="card-body">
                                            <div class="icon">
                                                <figure>
                                                    <img src="<?php echo $base_url; ?>/images/nd/category/<?php echo $category['icon']; ?>" width="37" height="33" alt="" title = "<?php echo $category['icon_title']; ?>">
                                                    <figcaption class="vis-hid"><?php echo $category['icon_description']; ?></figcaption>
                                                </figure>
                                            </div>
                                            <div class="text">
                                                <h2><?php echo $category['cname']; ?></h2>
                                                <span><?php echo $category['course_count']; ?> Courses</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
}
// End displaying course categories on free course category page

// Begin showing category/course search suggestions
if ($_GET['task'] == 'searchSuggest') {
    $searchTerm = isset($_GET['q']) ? $_GET['q'] : '';
    $response = [];

    if ($searchTerm !== '') {
        // Begin fetching relevant categories
        $query = "SELECT cname, slug, previous_free_category FROM tbl_courses WHERE cname LIKE ? AND IStatus = 1 ";
        $stmt = $db->prepare($query);
        $likeTerm = "%" . $searchTerm . "%";
        $stmt->bind_param("s", $likeTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $c_uri = ($row['previous_free_category']) ? '/' : '/courses/';
            $dynamicLink = $base_url . $c_uri . $row['slug'];
            $response[] = [
                "course_name" => $row['cname'],
                "course_link" => $dynamicLink
            ];
        }
        $stmt->close();
        // End fetching relevant categories
        // Begin fetching relevant courses
        $query = "SELECT tc.slug AS catSlug, td.name, td.slug FROM tbl_courses AS tc, tbl_diploma AS td WHERE tc.cid = td.cid AND td.name LIKE ? AND td.IStatus = 1 ";
        $stmt = $db->prepare($query);
        $likeTerm = "%" . $searchTerm . "%";
        $stmt->bind_param("s", $likeTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $dynamicLink = $base_url . '/courses/' . $row['catSlug'] . '/' . $row['slug'];
            $response[] = [
                "course_name" => $row['name'],
                "course_link" => $dynamicLink
            ];
        }
        $stmt->close();
        // End fetching relevant courses
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
// End showing category/course search suggestions
