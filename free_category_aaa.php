<?php
include("admin/classes/connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php
    $seo_result = $db->query("SELECT * FROM tbl_caourseseo WHERE diplomaid = '" . $diplomaresult['diplomaid'] . "' ");
    $seo_row = $seo_result->fetch_array();
    $cat_result = $db->query("SELECT * FROM tbl_courses WHERE cid = '" . $diplomaresult['cid'] . "' ");
    $cat_row = $cat_result->fetch_array();
    $subject_result = $db->query("SELECT courseoverview, main FROM tbl_subject WHERE diplomaid = '" . $diplomaresult['diplomaid'] . "' ");
    $subject_row = $subject_result->fetch_array();
    ?>
    <title><?php echo $seo_row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($seo_row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($seo_row['metadescription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($seo_row['metadescription']); ?>" />
    <meta property="og:url" content="https://www.oxfordhomestudy.com/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $seo_row['metatitle']; ?>" />
    <meta name="robots" content="<?php echo $seo_row['robots']; ?>" />
    <?php include 'includes/header-new.php'; ?>
    <style>
        .new-style {
            text-align: center;
            margin: 0 7.3px;
            padding: 5px;
            display: table-cell !important;
        }
        .new-style a {
            color: #333;
            text-decoration: none;
            background-color: #F0F0F0;
            display: block !important;
            padding: 10px 14.9px;
            height: 49px;
            font-weight: 600;
            line-height: 25px;
            letter-spacing: 0.01em;
            border-radius: 7px;
            font-size: 17px;
        }
        .carousel-nav1,
        .carousel-nav {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: #FFFFFF;
            border: none;
            box-shadow: 1px 4px 4px 0px #00000040;
            color: #D74826;
            font-size: 18px;
            z-index: 99;
        }
        .carousel-nav {
            position: absolute;
            right: 110px;
            margin-top: -90px;
        }
        .carousel-nav1 {
            position: absolute;
            left: 112px;
            margin-top: -90px;
        }
    </style>
    <!-- Hero section -->
    <section class="hero">
        <div class="container">
            <div class="hero-badge">
                <div class="main-page">
                    <strong>Free Courses</strong>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
                <div class="next-page">
                    <a href=""><?php echo $cat_row['cname']; ?></a>
                </div>
            </div>
            <div class="hero-data">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="hero-text">
                            <div class="text">
                                <?php
                                echo  $subject_row['main'];
                                ?>
                            </div>
                            <div class="text read-more-text d-none">
                                <?php
                                echo  $subject_row['courseoverview'];
                                ?>
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
    <section class="cources ">
        <div class="container">
            <div class="heading">
                <h3>Popular <span><?php echo $cat_row['cname']; ?> Courses</span></h3>
            </div>
            <!--  -->
            <div class="slider">
                <div class="row">
                    <div class="owl-carousel owl-theme all-carousel cource-boxs">
                        <?php
                        $diploma_result = $db->query("SELECT * FROM tbl_diploma WHERE cid = '" . $cat_row['cid'] . "' AND type = '2'");
                        if ($diploma_result->num_rows > 0) {
                            while ($diploma = $diploma_result->fetch_assoc()) {
                                $square_icon = !empty($diploma['square_icon']) ? $diploma['square_icon'] : 'dipimg.webp';
                        ?>
                                <div class="item">
                                    <div class="card-data">
                                        <div class="cource-box">
                                            <div class="img">
                                                <img src="<?php echo $base_url; ?>/images/subject/<?php echo htmlspecialchars($square_icon); ?>" alt="<?php echo htmlspecialchars($diploma['name']); ?>">
                                            </div>
                                            <div class="data">
                                                <div class="data-text">
                                                    <div class="head coname">
                                                        <h4><?php echo (strlen(htmlspecialchars($diploma['name'])) <= 30) ? htmlspecialchars($diploma['name']) : substr(htmlspecialchars($diploma['name']), 0, 30) . '...'; ?></h4>
                                                    </div>
                                                    <div class="stars">
                                                        <ul>
                                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                                <li><img src="<?php echo $pdots; ?>/assets/img/Star-3.png"></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <div class="cource-data">
                                                        <div class="data-point">
                                                            <div class="icon">
                                                                <img src="<?php echo $pdots; ?>/assets/img/bx-user.png">
                                                            </div>
                                                            <div class="name">
                                                                <strong><?php echo (int)$diploma['admin_total_enrolled']; ?> Enrolled</strong>
                                                            </div>
                                                        </div>
                                                        <div class="data-point">
                                                            <div class="icon">
                                                                <img src="<?php echo $pdots; ?>/assets/img/bx-laptop.png">
                                                            </div>
                                                            <div class="name">
                                                                <strong>Online</strong>
                                                            </div>
                                                        </div>
                                                        <div class="data-point">
                                                            <div class="icon">
                                                                <img src="<?php echo $pdots; ?>/assets/img/bx-time-five.png">
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
                                                        <a href="<?php echo htmlspecialchars($base_url . '/courses/' . $cat_row['slug'] . '/' . $diploma['slug']); ?>">View Course >></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>No diplomas found for this category.</p>";
                        }
                        ?>
                    </div>
                </div>
                <div class="cource-bottom">
                    <div class="show-more">&nbsp;</div>
                    <div class="show-pages">
                        <span>Showing 1-<?php echo $diploma_result->num_rows . ' of ' . $diploma_result->num_rows ?> Courses</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="course-slider">
        <div class="container">
            <div class="heading">
                <h3>Explore Related <span>Course Categories</span></h3>
            </div>
            <div class="slider">
                <?php
                $classification_row = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1")->fetch_array();
                $categories_result = $db->query("
                    SELECT 
                        c.cname, 
                        c.slug, 
                        c.icon, 
                        COUNT(d.diplomaid) AS course_count 
                    FROM tbl_courses AS c
                    LEFT JOIN tbl_diploma AS d ON c.cid = d.cid
                    WHERE c.IStatus = 1 
                    AND c.classification_id IN (SELECT id FROM classification WHERE show_at_frontend = 1)
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
                                            <a href="<?php echo $base_url; ?>/<?php echo $classification_row['slug'] . '/' . $category['slug']; ?>">
                                                <div class="card-body">
                                                    <div class="icon">
                                                        <img src="<?php echo $base_url; ?>/images/nd/category/<?php echo $category['icon']; ?>" width="37" height="33" alt="<?php echo $category['cname']; ?>">
                                                    </div>
                                                    <div class="text">
                                                        <h4><?php echo $category['cname']; ?></h4>
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
                            <?php
                            // Fetch benefits dynamically from the database
                            $benefit_result = $db->query("SELECT heading, img FROM benefits ORDER BY sort_order ASC");
                            if ($benefit_result->num_rows > 0) {
                                while ($benefit_row = $benefit_result->fetch_assoc()) { ?>
                                    <div class="data-point">
                                        <div class="icon"><i class="fa-solid fa-check"></i></div>
                                        <div class="text">
                                            <h4><?php echo htmlspecialchars($benefit_row['heading']); ?></h4>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<p>No benefits available.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="img">
                            <img src="<?php echo $pdots; ?>/assets/img/OHSC Certificate 1.png" alt="certificate">
                        </div>
                    </div>
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
                                                            <img src="<?php echo $pdots; ?>/images/form_photo/<?php echo $review_row['photo']; ?>" alt="learner" width="64" height="64" style="border-radius:50%;">
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
                                                        <img src="<?php echo $pdots; ?>/assets/img/learn-mark.png">
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
                                                                <img src="<?php echo $pdots; ?>/assets/img/<?php echo ($i <= $review_row['rating']) ? 'Star-3.png' : 'star.png'; ?>">
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
    <section class="learner-bar">
        <div class="container">
            <div class="bar-boxes">
                <div class="bar-box">
                    <div class="img">
                        <img src="./assets/img/users.png">
                    </div>
                    <div class="text">
                        <p><strong>4500+</strong> free courses</p>
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
    <?php
    $benefit_result = $db->query("SELECT heading, description, img FROM benefits ORDER BY sort_order ASC");
    ?>
    <section class="course-benifits">
        <div class="container">
            <div class="bani-data">
                <div class="benifits-box">
                    <div class="bani-head">
                        <h4>Course Benefits</h4>
                    </div>
                    <div class="bani-box-details">
                        <div class="bani-points">
                            <div class="row">
                                <?php while ($benefit_row = $benefit_result->fetch_assoc()) { ?>
                                    <div class="col-lg-6">
                                        <div class="point m-3">
                                            <div class="icon"><i class="fa-solid fa-check"></i></div>
                                            <div class="text">
                                                <h4><?php echo htmlspecialchars($benefit_row['heading']); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banifits-text-box">
                    <div class="bani-heading">
                        <h4>100+ Courses to choose from.</h4>
                        <span>A free online course from OHSC could be ideal for anyone looking to:</span>
                    </div>
                    <div class="text-points">
                        <?php
                        $course_points = [
                            "Pursue promotion in their current line of work",
                            "Explore an entirely new career path",
                            "Brush up on existing knowledge, skills and professional traits",
                            "Pave the way for further studies at a much higher level",
                            "Start their own business from scratch or set up as freelancer"
                        ];
                        foreach ($course_points as $point) { ?>
                            <div class="point">
                                <div class="icon"><i class="fa-solid fa-check"></i></div>
                                <div class="text">
                                    <p><?php echo $point; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="start-btn">
                        <a href="https://www.elearningcollege.com/lms">Start Your Free Course Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    $related_blog_result = $db->query("SELECT title, slug, image, alt, publish_date 
                                   FROM blog 
                                   WHERE status = '1' AND show_in_free_courses = '1' 
                                   ORDER BY publish_date DESC LIMIT 8");
    if ($related_blog_result->num_rows > 0) { ?>
        <section class="blogs">
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
                                                <img src="<?php echo $base_url; ?>/images/blog/<?php echo htmlspecialchars($blog['image']); ?>"
                                                    alt="<?php echo htmlspecialchars($blog['alt']); ?>">
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
                                                    <a href="<?php echo $base_url; ?>/OHSC-Blog/<?php echo htmlspecialchars($blog['slug']); ?>">
                                                        Read More +
                                                    </a>
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
    <?php } ?>
    <?php
    $faqs_result = $db->query("SELECT * FROM tbl_faq WHERE IStatus = '1' AND show_in_free_courses = '1' ORDER BY ordernumber ASC");
    if ($faqs_result->num_rows > 0) { ?>
        <section class="faq">
            <div class="container">
                <div class="heading">
                    <h3>Frequently Asked Questions (FAQs)</h3>
                </div>
                <div class="faq-data">
                    <div class="accordion" id="accordionExample">
                        <?php
                        $faq_index = 1;
                        while ($faq_row = $faqs_result->fetch_assoc()) {
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
                                        <p><?php echo htmlspecialchars($faq_row['detail']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php $faq_index++;
                        } ?>
                    </div>
                </div>
        </section>
    <?php } ?>
    <?php include 'includes/footer-new.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.querySelector(".toggle-btn");
            const readMoreText = document.querySelector(".read-more-text");
            toggleBtn.addEventListener("click", function() {
                if (readMoreText.classList.contains("d-none")) {
                    readMoreText.classList.remove("d-none");
                    toggleBtn.textContent = "Read Less -";
                } else {
                    readMoreText.classList.add("d-none");
                    toggleBtn.textContent = "Read More +";
                }
            });
        });
    </script>
    </body>
</html>