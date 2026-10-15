<?php
include("admin/classes/connection.php");
include_once("includes/functions.php");
require_once('includes/blog-landing-helpers.php');

if (!isset($_GET['slug'])) {
    header('HTTP/1.1 404 Not Found');
    header("Refresh:0; url={$base_url}/page-not-found");
    exit();
}

$slug = preg_replace('/[^a-z0-9\-]/i', '', (string) $_GET['slug']);
$category_slug = isset($_GET['category_slug']) ? preg_replace('/[^a-z0-9\-]/i', '', (string) $_GET['category_slug']) : '';

if ($slug === '') {
    header('HTTP/1.1 404 Not Found');
    header("Refresh:0; url={$base_url}/page-not-found");
    exit();
}

$blog = loadPublishedBlogPostBySlug($db, $slug, $category_slug);

if (!$blog) {
    header('HTTP/1.1 404 Not Found');
    header("Refresh:0; url={$base_url}/page-not-found");
    exit();
}
$ad_content = '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8850775732328921"
crossorigin="anonymous"></script>
<!-- Horizontal Ads 1 -->
<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-8850775732328921"
data-ad-slot="1209776678"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $blog['meta_title']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($blog['meta_title']); ?>" />
    <meta name="description" content="<?php echo strip_tags($blog['meta_description']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($blog['meta_description']); ?>" />
    <meta property="og:url" content="/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo strip_tags($blog['meta_keywords']); ?>" />
    <meta name="robots" content="<?php echo $blog['robots']; ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL('/OHSC-Blog/' . $slug), ENT_QUOTES, 'UTF-8'); ?>">
    <?php
    include("includes/stylesheets.php");
    include("includes/header.php");
    ?>
    <div class="home_content">
        <div class="row  clearfix">
            <ol class="breadcrumb">
                <li><a href="<?php echo $base_url; ?>" class="gentxt_basic bluetxt">Home</a></li>
                <li><a href="<?php echo $base_url.'/OHSC-Blog'; ?>" class="gentxt_basic bluetxt">Blog</a></li>
                <li><span class="active gentxt_basic"><?php echo $blog['title']; ?></span></li>
            </ol>
            <div class="col-lg-9">
                <div class="panel panel-info page_content">
                    <div class="panel-heading" style="padding:1px 15px;">
                        <span class="course_header gentxt_fbasic"><?php echo $blog['title']; ?></span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <!-- Go to www.addthis.com/dashboard to customize your tools -->
                            <div id="rite-cotainer" style="font-family:Helvetica,Arial,sans-serif;">
                                <div style="width: 90%; margin: 15px auto;">
                                    <?php list($temp_width, $temp_height) = getimagesize(dirname(__FILE__) . '/images/blog/' . $blog['image']); ?>
                                    <figure style="margin:0;">
                                        <img src="<?php echo $base_url; ?>/images/blog/<?php echo $blog['image']; ?>"
                                        class="img-responsive" alt="<?php echo $blog['alt']; ?>" 
                                        title="<?php echo $blog['image_title']; ?>" style="width: 55%; margin: auto;" width="<?php echo $temp_width; ?>" height="<?php echo $temp_height; ?>" loading="lazy" />
                                        <figcaption class="visually-hidden"><?php echo $blog['image_description']; ?></figcaption>
                                    </figure>
                                </div>
                                <div style="padding: 14px 15px;" class="gentxt">
                                    <p class="gentxt"><?php echo str_replace('@@@', $ad_content, $blog['content']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Begin FAQs section -->
            <?php
            $catCid = $blog['category'];
            
        $faqs_result = $db->query("SELECT title, detail FROM tbl_faq WHERE IStatus = '1' AND category_id = '$catCid' ORDER BY ordernumber ASC ");
        if ($faqs_result->num_rows > 0) {
        ?>
            <div class="headingtxt mrt50 mrb15">Frequently Asked Questions</div>
            <div class="secouter" style="margin-bottom:10px">
                <?php while ($faqs_row = $faqs_result->fetch_array()) { ?>
                <button class="accordiondev"><?php echo $faqs_row['title']; ?></button>
                <div class="paneldev"><?php echo $faqs_row['detail']; ?></div>
                <?php }} ?>
            </div>
            <script>
            var acc = document.getElementsByClassName("accordiondev");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function() {
                    this.classList.toggle("activedev");
                    var panel = this.nextElementSibling;
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                        panel.style.marginBottom = "0px";
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                        panel.style.marginBottom = "16px";
                    }
                });
            }
            </script>
            <!-- End FAQs section -->
    </div>
    <?php include("includes/footer.php"); ?>
    </body>
</html>
