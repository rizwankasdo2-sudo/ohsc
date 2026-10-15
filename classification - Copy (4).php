<?php
include("admin/classes/connection.php");
require_once('includes/functions.php');
$classification_row = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1 AND id = " . $_GET['id'])->fetch_array();
                    
if (!$classification_row) {
    header('location:/');
    exit;
}
?>
<!DOCTYPE>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $classification_row['page_title']; ?></title>
    <meta name="description" content="<?php echo strip_tags($classification_row['meta_desc']); ?>" />
    <meta name="keywords" content="<?php echo $classification_row['meta_key']; ?>" />
    <meta name="robots" content="<?php echo $classification_row['robots']; ?>" />
    <!-- style.css -->
    <?php include 'includes/header-new.php'; ?>
    <!-- Hero section -->
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
                                <p><?php echo $classification_row['excerpt']; ?></p>
                            </div>
                            <div class="text read-more d-none">
                                <p><?php echo $classification_row['description']; ?></p>
                            </div>
                            <div class="read-btn">
                                <a href="javascript:void(0);" class="toggle-btn">Read More +</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="learner-bar">
        <div class="container">
            <div class="bar-boxes">
                <div class="bar-box">
                    <div class="star-box">
                        <div class="bar-stars">
                            <ul>
                                <li>
                                    <div class="star-box">
                                        <img src="./assets/img/star.png">
                                    </div>
                                </li>
                                <li>
                                    <div class="star-box">
                                        <img src="./assets/img/star.png">
                                    </div>
                                </li>
                                <li>
                                    <div class="star-box">
                                        <img src="./assets/img/star.png">
                                    </div>
                                </li>
                                <li>
                                    <div class="star-box">
                                        <img src="./assets/img/star.png">
                                    </div>
                                </li>
                                <li>
                                    <div class="star-box">
                                        <img src="./assets/img/star.png">
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="trustpliot-star">
                            <div class="icon">
                                <img src="./assets/img/g-star.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bar-box">
                    <div class="img">
                        <img src="./assets/img/learners.png">
                    </div>
                    <div class="text">
                        <p><strong>4 million+</strong> learners</p>
                    </div>
                </div>
                <div class="bar-box">
                    <div class="img">
                        <img src="./assets/img/learners.png">
                    </div>
                    <div class="text">
                        <p><strong>8 million+</strong> graduates</p>
                    </div>
                </div>
                <div class="bar-box">
                    <div class="img">
                        <img src="./assets/img/world.png">
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
                <h3>Explore 100s of Online <?php echo $classification_row['name']; ?> Courses</h3>
            </div>
            <div class="free-courses-boxs">
                <div class="row">
                    <?php
                    $temp_count = 0;
                    $catgories_result = $db->query("SELECT cname, slug, icon FROM tbl_courses WHERE IStatus = 1 AND classification_id = " . $classification_row['id'] . " ORDER BY ordernumber ASC");
                    while ($catgories_row = $catgories_result->fetch_array()) {
                        $temp_count++;
                    ?>
                        <div class="col-lg-3 course-item">
                            <a href="<?php echo $base_url; ?>/<?php echo $classification_row['slug'] . '/' . $catgories_row['slug']; ?>">
                                <div class="free-box">
                                    <div class="img">
                                        <img src="<?php echo $base_url; ?>/images/nd/category/<?php echo $catgories_row['icon']; ?>" style="width:unset !important" width="37" height="33" alt="Category Image">
                                    </div>
                                    <div class="name">
                                        <h4><?php echo $catgories_row['cname']; ?></h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($temp_count > 8) { ?>
            <div class="view-all-btn">
                <a href="javascript:void(0);" id="toggleBtn">View all Subjects</a>
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
                                        } elseif ($diploma_row['special_discount']) {
                                            $discount_amount = number_format(($payment_row['fullpayment'] * $diploma_row['special_discount']) / 100, 2, '.', '');
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $discount_amount);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } elseif ($payment_row['discount']) {
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $payment_row['discount']);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } else {
                                            $price = "&#163; " . $payment_row['fullpayment'];
                                            $previous_price = "";
                                        }
                                    ?>
                                        <div class="item">
                                            <div class="card-data">
                                                <div class="cource-box">
                                                    <div class="img">
                                                        <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['name']; ?>" loading="lazy">
                                                    </div>
                                                    <div class="data">
                                                        <div class="data-text">
                                                            <div class="head coname">
                                                                <h4><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h4>
                                                            </div>
                                                            <div class="stars">
                                                                <ul>
                                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                        <li><img src="./assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>"></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                            <div class="cource-data">
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-user.png"></div>
                                                                    <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-laptop.png"></div>
                                                                    <div class="name"><strong>Online</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-time-five.png"></div>
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
                                        } elseif ($diploma_row['special_discount']) {
                                            $discount_amount = number_format(($payment_row['fullpayment'] * $diploma_row['special_discount']) / 100, 2, '.', '');
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $discount_amount);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } elseif ($payment_row['discount']) {
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $payment_row['discount']);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } else {
                                            $price = "&#163; " . $payment_row['fullpayment'];
                                            $previous_price = "";
                                        }
                                    ?>
                                        <div class="item">
                                            <div class="card-data">
                                                <div class="cource-box">
                                                    <div class="img">
                                                        <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['name']; ?>" loading="lazy">
                                                    </div>
                                                    <div class="data">
                                                        <div class="data-text">
                                                            <div class="head coname">
                                                                <h4><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h4>
                                                            </div>
                                                            <div class="stars">
                                                                <ul>
                                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                        <li><img src="./assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>"></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                            <div class="cource-data">
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-user.png"></div>
                                                                    <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-laptop.png"></div>
                                                                    <div class="name"><strong>Online</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-time-five.png"></div>
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
                                        } elseif ($diploma_row['special_discount']) {
                                            $discount_amount = number_format(($payment_row['fullpayment'] * $diploma_row['special_discount']) / 100, 2, '.', '');
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $discount_amount);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } elseif ($payment_row['discount']) {
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $payment_row['discount']);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } else {
                                            $price = "&#163; " . $payment_row['fullpayment'];
                                            $previous_price = "";
                                        }
                                    ?>
                                        <div class="item">
                                            <div class="card-data">
                                                <div class="cource-box">
                                                    <div class="img">
                                                        <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['name']; ?>" loading="lazy">
                                                    </div>
                                                    <div class="data">
                                                        <div class="data-text">
                                                            <div class="head coname">
                                                                <h4><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h4>
                                                            </div>
                                                            <div class="stars">
                                                                <ul>
                                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                        <li><img src="./assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>"></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                            <div class="cource-data">
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-user.png"></div>
                                                                    <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-laptop.png"></div>
                                                                    <div class="name"><strong>Online</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-time-five.png"></div>
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
                                        } elseif ($diploma_row['special_discount']) {
                                            $discount_amount = number_format(($payment_row['fullpayment'] * $diploma_row['special_discount']) / 100, 2, '.', '');
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $discount_amount);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } elseif ($payment_row['discount']) {
                                            $price = "&#163; " . ($payment_row['fullpayment'] - $payment_row['discount']);
                                            $previous_price = "<strike>&#163;" . $payment_row['fullpayment'] . "</strike>";
                                        } else {
                                            $price = "&#163; " . $payment_row['fullpayment'];
                                            $previous_price = "";
                                        }
                                    ?>
                                        <div class="item">
                                            <div class="card-data">
                                                <div class="cource-box">
                                                    <div class="img">
                                                        <img src="<?php echo $image_url; ?>" alt="<?php echo $diploma_row['name']; ?>" loading="lazy">
                                                    </div>
                                                    <div class="data">
                                                        <div class="data-text">
                                                            <div class="head coname">
                                                                <h4><?php echo (strlen(htmlspecialchars($diploma_row['name'])) <= 30) ? htmlspecialchars($diploma_row['name']) : substr(htmlspecialchars($diploma_row['name']), 0, 30) . '...'; ?></h4>
                                                            </div>
                                                            <div class="stars">
                                                                <ul>
                                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                        <li><img src="./assets/img/Star-3.png" class="<?php getRatingCSS($i, $diploma_row['admin_rating']); ?>"></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                            <div class="cource-data">
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-user.png"></div>
                                                                    <div class="name"><strong><?php echo $diploma_row['admin_total_enrolled']; ?> Enrolled</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-laptop.png"></div>
                                                                    <div class="name"><strong>Online</strong></div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon"><img src="./assets/img/bx-time-five.png"></div>
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
                            <h3>Benefits of Free Courses</h3>
                        </div>
                        <div class="left-data">
                            <div class="data-point">
                                <div class="c-icon">
                                    <img src="./assets/img/bxs-file-doc.png">
                                </div>
                                <div class="text">
                                    <h4>Certify Your Skills</h4>
                                </div>
                            </div>
                            <div class="data-point">
                                <div class="c-icon">
                                    <img src="./assets/img/bxs-crown.png">
                                </div>
                                <div class="text">
                                    <h4>Stand Out From the Crowd</h4>
                                </div>
                            </div>
                            <div class="data-point">
                                <div class="c-icon">
                                    <img src="./assets/img/bx-run.png">
                                </div>
                                <div class="text">
                                    <h4>Advance in Your Career</h4>
                                </div>
                            </div>
                            <div class="data-point">
                                <div class="c-icon">
                                    <img src="./assets/img/bxs-file-doc.png">
                                </div>
                                <div class="text">
                                    <h4>Certify Your Skills</h4>
                                </div>
                            </div>
                            <div class="data-point">
                                <div class="c-icon">
                                    <img src="./assets/img/bxs-crown.png">
                                </div>
                                <div class="text">
                                    <h4>Stand Out From the Crowd</h4>
                                </div>
                            </div>
                            <div class="data-point">
                                <div class="c-icon">
                                    <img src="./assets/img/bx-run.png">
                                </div>
                                <div class="text">
                                    <h4>Advance in Your Career</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="img">
                            <img src="./assets/img/OHSC Certificate 1.png" alt="certificate">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php /*<section class="demand-careers">
        <div class="container">
            <div class="heading">
                <h3>Explore Top In-Demand Careers</h3>
            </div>
            <div class="slider">
                <div class="row">
                    <div class="owl-carousel owl-theme demand-carousel">
                        <div class="item">
                            <div class="card">
                                <a>
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaing-1.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Business Analyst</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-2.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>HR Manager</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-3.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Digital Marketing
                                                Specialist</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-4.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Financial Analyst</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-2.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Forensic Accountant</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaing-1.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Project Manager</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaing-1.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Business Analyst</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-2.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>HR Manager</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-3.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Digital Marketing
                                                Specialist</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-4.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Financial Analyst</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-2.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Forensic Accountant</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaing-1.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Project Manager</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaing-1.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Business Analyst</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-2.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>HR Manager</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-3.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Digital Marketing
                                                Specialist</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-4.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Financial Analyst</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaind-2.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Forensic Accountant</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="">
                                    <div class="card-data">
                                        <div class="img">
                                            <img src="./assets/img/demaing-1.png" alt="">
                                        </div>
                                        <div class="text">
                                            <h4>Project Manager</h4>
                                            <img src="./assets/img/Arrow 1.png">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>*/ ?>
    <section class="learner-sider slider-border">
        <div class="container">
            <div class="learner-carousel">
                <div class="heading">
                    <h3>Our Learner Stories</h3>
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
                                                        <h4><?php echo $review_row['name'] . ' ' . $review_row['surname']; ?></h4>
                                                        <span>Verified Learner</span>
                                                    </div>
                                                    <div class="learn-mark">
                                                        <img src="./assets/img/learn-mark.png">
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
                                                                <img src="./assets/img/<?php echo ($i <= $review_row['rating']) ? 'Star-3.png' : 'star.png'; ?>">
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
                        <div class="head">
                            <h3>Online <?php echo $classification_row['name']; ?> Courses with Certificates
                        </div>
                        <div class="para">
                            <p><?php echo $classification_row['excerpt']; ?></p>
                            <div class="text read-more-text d-none">
                                <p><?php echo $classification_row['description']; ?></p>
                            </div>
                            <!-- <p>OHSCÂ is proud to offer access to some of the UKâ€™s finest 100%Â freeÂ online courses with certificates of completionÂ available for a small fee.</p> -->
                            <!-- <p>With not a penny to pay, no strings attached and no entry requirements whatsoever, anyone interested in furthering their education now has the chance to study at home for free!</p> -->
                        </div>
                        <div class="read-more-btn">
                            <a href="javascript:void(0);" class="toggle-btn-more">Read More +</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <iframe class="mx-auto d-block" width="400" height="315" src="https://www.youtube.com/embed/rXJS9bGfJg8?si=NTO7C-3t_V16lxPW" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    <?php /*<div class="img">
                        <img src="./assets/img/management.png" alt="play-video">
                        <div class="play-btn">
                            <a href=""><img src="./assets/img/youtube.png"></a>
                        </div>
                    </div>*/ ?>
                </div>
            </div>
        </div>
    </section>
    <section class="enroll">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="enroll-box">
                        <div class="enroll-head">
                            <div class="bar1">
                                <h3>Dive deeper into </h3>
                            </div>
                            <div class="bar2">
                                <h3>your true self</h3>
                            </div>
                        </div>
                        <div class="text">
                            <p>Discover your career strengths & weaknesses</p>
                        </div>
                        <div class="enroll-btn">
                            <a href="<?php echo $base_url; ?>/apply.php">Enrol Now for Free</a>
                        </div>
                        <div class="left-img">
                            <img src="./assets/img/enroll-left.png" alt="enroll-now">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="enroll-box enroll-right">
                        <div class="enroll-head">
                            <div class="bar1">
                                <h3>Dive deeper into </h3>
                            </div>
                            <div class="bar2">
                                <h3>your true self</h3>
                            </div>
                        </div>
                        <div class="text">
                            <p>Discover your career strengths & weaknesses</p>
                        </div>
                        <div class="enroll-btn">
                            <a href="<?php echo $base_url; ?>/apply.php">Enrol Now for Free</a>
                        </div>
                        <div class="left-img">
                            <img src="./assets/img/enroll-right.png" alt="enroll-now">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="course-slider Categories course-categories">
        <div class="container">
            <div class="heading">
                <h3>Explore Other Categories</h3>
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
            <div class="c-nav">
                <button class="carousel-nav1"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="carousel-nav"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </section>
    <?php
        $classification_id = intval($_GET['id']); // Sanitize input
        
        $faq_query = "SELECT f.*
                      FROM tbl_faq f
                      JOIN tbl_courses c ON f.category_id = c.cid
                      WHERE c.classification_id = $classification_id AND f.IStatus = 1
                      ORDER BY f.ordernumber ASC
                      LIMIT 6";
        
        $faq_result = $db->query($faq_query);
        
        if ($faq_result->num_rows > 0) {
        ?>
            <section class="faq">
                <div class="container">
                    <div class="heading">
                        <h3>Frequently Asked Questions (FAQs)</h3>
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
                <h3>Related Blog Posts</h3>
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
                                                <img src="<?php echo $base_url; ?>/images/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['alt']); ?>">
                                            </div>
                                            <div class="text">
                                                <div class="publisher">
                                                    <div class="date">
                                                        <span><?php echo date("d M, Y", strtotime($blog['publish_date'])); ?></span>
                                                    </div>
                                                </div>
                                                <div class="blog-heading">
                                                    <h4><?php echo htmlspecialchars($blog['title']); ?></h4>
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
    <?php include 'includes/footer-new.php'; ?>
    <script>
        $(document).ready(function() {
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
            $(document).ready(function() {
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
        });
    </script>
    </body>
</html>
