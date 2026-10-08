<?php
include "admin/classes/connection.php";
require_once('includes/functions.php');
// Get page SEO
$seo_result = $db->query("select * from tbl_seo where seoid='1' and pagename='Home'");
$seo_row = $seo_result->fetch_array();
?>
<!DOCTYPE>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://www.oxfordhomestudy.com/images/OpenGraphLogo.PNG" />
    <meta property="og:title" content="<?php echo strip_tags($seo_row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($seo_row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($seo_row['metadiscription']); ?>" />
    <meta property="og:url" content="https://www.oxfordhomestudy.com/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $seo_row['metatitle']; ?>" />
    <meta name="robots" content="<?php echo $seo_row['robots']; ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $seo_row['pagetitle']; ?></title>
    <?php include 'includes/header-home-new.php'; ?>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Organization",
            "name": "Oxford Home Study Centre",
            "url": "https://www.oxfordhomestudy.com/",
            "address": "Business Box, Oswin Road, UK",
            "sameAs": [
                "https://www.facebook.com/oxfordhomestudy/",
                "https://twitter.com/OHSCUK",
                "https://www.linkedin.com/company-beta/13375044/",
                "https://www.instagram.com/ohscuk/"
            ]
        }
    </script>
    <style>
        .hidden-course {
            display: none;
        }
    </style>
    <!-- Hero section -->
    <section class="home-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="hero-text">
                        <div class="head">
                            <h1>Connect, Learn, Succeed -</h1>
                            <h3>
                                Wherever You Are
                            </h3>
                        </div>
                    </div>
                    <div class="hero-search-filter">
                        <div class="head-line">
                            <span>Choose online courses to boost your CV for better job prospects.</span>
                        </div>
                        <div class="search-input">
                            <form class="d-flex" method="post" action="<?php echo $base_url ?>/search.php" name="searchfrm" role="search">
                                <input class="form-control me-2" type="text" name="course_name" placeholder="Subject or Qualification, e.g, IT" aria-label="Search">
                                <button class="btn" type="submit">Search Courses</button>
                            </form>
                        </div>
                        <div class="filter-bottom">
                            <div class="tags">
                                <!-- <span>Regulated Courses</span>
                                <span>CPD Courses</span>
                                <span>Free Courses</span> -->
                                <span id="<?php echo ($_SESSION['total_classifications'] == 4) ? 'topslider' : 'noslider'; ?>" class="<?php echo ($_SESSION['total_classifications'] == 4) ? 'topul owl-carousel owl-theme' : ''; ?>">
                                    <?php
                                    $i = 0;
                                    $classification_result = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1");
                                    while ($classification_row = $classification_result->fetch_array()) {
                                        $i++;
                                    ?>
                                        <p class="item" <?php echo ($_SESSION['total_classifications'] < 4) ? 'style="display:inline;"' : ''; ?>><?php if ($i > 1) { ?><span class="whitebar">l</span><?php } ?><a
                                                style="text-decoration: none; color: #fff;"
                                                href="<?php echo $base_url . '/' . $classification_row['slug']; ?>"
                                                class="topultxt"><?php echo $classification_row['name']; ?>
                                                Courses</a><?php if ($i == $total_classifications) { ?><span
                                                    class="whitebar">l</span><?php } ?></p>
                                    <?php } ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="img">
                        <img src="./assets/img/home-hero.png" alt="home-hero">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="text-center mrt5" id="homeadsec1"></div>
        </div>
    </section>
    <section class="free-course">
        <div class="container">
            <div class="home-free-course">
                <h3>What do You want to Learn?</h3>
            </div>
            <div class="free-courses-boxs">
                <div class="row">
                    <?php
                    $classification_row = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1")->fetch_array();
                    $categories_result = $db->query("SELECT cname, slug, icon, icon_alt, icon_title, icon_description FROM tbl_courses WHERE IStatus = 1 AND classification_id = " . $classification_row['id'] . " ORDER BY ordernumber ASC");
                    $count = 0;
                    while ($categories_row = $categories_result->fetch_array()) {
                        $count++;
                        $extraClass = ($count > 8) ? 'hidden-course' : '';
                    ?>
                        <div class="col-lg-3 course-item <?php echo $extraClass; ?>">
                            <a href="<?php echo $base_url; ?>/<?php echo $classification_row['slug'] . '/' . $categories_row['slug']; ?>">
                                <div class="free-box">
                                    <div class="img">
                                        <figure style="margin:0;">
                                            <img src="<?php echo $base_url; ?>/images/nd/category/<?php echo $categories_row['icon']; ?>" style="width:unset !important" width="37" height="33" alt="<?php echo $categories_row['icon_alt']; ?>" title = "<?php echo $categories_row['icon_title']; ?>">
                                            <figcaption class="vis-hid"><?php echo $categories_row['icon_description']; ?></figcaption>
                                        </figure>
                                    </div>
                                    <div class="name">
                                        <h4><?php echo $categories_row['cname']; ?></h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                
            </div>
            <div class="view-all-btn">
                <a href="javascript:void(0);" id="toggleCourses">View all Subjects</a>
            </div>
        </div>
    </section>
    <?php /*<section class="all-cources-slider">
        <div class="container">
            <div class="heading">
                <h3>Top Regulated Courses / University Pathway</h3>
            </div>
            <div class="slider">
                <div class="row">
                    <div class="owl-carousel owl-theme all-carousel">
                        <?php
                        // Fetch classification where name is "Regulated"
                        $regulate_classification = $db->query("SELECT * FROM classification WHERE name LIKE '%Regulated%' AND show_at_frontend = 1  LIMIT 1")->fetch_assoc();
                        if ($regulate_classification) {
                            // Fetch Regulated courses related to this classification
                            $regulate_course = $db->query("SELECT * FROM tbl_courses WHERE classification_id = " . (int)$regulate_classification['id'] . " AND IStatus = 1 ORDER BY ordernumber ASC LIMIT 1")->fetch_assoc();
                            if ($regulate_course) { // Check if a Regulated course exists
                                // Fetch Regulated diplomas related to the course
                                $regulate_diploma_result = $db->query("SELECT * FROM tbl_diploma WHERE cid = " . (int)$regulate_course['cid'] . " AND IStatus = 1 AND show_in_category = 1 ORDER BY ordernumber ASC");
                                if ($regulate_diploma_result->num_rows > 0) {
                                    while ($diploma = $regulate_diploma_result->fetch_assoc()) {
                        ?>
                                        <div class="item">
                                            <div class="card-data">
                                                <div class="cource-box">
                                                    <div class="img">
                                                        <img src="images/subject/<?php echo htmlspecialchars($diploma['thumbnail']); ?>" alt="<?php echo htmlspecialchars($diploma['name']); ?>">
                                                    </div>
                                                    <div class="data">
                                                        <div class="data-text">
                                                            <div class="head">
                                                                <h4><?php echo htmlspecialchars($diploma['name']); ?></h4>
                                                            </div>
                                                            <div class="stars">
                                                                <ul>
                                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                        <li><img src="./assets/img/Star-3.png"></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                            <div class="cource-data">
                                                                <div class="data-point">
                                                                    <div class="icon">
                                                                        <img src="./assets/img/bx-user.png">
                                                                    </div>
                                                                    <div class="name">
                                                                        <strong><?php echo (int)$diploma['admin_total_enrolled']; ?> Enrolled</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon">
                                                                        <img src="./assets/img/bx-laptop.png">
                                                                    </div>
                                                                    <div class="name">
                                                                        <strong>Online</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon">
                                                                        <img src="./assets/img/bx-time-five.png">
                                                                    </div>
                                                                    <div class="name">
                                                                        <strong>No timeframe - Self Paced</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="price">
                                                                <div class="current-fee">
                                                                    <strong>
                                                                        <?php
                                                                        if ($diploma['free_course'] == 1) {
                                                                            echo "FREE";
                                                                        } else {
                                                                            // Fetch price details
                                                                            $payment_result = $db->query("SELECT payment AS fullpayment, discount FROM tbl_payment WHERE diplomaid = " . (int)$diploma['diplomaid']);
                                                                            if ($payment_result->num_rows > 0) {
                                                                                $payment = $payment_result->fetch_assoc();
                                                                                $final_price = $payment['fullpayment'] - $payment['discount'];
                                                                                echo "Â£" . number_format($final_price, 2);
                                                                            } else {
                                                                                echo "Price Unavailable";
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </strong>
                                                                </div>
                                                                <?php if (!empty($payment)) { ?>
                                                                    <div class="previous-fee">
                                                                        <strike>Â£<?php echo number_format($payment['fullpayment'], 2); ?></strike>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="view-btn">
                                                                <a href="<?php echo htmlspecialchars($base_url . '/' . $regulate_classification['slug'] . '/' . $regulate_course['slug'] . '/' . $diploma['slug']); ?>">View Course >></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        <?php
                                    } // End diploma loop
                                } else {
                                    echo "<p>No Regulated courses found.</p>";
                                }
                            } else {
                                echo "<p>No Regulated courses found.</p>";
                            }
                        } else {
                            echo "<p>No Regulated courses found.</p>";
                        }
                        ?>
                    </div>
                </div>
                <div class="explore-btn">
                    <a href="<?php echo htmlspecialchars($base_url . '/' . $regulate_classification['slug']); ?>">View All Regulated Courses</a>
                </div>
            </div>
        </div>
    </section>*/ ?>
    <section class="all-cources-slider home-course">
        <div class="container">
            <div class="heading">
                <h3>Top Career-Boosting Courses for Professional Development (CPD Courses)</h3>
            </div>
            <div class="slider">
                <div class="row">
                    <div class="owl-carousel owl-theme all-carousel">
                        <?php
                        // Fetch classification where name is "CPD"
                        $cdb_classification = $db->query("SELECT * FROM classification WHERE name LIKE '%CPD%' AND show_at_frontend = 1 ORDER BY sort_order ASC LIMIT 1")->fetch_assoc();
                        if ($cdb_classification) {
                            // Fetch CPD courses related to this classification
                            $cpd_course = $db->query("SELECT * FROM tbl_courses WHERE classification_id = " . (int)$cdb_classification['id'] . " AND IStatus = 1 ORDER BY ordernumber ASC LIMIT 1")->fetch_assoc();
                            if ($cpd_course) { // Check if a CPD course exists
                                // Fetch CPD diplomas related to the course
                                $cpd_diploma_result = $db->query("SELECT * FROM tbl_diploma WHERE cid = " . (int)$cpd_course['cid'] . " AND IStatus = 1 AND show_in_category = 1 ORDER BY ordernumber ASC");
                                if ($cpd_diploma_result->num_rows > 0) {
                                    while ($diploma = $cpd_diploma_result->fetch_assoc()) {
                        ?>
                                        <div class="item">
                                            <div class="card-data">
                                                <div class="cource-box">
                                                    <div class="img">
                                                        <figure style="margin:0;">
                                                            <img src="images/subject/<?php echo htmlspecialchars($diploma['thumbnail']); ?>" alt="<?php echo htmlspecialchars($diploma['alternate']); ?>" title = "<?php echo htmlspecialchars($diploma['thumbnail_title']); ?>">
                                                            <figcaption class="visually-hidden"><?php echo htmlspecialchars($diploma['thumbnail_description']); ?></figcaption>
                                                        </figure>
                                                    </div>
                                                    <div class="data">
                                                        <div class="data-text">
                                                            <div class="head coname">
                                                                <h4><?php echo (strlen(htmlspecialchars($diploma['name'])) <= 30) ? htmlspecialchars($diploma['name']) : substr(htmlspecialchars($diploma['name']), 0, 30) . '...'; ?></h4>
                                                            </div>
                                                            <div class="stars">
                                                                <ul>
                                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                        <li><img src="./assets/img/Star-3.png"></li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                            <div class="cource-data">
                                                                <div class="data-point">
                                                                    <div class="icon">
                                                                        <img src="./assets/img/bx-user.png">
                                                                    </div>
                                                                    <div class="name">
                                                                        <strong><?php echo (int)$diploma['admin_total_enrolled']; ?> Enrolled</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon">
                                                                        <img src="./assets/img/bx-laptop.png">
                                                                    </div>
                                                                    <div class="name">
                                                                        <strong>Online</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="data-point">
                                                                    <div class="icon">
                                                                        <img src="./assets/img/bx-time-five.png">
                                                                    </div>
                                                                    <div class="name">
                                                                        <strong>No timeframe - Self Paced</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="price">
                                                                <div class="current-fee">
                                                                    <strong>
                                                                        <?php
                                                                        if ($diploma['free_course'] == 1) {
                                                                            echo "FREE";
                                                                        } else {
                                                                            // Fetch price details
                                                                            $payment_result = $db->query("SELECT payment AS fullpayment, discount FROM tbl_payment WHERE diplomaid = " . (int)$diploma['diplomaid']);
                                                                            if ($payment_result->num_rows > 0) {
                                                                                $payment = $payment_result->fetch_assoc();
                                                                                $final_price = $payment['fullpayment'] - $payment['discount'];
                                                                                echo "Â£" . number_format($final_price, 2);
                                                                            } else {
                                                                                echo "Price Unavailable";
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </strong>
                                                                </div>
                                                                <?php if (!empty($payment)) { ?>
                                                                    <div class="previous-fee">
                                                                        <strike>Â£<?php echo number_format($payment['fullpayment'], 2); ?></strike>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="view-btn">
                                                                <a href="<?php echo htmlspecialchars($base_url . '/' . $cdb_classification['slug'] . '/' . $cpd_course['slug'] . '/' . $diploma['slug']); ?>">View Course >></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        <?php
                                    } // End diploma loop
                                } else {
                                    echo "<p>No CPD courses found.</p>";
                                }
                            } else {
                                echo "<p>No CPD courses found.</p>";
                            }
                        } else {
                            echo "<p>No CPD courses found.</p>";
                        }
                        ?>
                    </div>
                </div>
                <div class="explore-btn">
                    <a href="<?php echo htmlspecialchars($base_url . '/' . $cdb_classification['slug']); ?>">View All CPD Courses</a>
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
                            <a href="apply.php">Enrol Now for Free</a>
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
                            <a href="apply.php">Enrol Now for Free</a>
                        </div>
                        <div class="left-img">
                            <img src="./assets/img/enroll-right.png" alt="enroll-now">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="all-cources-slider">
        <div class="container">
            <div class="heading">
                <h3>Learn for Free: Popular Free Courses</h3>
            </div>
            <div class="slider">
                <div class="row">
                    <div class="owl-carousel owl-theme all-carousel">
                        <?php
                        // Fetch only the free course
                        $free_diploma_result = $db->query("SELECT td.*, tc.slug AS catslug FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.IStatus = 1 AND (td.type = 2 OR td.type = 3) AND td.cid = tc.cid ORDER BY td.ordernumber ASC");
                        if ($free_diploma_result->num_rows > 0) {
                            while ($diploma = $free_diploma_result->fetch_assoc()) {
                        ?>
                                <div class="item">
                                    <div class="card-data">
                                        <div class="cource-box">
                                            <div class="img">
                                                <figure style="margin:0;">
                                                    <img src="images/subject/<?php echo htmlspecialchars($diploma['thumbnail']); ?>" alt="<?php echo htmlspecialchars($diploma['alternate']); ?>" title = "<?php echo $diploma['thumbnail_title']; ?>">
                                                    <figcaption class="visually-hidden"><?php echo $diploma['thumbnail_description']; ?></figcaption>
                                                </figure>
                                            </div>
                                            <div class="data">
                                                <div class="data-text">
                                                    <div class="head coname">
                                                        <h4><?php echo (strlen(htmlspecialchars($diploma['name'])) <= 30) ? htmlspecialchars($diploma['name']) : substr(htmlspecialchars($diploma['name']), 0, 30) . '...'; ?></h4>
                                                    </div>
                                                    <div class="stars">
                                                        <ul>
                                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                <li><img src="./assets/img/Star-3.png"></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <div class="cource-data">
                                                        <div class="data-point">
                                                            <div class="icon">
                                                                <img src="./assets/img/bx-user.png">
                                                            </div>
                                                            <div class="name">
                                                                <strong><?php echo (int)$diploma['admin_total_enrolled']; ?> Enrolled</strong>
                                                            </div>
                                                        </div>
                                                        <div class="data-point">
                                                            <div class="icon">
                                                                <img src="./assets/img/bx-laptop.png">
                                                            </div>
                                                            <div class="name">
                                                                <strong>Online</strong>
                                                            </div>
                                                        </div>
                                                        <div class="data-point">
                                                            <div class="icon">
                                                                <img src="./assets/img/bx-time-five.png">
                                                            </div>
                                                            <div class="name">
                                                                <strong>No timeframe - Self Paced</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="price">
                                                        <div class="current-fee">
                                                            <strong>
                                                                <?php
                                                                if ($diploma['free_course'] == 1) {
                                                                    echo "FREE";
                                                                } else {
                                                                    // Fetch price details
                                                                    $payment_result = $db->query("SELECT payment AS fullpayment, discount FROM tbl_payment WHERE diplomaid = " . (int)$diploma['diplomaid']);
                                                                    if ($payment_result->num_rows > 0) {
                                                                        $payment = $payment_result->fetch_assoc();
                                                                        $final_price = $payment['fullpayment'] - $payment['discount'];
                                                                        echo "Â£" . number_format($final_price, 2);
                                                                    } else {
                                                                        echo "FREE";
                                                                    }
                                                                }
                                                                ?>
                                                            </strong>
                                                        </div>
                                                        <?php if (isset($payment)) { ?>
                                                            <div class="previous-fee">
                                                                <strike>Â£<?php echo number_format($payment['fullpayment'], 2); ?></strike>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="view-btn">
                                                        <a href="<?php echo htmlspecialchars($base_url . '/courses/' . $diploma['catslug'] . '/' . $diploma['slug']); ?>">View Course >></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>No free course found.</p>";
                        }
                        ?>
                    </div>
                </div>
                <div class="explore-btn">
                    <a href="<?php echo htmlspecialchars($base_url . '/free-online-courses-with-certificates'); ?>">View All Free Courses</a>
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
                            <a href="apply.php">Enrol Now for Free</a>
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
                            <a href="apply.php">Enrol Now for Free</a>
                        </div>
                        <div class="left-img">
                            <img src="./assets/img/enroll-right.png" alt="enroll-now">
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
                        <?php /*<div class="head">
                            <h3>Free Online Courses with Certificates</h3>
                        </div>*/ ?>
                        <div class="para">
                            <?php
                            $homecon_row = $db->query("SELECT * FROM home_tab WHERE id = 1")->fetch_array();
                            echo $homecon_row['excerpt'];
                            ?>
                            <div id="moreText" style="display:none;"><?php echo $homecon_row['description']; ?></div>
                        </div>
                        <div class="read-more-btn">
                            <a href="javascript:void(0);" id="readMoreBtn">Read More +</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <iframe class="mx-auto d-block" width="400" height="315" src="https://www.youtube.com/embed/kDMNrObeOzg?si=g2D2LcXVztom-MG8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    <!--<div class="img">
                        <img src="./assets/img/home-online.png" alt="play-video">
                        <div class="play-btn">
                            <a href=""><img src="./assets/img/youtube.png"></a>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </section>
    <section class="learner-sider">
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
    <section class="faq">
        <div class="container" id="homefaqsec">
        </div>
    </section>
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
                                                <figure style="margin:0;">
                                                    <img src="<?php echo $base_url; ?>/images/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['alt']); ?>" title="<?php echo htmlspecialchars($blog['image_title']); ?>">
                                                    <figcaption class="visually-hidden"><?php echo htmlspecialchars($blog['image_description']); ?></figcaption>
                                                </figure>
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
    <section>
        <div class="container">
            <div class="text-center mrt5" id="homeadsec3"></div>
        </div>
    </section>
    <?php include 'includes/footer-new.php'; ?>
    <script>
        $(document).ready(function() {
            var carousel = $('#topslider');
            carousel.owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                rewindNav: true,
                dots: false,
                autoWidth: true,
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#toggleCourses").click(function() {
                $(".hidden-course").toggle();
                let isHidden = $(".hidden-course").is(":visible");
                $(this).text(isHidden ? "View less subjects" : "View all Subjects");
            });
        });
    // Begin readmore button code
    document.getElementById("readMoreBtn").addEventListener("click", function() {
        var moreText = document.getElementById("moreText");
        if (moreText.style.display === "none") {
          moreText.style.display = "inline";
          this.textContent = "Read less";
        } else {
          moreText.style.display = "none";
          this.textContent = "Read more";
        }
    });
    // End readmore button code
</script>
    </body>
</html>
