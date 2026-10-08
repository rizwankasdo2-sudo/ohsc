<?php include("admin/classes/connection.php"); ?>
<!DOCTYPE>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $qry = $db->query("select * from tbl_seo where seoid=3 and pagename='Accreditations'");
    $row = $qry->fetch_array();
    ?>
    <title><?php echo $row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:url" content="https://www.oxfordhomestudy.com/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="<?php echo $row['robots']; ?>" />
    <?php
    include("includes/header-new.php");
    $aboutqry = $db->query("SELECT * FROM `tbl_ohltv` WHERE ohlid = '2' ");
    $aboutresult = $aboutqry->fetch_array();
    ?>
    <!-- Hero section -->
    <section class="course-hero">
        <div class="container">
            <div class="course-hero-data">
                <div class="course-head">
                    <h2>Free Online Courses with <span>Certificates and Diplomas</span></h2>
                </div>
                <div class="course-serach">
                    <div class="input">
                        <input type="search" name="" id="" placeholder="What do you want to learn today?">
                        <i class="fa-solid fa-magnifying-glass"></i>
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
                                <img src="./assets/img/g-Star.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bar-box">
                    <div class="img">
                        <img src="./assets/img/learners.png">
                    </div>
                    <div class="text">
                        <p><strong>3 million+</strong> learners</p>
                    </div>
                </div>
                <div class="bar-box">
                    <div class="img">
                        <img src="./assets/img/learners.png">
                    </div>
                    <div class="text">
                        <p><strong>5 million+</strong> graduates</p>
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
                <h3>Explore 800+ Free Online Courses</h3>
            </div>
            <div class="free-courses-boxs">
                <div class="row">
                    <?php
                    $catdip_result = $db->query("SELECT td.cid, td.slug, tc.slug AS catslug, tc.cname, tc.icon FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.type = '3' AND td.cid = tc.cid ORDER BY tc.ordernumber ASC");
                    $count = 0;
                    while ($catdip_row = $catdip_result->fetch_array()) {
                        $category_image = (!empty($catdip_row['icon'])) ? "images/nd/category/" . $catdip_row['icon'] : "images/nd/category/default_cat_icon.webp";
                        $count++;
                    ?>
                        <div class="col-lg-3 category-item <?php echo ($count > 8) ? 'd-none' : ''; ?>">
                            <a href="<?php echo $base_url; ?>/courses/<?php echo $catdip_row['catslug']; ?>/<?php echo $catdip_row['slug']; ?>">
                                <div class="free-box">
                                    <div class="img">
                                        <img style="width: 30px !important" src="<?php echo $category_image; ?>" alt="<?php echo $catdip_row['cname']; ?>">
                                    </div>
                                    <div class="name">
                                        <h4><?php echo $catdip_row['cname']; ?></h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="view-all-btn text-center">
                <a href="javascript:void(0);" id="toggle-categories">View All</a>
            </div>
        </div>
    </section>
    <style>
        .nav-tabs.f-switch-btn {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #ddd transparent;
        }
        .nav-tabs.f-switch-btn .nav-link {
            white-space: nowrap;
            padding: 15px 15px;
            flex-shrink: 0;
        }
    </style>
    <section class="all-cources-slider">
        <div class="container">
            <div class="tabs-box">
                <nav>
                    <div class="nav nav-tabs f-switch-btn" id="nav-tab" role="tablist">
                        <?php
                        $cat_result = $db->query("SELECT DISTINCT tc.cid, tc.cname, tc.slug AS catslug FROM tbl_courses AS tc 
                                      JOIN tbl_diploma AS td ON tc.cid = td.cid 
                                      WHERE td.type = '3' ORDER BY tc.ordernumber ASC");
                        $first = true;
                        while ($cat_row = $cat_result->fetch_assoc()) {
                            $active = $first ? 'active' : '';
                            echo '<button class="nav-link ' . $active . '" id="tab-' . $cat_row['cid'] . '" 
                    data-bs-toggle="tab" data-bs-target="#content-' . $cat_row['cid'] . '" 
                    type="button" role="tab" aria-controls="content-' . $cat_row['cid'] . '" 
                    aria-selected="' . ($first ? 'true' : 'false') . '">' . $cat_row['cname'] . '</button>';
                            $first = false;
                        }
                        ?>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <?php
                    $cat_result->data_seek(0);
                    $first = true;
                    while ($cat_row = $cat_result->fetch_assoc()) {
                        $active = $first ? 'show active' : '';
                        echo '<div class="tab-pane fade ' . $active . '" id="content-' . $cat_row['cid'] . '" role="tabpanel">
                <div class="slider">
                    <div class="row">
                        <div class="owl-carousel owl-theme all-carousel">';
                        // Fetch diplomas under this category
                        $diploma_result = $db->query("SELECT name, slug, square_icon, admin_rating, admin_total_enrolled FROM tbl_diploma 
                                          WHERE cid = '{$cat_row['cid']}' AND type = '2'");
                        while ($diploma_row = $diploma_result->fetch_assoc()) {
                            $square_icon = !empty($diploma_row['square_icon']) ? $diploma_row['square_icon'] : 'dipimg.webp';
                            echo '<div class="item">
                    <div class="card-data">
                        <div class="cource-box">
                            <div class="img">
                                <img src="images/subject/' . $square_icon . '" alt="' . $diploma_row['name'] . '">
                            </div>
                            <div class="data">
                                <div class="data-text">
                                    <div class="head">
                                        <h4>' . $diploma_row['name'] . '</h4>
                                    </div>
                                    <div class="stars">
                                        <ul>';
                            for ($i = 0; $i < round($diploma_row['admin_rating']); $i++) {
                                echo '<li><img src="./assets/img/Star-3.png"></li>';
                            }
                            echo '            </ul>
                                    </div>
                                    <div class="cource-data">
                                        <div class="data-point">
                                            <div class="icon"><img src="./assets/img/bx-user.png"></div>
                                            <div class="name"><strong>' . number_format($diploma_row['admin_total_enrolled']) . ' Enrolled</strong></div>
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
                                        <div class="current-fee"><strong>FREE</strong></div>
                                        <div class="previous-fee"><strike>£130.00</strike></div>
                                    </div>
                                    <div class="view-btn">
                                        <a href="' . $base_url . '/courses/' . $cat_row['catslug'] . '/' . $diploma_row['slug'] . '">View Course &gt;&gt;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>';
                        }
                        echo '      </div> 
                </div>
                <div class="explore-btn">
                    <a href="#">Explore More Courses</a>
                </div>
              </div>
            </div>';
                        $first = false;
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php /*<!-- Begin Banner Section -->
    <section class="main_banner">
        <div class="main_banner_left"><a href="https://www.cpdcourses.com/" class="flxlink"><img src="<?php echo $pdots; ?>images/enroll_btn2.webp" width="188" height="54" class="enrbtn_left" border="0" alt="CPD" /></a></div>
        <div class="main_banner_right"><a href="https://www.learnartificialintelligence.ai/" class="flxlink"><img src="<?php echo $pdots; ?>images/enroll_btn1.webp" width="187" height="53" class="enrbtn_right" border="0" alt="LAI" /></a></div>
    </section>
    <!-- End Banner Section -->*/ ?>
    <?php /*<section class="enroll">
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
                            <a href="https://www.elearningcollege.com/lms">Enrol Now for Free</a>
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
                            <a href="https://www.elearningcollege.com/lms">Enrol Now for Free</a>
                        </div>
                        <div class="left-img">
                            <img src="./assets/img/enroll-right.png" alt="enroll-now">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>*/ ?>
    <section class="online-course">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="text">
                        <div class="para">
                            <?php
                            $upper_content_short = $aboutresult['short_description'];
                            $upper_content_full = $aboutresult['detail'];
                            if (strlen($upper_content_full) > 200) {
                                echo '<p>' . $upper_content_short . '</p>';
                                echo '<div class="text read-more-text d-none"><p>' . $upper_content_full . '</p></div>';
                            } else {
                                echo '<p>' . $upper_content_full . '</p>';
                            }
                            ?>
                        </div>
                        <?php if (strlen($upper_content_full) > 200) { ?>
                            <div class="read-more-btn">
                                <a href="javascript:void(0);" class="toggle-btn-more">Read More +</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="img">
                        <img src="./assets/img/play-video.png" alt="play-video">
                        <div class="play-btn">
                            <a href=""><img src="./assets/img/youtube.png"></a>
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
    <section class="course-slider Categories">
        <div class="container">
            <div class="heading">
                <h3>Explore Other Categories</h3>
            </div>
            <?php
            $cat_result = $db->query("SELECT tc.slug, tc.cname, td.slug AS catslug 
                                  FROM tbl_courses AS tc 
                                  INNER JOIN tbl_diploma AS td ON tc.cid = td.cid 
                                  WHERE td.type = '3' 
                                  ORDER BY tc.ordernumber ASC");
            $categories = [];
            while ($cat_row = $cat_result->fetch_array()) {
                $categories[] = $cat_row;
            }
            ?>
            <!-- First Owl Carousel -->
            <div class="owl-carousel owl-theme categories-slider" id="carousel1">
                <?php
                $count = 0;
                foreach ($categories as $category) {
                    if ($count < 8) { // First 8 categories
                        echo '<div class="category-item">
                            <a href="' . $base_url . '/courses/' . $category['slug'] . '/' .  $category['catslug'] . '">'
                            . $category['cname'] . '</a>
                          </div>';
                    }
                    $count++;
                }
                ?>
            </div>
            <!-- Second Owl Carousel -->
            <?php if (count($categories) > 8) { ?>
                <div class="owl-carousel owl-theme categories-slider" id="carousel2">
                    <?php
                    $count = 0;
                    foreach ($categories as $category) {
                        if ($count >= 8) { // Remaining categories
                            echo '<div class="category-item">
                                <a href="' . $base_url . '/courses/' . $category['slug'] . '/' . $category['catslug'] . '">'
                                . $category['cname'] . '</a>
                              </div>';
                        }
                        $count++;
                    }
                    ?>
                </div>
            <?php } ?>
            <!-- Shared Navigation Button -->
            <div class="c-nav">
                <button class="carousel-nav1"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="carousel-nav"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </section>
    <?php
    $related_blog_result = $db->query("SELECT title, slug, image, alt, image_title, image_description, publish_date 
                                   FROM blog 
                                   WHERE status = '1' AND show_in_free_courses = '1' 
                                   ORDER BY publish_date DESC 
                                   LIMIT 8");
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
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php
    $faqs_result = $db->query("SELECT * FROM tbl_faq WHERE IStatus = 1 AND show_in_free_courses = 1 ORDER BY ordernumber ASC");
    if ($faqs_result->num_rows > 0) {
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
                        while ($faqs_row = $faqs_result->fetch_array()) {
                            $collapse_id = "collapse" . $faq_index;
                        ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#<?php echo $collapse_id; ?>" aria-expanded="false" aria-controls="<?php echo $collapse_id; ?>">
                                        <?php echo $faqs_row['title']; ?>
                                    </button>
                                </h2>
                                <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p><?php echo $faqs_row['detail']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $faq_index++;
                        } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php
    }
    ?>
    <?php include("includes/footer-new.php"); ?>
    <script>
        $(".toggle-btn-more").click(function(e) {
            e.preventDefault();
            var moreTextDiv = $(this).closest(".text").find(".read-more-text");
            moreTextDiv.toggleClass("d-none"); // Toggle visibility
            // Change button text
            if (moreTextDiv.hasClass("d-none")) {
                $(this).text("Read More +");
            } else {
                $(this).text("Read Less -");
            }
        });
        $(document).ready(function() {
            $("#toggle-categories").click(function() {
                if ($(".category-item.d-none").length > 0) {
                    $(".category-item").removeClass("d-none");
                    $(this).text("View Less");
                } else {
                    $(".category-item").slice(8).addClass("d-none");
                    $(this).text("View All");
                }
            });
        });
    </script>
    </body>
</html>