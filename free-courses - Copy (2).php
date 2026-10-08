<?php
include("admin/classes/connection.php");
include_once("includes/functions.php");
?>
<!DOCTYPE HTML>
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
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preload" as="image" href="../assets/img/bg.webp" fetchpriority="high">
    <link rel="preload" as="image" href="../assets/img/mob-hero.webp" fetchpriority="high">
    <style>
    .hidden-course {
        display: none;
    }
    </style>
    <?php
    include("includes/header-new.php");
    $aboutqry = $db->query("SELECT * FROM `tbl_ohltv` WHERE ohlid = '2' ");
    $aboutresult = $aboutqry->fetch_array();
    ?>
    <main id="main-content">
        <div class="home_content" style="padding-bottom:50px;">
            <div class="row  clearfix">
                <ol class="breadcrumb">
                    <li><a href="<?php echo $base_url; ?>" class="gentxt_basic bluetxt">Home</a></li>
                    <li><span class="active gentxt_basic">Free Courses</span></li>
                </ol>
            </div>
            <div class="heading">
                <h2>Free Online Courses with Certificates and Diplomas</h2>
            </div>
        </div>
        <?php /*<!-- Hero section -->
        <section class="course-hero">
            <div class="container">
                <div class="course-hero-data">
                    <div class="course-head">
                        <h2>Free Online Courses with <span>Certificates and Diplomas</span></h2>
                    </div>
                    <div class="course-serach">
                        <div class="input">
                            <form class="d-flex" method="post" action="<?php echo $base_url ?>/search.php" name="searchfrm" role="search">
                                <input type="search" name="course_name" placeholder="What do you want to learn today?" autocomplete="off">
                                <i class="fa-solid fa-magnifying-glass curp" onclick="this.closest('form').requestSubmit();"></i>
                            </form>
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
                                            <img src="./assets/img/star.png" alt="Star" width="18" height="17">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="./assets/img/star.png" alt="Star" width="18" height="17">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="./assets/img/star.png" alt="Star" width="18" height="17">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="./assets/img/star.png" alt="Star" width="18" height="17">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="star-box">
                                            <img src="./assets/img/star.png" alt="Star" width="18" height="17">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="trustpliot-star">
                                <div class="icon">
                                    <img src="./assets/img/g-star.png" alt="Star" width="136" height="26">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="./assets/img/learners.png" alt="Learners" width="30" height="20">
                        </div>
                        <div class="text">
                            <p><strong>3 million+</strong> learners</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="./assets/img/learners.png" alt="Learners" width="30" height="20">
                        </div>
                        <div class="text">
                            <p><strong>2 million+</strong> graduates</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="./assets/img/world.png" alt="World" width="30" height="30">
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
                    <div class="row" id="freecatcon"></div>
                </div>
                <div class="view-all-btn text-center">
                    <button id="toggleCourses">View All Subjects</button>
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
        <?php
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8;
        $total_res   = $db->query("SELECT COUNT(*) AS cnt FROM tbl_diploma WHERE type = 2");
        $total_row   = $total_res->fetch_assoc();
        $total_items = (int)$total_row['cnt'];
        $total_pages = $total_items > 0 ? ceil($total_items / $limit) : 1;
        ?>
        <section class="all-cources-slider">
            <div class="container">
                <div class="row" id="diploma-list"></div>
                <?php if ($total_pages > 1): ?>
                <div class="row">
                    <div class="col-12">
                        <ul class="pagination diploma-pagination justify-content-center mt-4">
                            <!-- First Page -->
                            <li class="page-item <?= ($current_page == 1 ? 'disabled' : '') ?>">
                                <a href="#" class="page-link" data-page="1">&laquo; First</a>
                            </li>
                            <?php
                            // Sliding window logic
                            $max_links = 5;
                            $start = max(1, $current_page - 2);
                            $end   = min($total_pages, $start + $max_links - 1);
                            // Adjust start if near end
                            if ($end - $start < $max_links - 1) {
                                $start = max(1, $end - $max_links + 1);
                            }
                            for ($p = $start; $p <= $end; $p++): ?>
                                <li class="page-item <?= $p == $current_page ? 'active' : '' ?>">
                                    <a href="#" class="page-link" data-page="<?= $p ?>">
                                        <?= $p ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <!-- Last Page -->
                            <li class="page-item <?= ($current_page == $total_pages ? 'disabled' : '') ?>">
                                <a href="#" class="page-link" data-page="<?= $total_pages ?>">Last &raquo;</a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </section>
        <?php /*<!-- Begin Banner Section -->
        <section class="main_banner">
            <div class="main_banner_left"><a href="https://www.cpdcourses.com/" class="flxlink"><img src="<?php echo $pdots; ?>images/enroll_btn2.webp" width="188" height="54" class="enrbtn_left" border="0" alt="CPD" /></a></div>
            <div class="main_banner_right"><a href="https://www.learnartificialintelligence.ai/" class="flxlink"><img src="<?php echo $pdots; ?>images/enroll_btn1.webp" width="187" height="53" class="enrbtn_right" border="0" alt="LAI" /></a></div>
        </section>
        <!-- End Banner Section -->*/ ?>
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
                                    <button href="javascript:void(0);" class="toggle-btn-more">Read More +</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-6" id="display_youtube"></div>
                </div>
            </div>
        </section>
        <section class="learner-sider">
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
                                                            <img src="./assets/img/learn-mark.png" alt="Learn">
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
                                                                    <img src="./assets/img/<?php echo ($i <= $review_row['rating']) ? 'Star-3.png' : 'star.png'; ?>" alt="Star">
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
        <?php
            $faq_result = $db->query("SELECT * FROM tbl_faq WHERE IStatus = 1 AND show_in_free_courses = 1 ORDER BY ordernumber ASC");
            if($faq_result->num_rows > 0) {
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
                                                <?php echo $faq_row['title']; ?>
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
            } // End if record found
        ?>
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
                    <button class="carousel-nav1" aria-label="Previous slide"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="carousel-nav" aria-label="Next slide"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </section>
        <section class="blogs" id="blg_content"></section>
    </main>
    <?php include("includes/footer-new.php"); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        $("#toggleCourses").click(function() {
            $(".hidden-course").toggle();
            let isHidden = $(".hidden-course").is(":visible");
            $(this).text(isHidden ? "View Less Subjects" : "View All Subjects");
        });
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
        // Begin pagination handling
        $(document).on("click", ".diploma-pagination .page-link", function (e) {
            e.preventDefault();
            var page = $(this).data("page");
            $.ajax({
                url: "<?php echo $pdots; ?>ajax/general.php?task=show_free_course_page",
                type: "GET",
                data: { page: page },
                beforeSend: function () {
                    $("#diploma-list").html("<div class='col-12'><p>Loading...</p></div>");
                },
                success: function (res) {
                    $("#diploma-list").html(res);
                    $(".diploma-pagination").load("<?php echo $pdots; ?>ajax/general.php?task=show_free_course_pagination&page=" + page);
                    $(".diploma-pagination .page-item").removeClass("active");
                    $('.diploma-pagination .page-link[data-page="' + page + '"]')
                        .closest(".page-item")
                        .addClass("active");
                },
                error: function () {
                    $("#diploma-list").html("<div class='col-12'><p>Error loading data.</p></div>");
                }
            });
        });
        fetch("<?php echo $pdots; ?>ajax/general.php?task=show_free_course_page&page=1")
        .then(r => r.text())
        .then(html => {
            requestAnimationFrame(() => {
            document.querySelector("#diploma-list").innerHTML = html;
            });
        })
        .catch(err => console.error("Failed to load section:", err));
        // End pagination handling
    });
    </script>
    </body>
</html>
