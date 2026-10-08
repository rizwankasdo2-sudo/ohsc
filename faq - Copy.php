<?php
include("admin/classes/connection.php");
include("includes/constants.php");
include_once("includes/functions.php");
?>
<!DOCTYPE>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
    $qry = $db->query("select * from tbl_seo where seoid=9 and pagename='FAQ'");
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
    <section class="faq-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="faq-hero-data">
                        <h3>
                            <a href="faq.php" style="text-decoration:none; color:white">
                                Frequently Asked Questions (FAQs)
                            </a>
                        </h3>
                    </div>
                    <div class="hero-search">
                        <div class="search-bar">
                            <form class="search-form d-flex align-items-center" method="POST" action="#">
                                <input type="text" name="query" placeholder="Search FAQs" title="Enter search keyword">
                                <button type="submit" title="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="img">
                        <img src="./assets/img/faq.png" alt="faq-hero">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="faq faq-section">
        <div class="container">
            <div class="row">
                <?php include("includes/sidebar-new.php"); ?>
                <div class="col-lg-8 col-md-8">
                    <?php
                        $category_slug = isset($_GET['category']) ? $db->real_escape_string($_GET['category']) : '';
                        $category_id = 0;
                        
                        if (!empty($category_slug)) {
                            // Get course ID from slug
                            $course_qry = $db->query("SELECT cid, cname FROM tbl_courses WHERE slug = '$category_slug' LIMIT 1");
                            
                            if ($course_qry->num_rows > 0) {
                                $course_data = $course_qry->fetch_assoc();
                                $category_id = $course_data['cid'];
                                $category_name = $course_data['cname'];
                        
                                // Filter FAQs by course_id
                                $aboutqry = $db->query("SELECT * FROM tbl_faq WHERE IStatus = 1 AND category_id = $category_id ORDER BY ordernumber ASC");
                            } else {
                                // If slug is invalid, show no results or all depending on your preference
                                $aboutqry = $db->query("SELECT * FROM tbl_faq WHERE IStatus = 0"); // show nothing
                            }
                        } else {
                            // Show all FAQs if no category filter is set
                            $aboutqry = $db->query("SELECT * FROM tbl_faq WHERE IStatus = 1 ORDER BY ordernumber ASC");
                        }
                    // $aboutqry = $db->query("SELECT * FROM `tbl_faq` WHERE `IStatus`=1 ORDER BY ordernumber ASC");
                    if ($aboutqry->num_rows > 0) { // Show only if FAQs exist
                    ?>
                        <div class="faq-data">
                            <div class="accordion" id="accordionExample">
                                <?php
                                $counting = 0;
                                while ($aboutresult = $aboutqry->fetch_array()) {
                                    $collapse = "collapse" . $counting;
                                ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $counting; ?>">
                                            <button class="accordion-button collapsed"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#<?php echo $collapse; ?>"
                                                aria-expanded="false"
                                                aria-controls="<?php echo $collapse; ?>">
                                                <?php echo htmlspecialchars($aboutresult['title']); ?>
                                            </button>
                                        </h2>
                                        <div id="<?php echo $collapse; ?>"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p><?php echo $aboutresult['detail']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    $counting++;
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="faq-contact">
                        <div class="contact-data">
                            <div class="head">
                                <h3>Need more personalised assistance?</h3>
                            </div>
                            <div class="text">
                                <p>Everyone's different, and so are their questions. Speak to our team directly and they'll personally answer all your questions, no matter what they are.</p>
                            </div>
                            <div class="contact-btn">
                                <a href="https://www.oxfordhomestudy.com/contact">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include("includes/footer-new.php"); ?>
    </body>
</html>