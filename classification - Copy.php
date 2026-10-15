<?php
include("admin/classes/connection.php");
require_once('includes/functions.php');
$classification_row = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1 AND id = " . $_GET['id'])->fetch_array();
                    
if (!$classification_row) {
    header('location:/');
    exit;
}
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
    <link rel="preload" as="image" href="../assets/img/bg.webp" fetchpriority="high">
    <?php include 'includes/header-new.php'; ?>
    <main id="main-content">
        <div class="home_content" style="padding-bottom:10px;">
            <div class="row  clearfix">
                <ol class="breadcrumb">
                    <li><a href="<?php echo $base_url; ?>" class="gentxt_basic bluetxt">Home</a></li>
                    <li><span class="active gentxt_basic">Courses</span></li>
                </ol>
            </div>
            <div class="heading">
                <h1><?php echo $classification_row['name']; ?> Courses / University Pathway</h1>
            </div>
            <div class="text">
                <p><?php echo $classification_row['header_excerpt']; ?></p>
            </div>
            <div class="text read-more d-none">
                <p><?php echo $classification_row['header_description']; ?></p>
            </div>
            <div class="read-btn">
                <button type="button" class="toggle-btn read-btn-text" aria-expanded="false" aria-controls="more-1">Read more +</button>
            </div>
        </div>
        <?php /*<!-- Hero section -->
        <section class="hero">
            <div class="container">
                <div class="hero-badge">
                    <div class="main-page">
                        <strong>Home</strong>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                    <div class="next-page">
                        <a href=""><?php echo $classification_row['name']; ?> Courses</a>
                    </div>
                </div>
                <div class="hero-data">
                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                            <div class="hero-text">
                                <div class="heading">
                                    <h1><?php echo $classification_row['name']; ?> Courses / University Pathway</h1>
                                </div>
                                <div class="text">
                                    <p><?php echo $classification_row['header_excerpt']; ?></p>
                                </div>
                                <div class="text read-more d-none">
                                    <p><?php echo $classification_row['header_description']; ?></p>
                                </div>
                                <div class="read-btn">
                                    <button type="button" class="toggle-btn" aria-expanded="false" aria-controls="more-1">Read more +</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>*/ ?>
        <section class="learner-bar">
            <div class="container">
                <div class="bar-boxes">
                    <div class="bar-box">
                        <div class="star-box">
                            <div class="bar-stars">
                                <ul>
                                    <li>
                                        <div class="star-box">
                                            <img src="<?php echo $pdots; ?>assets/img/star.png" alt="image of star">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="<?php echo $pdots; ?>assets/img/star.png" alt="image of star">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="<?php echo $pdots; ?>assets/img/star.png" alt="image of star">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="<?php echo $pdots; ?>assets/img/star.png" alt="image of star">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="<?php echo $pdots; ?>assets/img/star.png" alt="image of star">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="trustpliot-star">
                                <div class="icon">
                                    <img src="<?php echo $pdots; ?>assets/img/g-star.png" alt="image of star" width="136" height="26">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="<?php echo $pdots; ?>assets/img/learners.png" alt="human icon">
                        </div>
                        <div class="text">
                            <p><strong>3 million+</strong> learners</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="<?php echo $pdots; ?>assets/img/learners.png" alt="human icon">
                        </div>
                        <div class="text">
                            <p><strong>2 million+</strong> graduates</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="<?php echo $pdots; ?>assets/img/world.png" alt="image of globe">
                        </div>
                        <div class="text">
                            <p><strong>195</strong> countries</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="free-course">
            <div class="container">
                <div class="free-heading">
                    <h2>Explore 800+ Online <?php echo $classification_row['name']; ?> Courses</h2>
                </div>
                <div class="free-courses-boxs">
                    <div class="row">
                        <?php
                        $temp_count = 0;
                        $catgories_result = $db->query("SELECT cname, slug, icon, icon_alt, icon_title, icon_description FROM tbl_courses WHERE IStatus = 1 AND classification_id = " . $classification_row['id'] . " ORDER BY ordernumber ASC");
                        while ($catgories_row = $catgories_result->fetch_array()) {
                            $temp_count++;
                        ?>
                            <div class="col-lg-3 course-item">
                                <a href="<?php echo $base_url; ?>/<?php echo $classification_row['slug'] . '/' . $catgories_row['slug']; ?>">
                                    <div class="free-box">
                                        <div class="img">
                                            <figure>
                                                <img src="<?php echo $pdots; ?>images/nd/category/<?php echo $catgories_row['icon']; ?>" style="width:unset !important" width="37" height="33" alt="<?php echo $catgories_row['icon_alt']; ?>" title = "<?php echo $catgories_row['icon_title']; ?>">
                                                <figcaption class="vis-hid"><?php echo $catgories_row['icon_description']; ?></figcaption>
                                            </figure>
                                        </div>
                                        <div class="name">
                                            <h2><?php echo $catgories_row['cname']; ?></h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($temp_count > 8) { ?>
                <div class="view-all-btn">
                    <button id="toggleBtn">View all Subjects</button>
                </div>
                <?php } ?>
            </div>
        </section>
        <section class="all-cources-slider">
            <div class="container">
                <div class="tabs-box">
                    <nav>
                        <div class="nav nav-tabs f-switch-btn" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-popular-course-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-popular-course" type="button" role="tab"
                                aria-controls="nav-popular-course" aria-selected="true">Popular Courses</button>
                            <button class="nav-link" id="nav-top-diploma-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-top-diploma" type="button" role="tab" aria-controls="nav-top-diploma"
                                aria-selected="false">Top Diplomas</button>
                            <button class="nav-link" id="nav-top-certificate-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-top-certificate" type="button" role="tab"
                                aria-controls="nav-top-certificate" aria-selected="false">Top Certifications</button>
                            <button class="nav-link" id="nav-new-tab" data-bs-toggle="tab" data-bs-target="#nav-new"
                                type="button" role="tab" aria-controls="nav-new" aria-selected="false">New Courses</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-popular-course" role="tabpanel"
                            aria-labelledby="nav-popular-course-tab">
                            <div class="slider">
                                <div class="row">
                                    <div class="owl-carousel owl-theme all-carousel">
                                        <?php
                                        $diploma_result = $db->query("SELECT td.*, tc.slug AS tc_slug, tc.special_discount FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.IStatus = 1 AND td.show_in_category = 1 AND td.classification = " . $classification_row['id'] . " AND (td.type = 1 OR td.type = 2) AND td.cid = tc.cid AND tc.IStatus = 1 AND td.famous_course = 1 ");
                                        while ($diploma_row = $diploma_result->fetch_array()) {
                                            $course_url = "/" . $classification_row['slug'] . '/' . $diploma_row['tc_slug'] . '/' . $diploma_row['slug'];
                                            $image_url = "images/subject/" . $diploma_row['thumbnail'];
                                            $payment_row = $db->query("SELECT payment AS fullpayment, discount FROM tbl_payment WHERE diplomaid = " . $diploma_row['diplomaid'])->fetch_array();
                                            if ($diploma_row['free_course'] == 1) {
                                                $price = "Free";
                                                $previous_price = "";
                                            } else {
                                                $price = (getCoursePrice($diploma_row['diplomaid']) == 0) ? 'FREE' : "Â£" . getCoursePrice($diploma_row['diplomaid']);
                                                $prev_price = getCoursePricePrev($diploma_row['diplomaid']);
                                                $previous_price = (!empty($prev_price)) ? "<strike>&#163;" . $prev_price . "</strike>" : '';
                                            }
                                        ?>
                                            <div class="item">
                                                <div class="card-data">
                                                    <div class="cource-box">
                                                        <div class="img">
                                                            <figure style="margin:0;">
                                                                <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['alternate']; ?>" loading="lazy" title="<?php echo $diploma_row['thumbnail_title']; ?>">
                                                                <figcaption class="visually-hidden"><?php echo $diploma_row['thumbnail_description']; ?></figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="data">
                                                            <div class="data-text">
                                                                <div class="head coname">
                                                                    <h2><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h2>
                                                                </div>
                                                                <div class="stars">
                                                                    <ul>
                                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                            <li><img src="<?php echo $pdots; ?>assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>" alt="image of star"></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="cource-data">
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-user.png" alt="user icon"></div>
                                                                        <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-laptop.png" alt="image of laptop"></div>
                                                                        <div class="name"><strong>Online</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-time-five.png" alt="Clock"></div>
                                                                        <div class="name"><strong>No timeframe - Self Paced</strong></div>
                                                                    </div>
                                                                </div>
                                                                <div class="price">
                                                                    <div class="current-fee"><strong><?php echo $price; ?></strong></div>
                                                                    <div class="previous-fee"> <?php echo $previous_price; ?> </div>
                                                                </div>
                                                                <div class="view-btn">
                                                                    <a href="<?php echo $course_url; ?>">View Course >></a>
                                                                </div>
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
                        <div class="tab-pane fade" id="nav-top-diploma" role="tabpanel"
                            aria-labelledby="nav-top-diploma-tab">
                            <div class="slider">
                                <div class="row">
                                    <div class="owl-carousel owl-theme all-carousel">
                                        <?php
                                        $diploma_result = $db->query("SELECT td.*, tc.slug AS tc_slug, tc.special_discount FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.IStatus = 1 AND td.show_in_category = 1 AND td.classification = " . $classification_row['id'] . " AND (td.type = 1 OR td.type = 2) AND td.cid = tc.cid AND tc.IStatus = 1 AND td.top_diploma = 1 ");
                                        while ($diploma_row = $diploma_result->fetch_array()) {
                                            $course_url = "/" . $classification_row['slug'] . '/' . $diploma_row['tc_slug'] . '/' . $diploma_row['slug'];
                                            $image_url = "images/subject/" . $diploma_row['thumbnail'];
                                            $payment_row = $db->query("SELECT payment AS fullpayment, discount FROM tbl_payment WHERE diplomaid = " . $diploma_row['diplomaid'])->fetch_array();
                                            if ($diploma_row['free_course'] == 1) {
                                                $price = "Free";
                                                $previous_price = "";
                                            } else {
                                                $price = (getCoursePrice($diploma_row['diplomaid']) == 0) ? 'FREE' : "Â£" . getCoursePrice($diploma_row['diplomaid']);
                                                $prev_price = getCoursePricePrev($diploma_row['diplomaid']);
                                                $previous_price = (!empty($prev_price)) ? "<strike>&#163;" . $prev_price . "</strike>" : '';
                                            }
                                        ?>
                                            <div class="item">
                                                <div class="card-data">
                                                    <div class="cource-box">
                                                        <div class="img">
                                                            <figure style="margin:0;">
                                                                <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['alternate']; ?>" loading="lazy" title="<?php echo $diploma_row['thumbnail_title']; ?>">
                                                                <figcaption class="visually-hidden"><?php echo $diploma_row['thumbnail_description']; ?></figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="data">
                                                            <div class="data-text">
                                                                <div class="head coname">
                                                                    <h2><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h2>
                                                                </div>
                                                                <div class="stars">
                                                                    <ul>
                                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                            <li><img src="<?php echo $pdots; ?>assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>" alt="image of star"></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="cource-data">
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-user.png" alt="human image"></div>
                                                                        <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-laptop.png" alt="laptop"></div>
                                                                        <div class="name"><strong>Online</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-time-five.png" alt="Clock"></div>
                                                                        <div class="name"><strong>No timeframe - Self Paced</strong></div>
                                                                    </div>
                                                                </div>
                                                                <div class="price">
                                                                    <div class="current-fee"><strong><?php echo $price; ?></strong></div>
                                                                    <div class="previous-fee"> <?php echo $previous_price; ?> </div>
                                                                </div>
                                                                <div class="view-btn">
                                                                    <a href="<?php echo $course_url; ?>">View Course >></a>
                                                                </div>
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
                        <div class="tab-pane fade" id="nav-top-certificate" role="tabpanel"
                            aria-labelledby="nav-top-certificate-tab">
                            <div class="slider">
                                <div class="row">
                                    <div class="owl-carousel owl-theme all-carousel">
                                        <?php
                                        $diploma_result = $db->query("SELECT td.*, tc.slug AS tc_slug, tc.special_discount FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.IStatus = 1 AND td.show_in_category = 1 AND td.classification = " . $classification_row['id'] . " AND (td.type = 1 OR td.type = 2) AND td.cid = tc.cid AND tc.IStatus = 1 AND td.top_certification = 1 ");
                                        while ($diploma_row = $diploma_result->fetch_array()) {
                                            $course_url = "/" . $classification_row['slug'] . '/' . $diploma_row['tc_slug'] . '/' . $diploma_row['slug'];
                                            $image_url = "images/subject/" . $diploma_row['thumbnail'];
                                            $payment_row = $db->query("SELECT payment AS fullpayment, discount FROM tbl_payment WHERE diplomaid = " . $diploma_row['diplomaid'])->fetch_array();
                                            if ($diploma_row['free_course'] == 1) {
                                                $price = "Free";
                                                $previous_price = "";
                                            } else {
                                                $price = (getCoursePrice($diploma_row['diplomaid']) == 0) ? 'FREE' : "Â£" . getCoursePrice($diploma_row['diplomaid']);
                                                $prev_price = getCoursePricePrev($diploma_row['diplomaid']);
                                                $previous_price = (!empty($prev_price)) ? "<strike>&#163;" . $prev_price . "</strike>" : '';
                                            }
                                        ?>
                                            <div class="item">
                                                <div class="card-data">
                                                    <div class="cource-box">
                                                        <div class="img">
                                                            <figure style="margin:0;">
                                                                <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['alternate']; ?>" loading="lazy" title="<?php echo $diploma_row['thumbnail_title']; ?>">
                                                                <figcaption class="visually-hidden"><?php echo $diploma_row['thumbnail_description']; ?></figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="data">
                                                            <div class="data-text">
                                                                <div class="head coname">
                                                                    <h2><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h2>
                                                                </div>
                                                                <div class="stars">
                                                                    <ul>
                                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                            <li><img src="<?php echo $pdots; ?>assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>" alt="image of star"></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="cource-data">
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-user.png" alt="Image of human"></div>
                                                                        <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-laptop.png" alt="Laptop"></div>
                                                                        <div class="name"><strong>Online</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-time-five.png" alt="Clock"></div>
                                                                        <div class="name"><strong>No timeframe - Self Paced</strong></div>
                                                                    </div>
                                                                </div>
                                                                <div class="price">
                                                                    <div class="current-fee"><strong><?php echo $price; ?></strong></div>
                                                                    <div class="previous-fee"> <?php echo $previous_price; ?> </div>
                                                                </div>
                                                                <div class="view-btn">
                                                                    <a href="<?php echo $course_url; ?>">View Course >></a>
                                                                </div>
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
                        <div class="tab-pane fade" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
                            <div class="slider">
                                <div class="row">
                                    <div class="owl-carousel owl-theme all-carousel">
                                        <?php
                                        $diploma_result = $db->query("SELECT td.*, tc.slug AS tc_slug, tc.special_discount FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.IStatus = 1 AND td.show_in_category = 1 AND td.classification = " . $classification_row['id'] . " AND (td.type = 1 OR td.type = 2) AND td.cid = tc.cid AND tc.IStatus = 1 AND td.new_course = 1 ");
                                        while ($diploma_row = $diploma_result->fetch_array()) {
                                            $course_url = "/" . $classification_row['slug'] . '/' . $diploma_row['tc_slug'] . '/' . $diploma_row['slug'];
                                            $image_url = "images/subject/" . $diploma_row['thumbnail'];
                                            $payment_row = $db->query("SELECT payment AS fullpayment, discount FROM tbl_payment WHERE diplomaid = " . $diploma_row['diplomaid'])->fetch_array();
                                            if ($diploma_row['free_course'] == 1) {
                                                $price = "Free";
                                                $previous_price = "";
                                            } else {
                                                $price = (getCoursePrice($diploma_row['diplomaid']) == 0) ? 'FREE' : "Â£" . getCoursePrice($diploma_row['diplomaid']);
                                                $prev_price = getCoursePricePrev($diploma_row['diplomaid']);
                                                $previous_price = (!empty($prev_price)) ? "<strike>&#163;" . $prev_price . "</strike>" : '';
                                            }
                                        ?>
                                            <div class="item">
                                                <div class="card-data">
                                                    <div class="cource-box">
                                                        <div class="img">
                                                            <figure style="margin:0;">
                                                                <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['alternate']; ?>" loading="lazy" title="<?php echo $diploma_row['thumbnail_title']; ?>">
                                                                <figcaption class="visually-hidden"><?php echo $diploma_row['thumbnail_description']; ?></figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="data">
                                                            <div class="data-text">
                                                                <div class="head coname">
                                                                    <h2><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h2>
                                                                </div>
                                                                <div class="stars">
                                                                    <ul>
                                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                            <li><img src="<?php echo $pdots; ?>assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>" alt="Star"></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="cource-data">
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-user.png" alt="Icon of human"></div>
                                                                        <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-laptop.png" alt="Laptop"></div>
                                                                        <div class="name"><strong>Online</strong></div>
                                                                    </div>
                                                                    <div class="data-point">
                                                                        <div class="icon"><img src="<?php echo $pdots; ?>assets/img/bx-time-five.png" alt="Clock"></div>
                                                                        <div class="name"><strong>No timeframe - Self Paced</strong></div>
                                                                    </div>
                                                                </div>
                                                                <div class="price">
                                                                    <div class="current-fee"><strong><?php echo $price; ?></strong></div>
                                                                    <div class="previous-fee"> <?php echo $previous_price; ?> </div>
                                                                </div>
                                                                <div class="view-btn">
                                                                    <a href="<?php echo $course_url; ?>">View Course >></a>
                                                                </div>
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
                </div>
            </div>
        </section>
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
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-file-doc.png" alt="File">
                                    </div>
                                    <div class="text">
                                        <h2>CPD & Employer Approved Qualifications</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-crown.png" alt="Crown">
                                    </div>
                                    <div class="text">
                                        <h2>Fully Endorsed by Leading Accrediting Bodies</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bx-run.png" alt="Running person">
                                    </div>
                                    <div class="text">
                                        <h2>Globally Recognised and Career-Focused</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-file-doc.png" alt="File">
                                    </div>
                                    <div class="text">
                                        <h2>Flexible, Self-Paced Online Learning</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-crown.png" alt="Crown">
                                    </div>
                                    <div class="text">
                                        <h2>Enhance Your Skills with Accredited Study</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bx-run.png" alt="Running person">
                                    </div>
                                    <div class="text">
                                        <h2>Earn Prestigious Certificates or Diplomas</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-file-doc.png" alt="File">
                                    </div>
                                    <div class="text">
                                        <h2>High-Quality Materials Designed by Experts</h2>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="<?php echo $pdots; ?>assets/img/bxs-crown.png" alt="Crown">
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
        <?php /*<!-- Begin Banner Section -->
        <section class="main_banner">
            <div class="main_banner_left"><a href="https://www.cpdcourses.com/" class="flxlink"><img src="<?php echo $pdots; ?>images/enroll_btn2.webp" width="188" height="54" class="enrbtn_left" border="0" alt="CPD" /></a></div>
            <div class="main_banner_right"><a href="https://www.learnartificialintelligence.ai/" class="flxlink"><img src="<?php echo $pdots; ?>images/enroll_btn1.webp" width="187" height="53" class="enrbtn_right" border="0" alt="LAI" /></a></div>
        </section>
        <!-- End Banner Section -->*/ ?>
        <section class="course-slider Categories course-categories">
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
        </section>
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
        <section class="blogs pb-blogs">
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
        </section>
    </main>
    <?php include 'includes/footer-new.php'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
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
        $(".course-item:gt(7)").hide();
        $("#toggleBtn").click(function() {
            $(".course-item:gt(7)").toggle();
            if ($(".course-item:gt(7)").is(":visible")) {
                $(this).text("Less all Subjects"); // Change text to "Less all Subjects"
            } else {
                $(this).text("View all Subjects"); // Change text to "View all Subjects"
            }
        });
    });
    </script>
    </body>
</html>
