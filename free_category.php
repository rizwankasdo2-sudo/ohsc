<?php
include("admin/classes/connection.php");
include_once("includes/functions.php");
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
    <?php if (!empty($_GET['page']) && (int) $_GET['page'] > 1) { ?>
    <meta name="robots" content="noindex, follow" />
    <?php } else { ?>
    <meta name="robots" content="<?php echo $seo_row['robots']; ?>" />
    <?php } ?>
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preload" as="image" href="<?php echo $pdots; ?>assets/img/bg.webp" fetchpriority="high">
    <link rel="preload" as="image" href="<?php echo $pdots; ?>assets/img/mob-hero.webp" fetchpriority="high">
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
    <main id="main-content">
        <div class="home_content" style="padding-bottom:10px;">
            <div class="row  clearfix">
                <ol class="breadcrumb">
                    <li><a href="<?php echo $base_url; ?>" class="gentxt_basic bluetxt">Home</a></li>
                    <li><a href="<?php echo $base_url; ?>/free-online-courses-with-certificates" class="gentxt_basic bluetxt">Free Courses</a></li>
                    <li><span class="active gentxt_basic">Courses</span></li>
                </ol>
            </div>
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
                <button class="toggle-btn read-btn-text">Read More +</button>
            </div>
        </div>
        <?php /*<!-- Hero section -->
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
                                    <button class="toggle-btn">Read More +</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        */
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8;
        $total_res   = $db->query("SELECT COUNT(*) AS cnt FROM tbl_diploma WHERE cid = '" . $cat_row['cid'] . "' AND type = 2");
        $total_row   = $total_res->fetch_assoc();
        $total_items = (int)$total_row['cnt'];
        $total_pages = $total_items > 0 ? ceil($total_items / $limit) : 1;
        ?>
        <section class="cources ">
            <div class="container">
                <div class="heading">
                    <h2>Popular <span><?php echo $cat_row['cname']; ?> Courses</span></h2>
                </div>
                <div class="cource-boxs row" id="diploma-list"></div>
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
        <section class="course-slider" id="crssldrsec"></section>
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
                                        <img src="../../assets/img/bxs-file-doc.webp" alt="File" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>CPD & Employer Approved</h4>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="../../assets/img/bxs-crown.webp" alt="Crown" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>Fully Endorsed and Professionally Recognised</h4>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="../../assets/img/bx-run.webp" alt="Run" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>100% Free â€“ No Fees or Hidden Costs</h4>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="../../assets/img/bxs-file-doc.webp" alt="File" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>Study Anytime, Anywhere at Your Own Pace</h4>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="../../assets/img/bxs-crown.webp" alt="Crown" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>Upskill and Advance Your Career for Free</h4>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="../../assets/img/bx-run.webp" alt="Run" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>Boost Your CV with Recognised Certificates</h4>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="../../assets/img/bxs-file-doc.webp" alt="File" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>Unlimited Access to Premium Course Materials</h4>
                                    </div>
                                </div>
                                <div class="data-point">
                                    <div class="c-icon">
                                        <img src="../../assets/img/bxs-crown.webp" alt="Crown" width="40" height="40">
                                    </div>
                                    <div class="text">
                                        <h4>No Cost, No Commitment, No Risk</h4>
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
                                                            <h2><?php echo $review_row['name'] . ' ' . $review_row['surname']; ?></h2>
                                                            <span>Verified Learner</span>
                                                        </div>
                                                        <div class="learn-mark">
                                                            <img src="<?php echo $pdots; ?>/assets/img/learn-mark.png" alt="">
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
                                                                    <img src="<?php echo $pdots; ?>/assets/img/<?php echo ($i <= $review_row['rating']) ? 'Star-3.png' : 'star.png'; ?>" alt="">
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
        $faqs_result = $db->query("SELECT * FROM tbl_faq WHERE IStatus = '1' AND course_id = " . $diplomaresult['diplomaid'] . " ORDER BY ordernumber ASC");
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
                                            <?php echo strip_tags($faq_row['title']); ?>
                                        </button>
                                    </h2>
                                    <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p><?php echo strip_tags($faq_row['detail']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php $faq_index++;
                            } ?>
                        </div>
                    </div>
            </section>
        <?php } ?>
        <section class="learner-bar">
            <div class="container">
                <div class="bar-boxes">
                    <div class="bar-box">
                        <div class="img">
                            <img src="../../assets/img/users.png" alt="" width="30" height="19">
                        </div>
                        <div class="text">
                            <p><strong>800+</strong> free courses</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="../../assets/img/learners.png" alt="" width="30" height="20">
                        </div>
                        <div class="text">
                            <p><strong>3 million+</strong> learners</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="../../assets/img/learners.png" alt="" width="30" height="20">
                        </div>
                        <div class="text">
                            <p><strong>2 million+</strong> graduates</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="../../assets/img/world.png" alt="" width="30" height="30">
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
                    <div class="banifits-text-box">
                        <div class="bani-heading">
                            <h2>800+ Free Online Courses to Choose From</h2>
                            <span>A <strong>free online course from Oxford Home Study Centre (OHSC)</strong> is perfect for anyone who wants to:</span>
                        </div>
                        <div class="text-points">
                            <?php
                            $course_points = [
                                "<strong>Pursue a promotion</strong> and progress in their current career",
                                "<strong>Explore a new professional path</strong> or discover a fresh area of interest",
                                "<strong>Refresh and strengthen existing skills</strong> to stay competitive in todayâ€™s job market",
                                "<strong>Prepare for advanced studies</strong> or higher-level qualifications in the future",
                                "<strong>Start a business or work independently</strong> as a freelancer or entrepreneur"
                            ];
                            foreach ($course_points as $point) { ?>
                                <div class="point">
                                    <div class="icon"><i class="fa-solid fa-check"></i></div>
                                    <div class="text">
                                        <p><?php echo $point; ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="bani-heading"><span><strong>Choose Your Free Course Today â€“ Learn Anytime, Anywhere!</strong></span></div>
                        </div>
                        <div class="start-btn">
                            <a href="<?php echo $base_url; ?>/free-online-courses-with-certificates">Choose Your Free Course Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blogs" id="blg_content"></section>
    </main>
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
        // Begin pagination handling
        $(document).on("click", ".diploma-pagination .page-link", function (e) {
            e.preventDefault();
            var page = $(this).data("page");
            $.ajax({
                url: "<?php echo $pdots; ?>ajax/general.php?task=show_free_course_category_page&cid=<?php echo $diplomaresult['cid']; ?>",
                type: "GET",
                data: { page: page },
                beforeSend: function () {
                    $("#diploma-list").html("<div class='col-12'><p>Loading...</p></div>");
                },
                success: function (res) {
                    $("#diploma-list").html(res);
                    $(".diploma-pagination").load("<?php echo $pdots; ?>ajax/general.php?task=show_free_course_category_pagination&cid=<?php echo $diplomaresult['cid']; ?>&page=" + page);
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
        fetch("<?php echo $pdots; ?>ajax/general.php?task=show_free_course_category_page&page=1&cid=<?php echo $diplomaresult['cid']; ?>")
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
