<?php
include("admin/classes/connection.php");
require_once('includes/functions.php');
$qry = $db->query("select * from tbl_seo where seoid=3 and pagename='Accreditations'");
$row = $qry->fetch_array();
$aboutqry = $db->query("SELECT * FROM `tbl_ohltv` WHERE ohlid = '2' ");
$aboutresult = $aboutqry->fetch_array();
?>
<!doctype html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:url" content="/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="<?php echo $row['robots']; ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <?php include 'includes/header-new.php'; ?>
    <main id="main-content">
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "Oxford Home Study Centre",
                "url": "/",
                "address": "Business Box, Oswin Road, UK",
                "sameAs": [
                    "https://www.facebook.com/oxfordhomestudy/",
                    "https://twitter.com/OHSCUK",
                    "https://www.linkedin.com/company-beta/13375044/",
                    "https://www.instagram.com/ohscuk/"
                ]
            }
        </script>

        <div class="home_content" style="padding-bottom:10px;">
            <div class="row  clearfix">
                <ol class="breadcrumb">
                    <li><a href="<?php echo $base_url; ?>" class="gentxt_basic bluetxt"><strong>Home</strong></a></li>
                    <li><span class="active gentxt_basic"><strong>Free Online Courses</strong></span></li>
                </ol>
            </div>
        </div>
        <form method="post" action="<?php echo $base_url . '/courses'; ?>" name="freeFrm" id="freeFrm">
            <input type="hidden" name="course_name" value="" />
            <input type="hidden" name="type[]" value="2" />
        </form>
        <!-- Begin main section -->
        <form method="post" action="<?php echo $base_url . '/courses'; ?>" name="homeFrm" id="homeFrm">
            <input type="hidden" name="from_index" value="1" />
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
                    <div class="blcon">
                        <a href="<?php echo $base_url . '/courses'; ?>" class="bluelink">All Courses</a>
                    </div>
                    <div class="pdl10">
                        <a class="bluelink curp" onclick="document.freeFrm.submit();">Free Online Courses</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="container mrt50 text">
            <div class="newtxt mrt15">
                <?php
                $upper_content_short = $aboutresult['short_description'];
                $upper_content_full = $aboutresult['detail'];
                if (strlen($upper_content_full) > 200) {
                    echo '<p>' . $upper_content_short . '</p>';
                    echo '<div class="text read-more-text d-none"><p>' . $upper_content_full . '</p></div><a href="javascript:void(0);" class="toggle-btn-more">Read More +</a>';
                } else {
                    echo '<p>' . $upper_content_full . '</p>';
                }
                ?>
            </div>
        </div>
        <div class="text-center mrt15">
            <button class="gsubmit" onclick="document.freeFrm.submit();" style="width:auto;">View All Free Courses</button>
        </div>
        <div class="container-fluid mrt20">
            <div class="row free-course">
                <h2 class="text-center mrt50"><strong>Browse Free Online Courses by Category:</strong></h2>
                <div class="free-courses-boxs">
                    <div class="row">
                        <?php
                        $categories_result = $db->query("SELECT cname, slug, icon, icon_alt, icon_title, icon_description FROM tbl_courses WHERE IStatus = 1 AND previous_free_category = 1 AND parent_ids = ''  ORDER BY ordernumber ASC");
                        $count = 0;
                        while ($categories_row = $categories_result->fetch_array()) {
                            $count++;
                            $extraClass = ($count > 8) ? 'hidden-course' : '';
                        ?>
                            <div class="col-lg-3 <?php echo $extraClass; ?>">
                                <a href="<?php echo $base_url . '/' . $categories_row['slug']; ?>">
                                    <div class="free-box">
                                        <div class="img">
                                            <figure style="margin:0;">
                                                <img src="<?php echo $base_url; ?>/images/nd/category/<?php echo $categories_row['icon']; ?>" style="max-width:33px;height:auto;aspect-ratio:33/33;" width="33" height="33" alt="" title = "<?php echo $categories_row['icon_title']; ?>">
                                                <figcaption class="vis-hid"><?php echo $categories_row['icon_description']; ?></figcaption>
                                            </figure>
                                        </div>
                                        <div class="name">
                                            <h2><?php echo $categories_row['cname']; ?></h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    
                </div>
                <div class="view-all-btn">
                    <button id="toggleCourses">View All Categories</button>
                </div>
            </div>
        </div>
        <!-- End main section -->
        <section class="learner-bar mt-5">
            <div class="container">
                <div class="bar-boxes">
                    <div class="bar-box">
                        <div class="img">
                            <img src="<?php echo $baseurl; ?>/assets/img/users.png" alt="" width="30" height="19">
                        </div>
                        <div class="text">
                            <p><strong>800+</strong> courses</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="<?php echo $baseurl; ?>/assets/img/learners.png" alt="" width="30" height="20">
                        </div>
                        <div class="text">
                            <p><strong>3M+</strong> Learners</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="<?php echo $baseurl; ?>/assets/img/learners.png" alt="" width="30" height="20">
                        </div>
                        <div class="text">
                            <p><strong>2M+</strong> Completions</p>
                        </div>
                    </div>
                    <div class="bar-box">
                        <div class="img">
                            <img src="<?php echo $baseurl; ?>/assets/img/world.png" alt="" width="30" height="30">
                        </div>
                        <div class="text">
                            <p><strong>195</strong> Countries</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
            $faq_query = "SELECT * FROM tbl_faq WHERE IStatus = 1 AND show_in_free_courses <> 0 ORDER BY ordernumber ASC";
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
        <section class="blogs" id="blg_content"></section>
    </main>
    <?php include 'includes/footer-new.php'; ?>
    <script>
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
    document.addEventListener('DOMContentLoaded', function () {
        $(".toggle-btn-more").click(function(e) {
            e.preventDefault();
            var moreTextDiv = $(this).closest(".text").find(".read-more-text");
            moreTextDiv.toggleClass("d-none");
            if (moreTextDiv.hasClass("d-none")) {
                $(this).text("Read More +");
            } else {
                $(this).text("Read Less -");
            }
        });
        $("#toggleCourses").click(function() {
            $(".hidden-course").toggle();
            let isHidden = $(".hidden-course").is(":visible");
            $(this).text(isHidden ? "View Less Categories" : "View All Categories");
        });
    });
    </script>
</html>
