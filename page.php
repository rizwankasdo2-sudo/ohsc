<?php include("admin/classes/connection.php");
include_once("includes/functions.php");
include("functions.php");
if(isset($_GET['slug']))
{
    $slug = $_GET['slug'];
} else {
    header('HTTP/1.1 404 Not Found');
    header("Refresh:0; url={$base_url}/page-not-found");
    exit();
}
$category_result = $db->query("SELECT * FROM tbl_courses WHERE previous_free_category = 1 AND slug = '$slug' AND IStatus = 1");
$page_result = $db->query("SELECT * FROM pages WHERE slug = '$slug' AND status = 1");
if ($category_result->num_rows == 0 && $page_result->num_rows == 0) {
    header('HTTP/1.1 404 Not Found');
    header("Refresh:0; url={$base_url}/page-not-found");
    exit();
}
if ($category_result->num_rows > 0) {
    $_GET['type'] = 'free';
    $prev_free_cat = 1;
    include('prev_free_category.php');
    exit;
}
$page = $page_result->fetch_array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $page['meta_title']; ?></title>
    <meta name="description" content="<?php echo strip_tags($page['meta_description']); ?>" />
    <meta name="keywords" content="<?php echo strip_tags($page['meta_keywords']); ?>" />
    <meta name="robots" content="<?php echo $page['robots']; ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <?php
    if (!empty($page['schema_markup'])) {
        echo $page['schema_markup'] . "\n";
    }
    include("includes/stylesheets.php"); ?>
    <?php include("includes/header.php");?>
<div class="home_content">
    <div class="row  clearfix">
        <ol class="breadcrumb">
            <li><a href="<?php echo $base_url; ?>" class="gentxt_basic bluetxt">Home</a></li>
            <li><span class="active gentxt_basic"><?php echo $page['name']; ?></span></li>
        </ol>
        <div class="col-lg-9 col-lg-push-3">
            <div class="panel panel-info page_content">
                <div class="panel-heading" style="padding:1px 15px;"><div class="whitehone gentxt_fbasic"><?php echo $page['title']; ?></div></div>
                <div class="panel-body">
                    <div class="row">
                        <div style="margin: 15px 15px;">
                            <div style="display:inline" class='st_twitter_hcount' title='Tweet'></div>
                            <div style="display:inline" class='st_fblike_hcount' title='Facebook Like'></div>
                            <div style="display:inline" class='st_googleplus_hcount' title='Google +'></div>
                        </div>
                        <div id="rite-cotainer" style="font-family:Helvetica,Arial,sans-serif;">
                            <div class="col-xs-12">
                                <?php
                                if (!empty($page['image'])) {
                                    list($temp_width, $temp_height) = getimagesize(dirname(__FILE__) . '/images/pages/' . $page['image']);
                                ?>
                                    <img src="<?php echo $base_url; ?>/images/pages/<?php echo $page['image']; ?>" class="img-responsive" alt="pages" width="<?php echo $temp_width; ?>" height="<?php echo $temp_height; ?>" />
                                <?php } ?>
                                <div class="gentxt linkadj">  <?php echo $page['content']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-lg-pull-9">
            <?php
            $recentPostSql = $db->query("SELECT * FROM `blog` WHERE status=1 ORDER BY publish_date DESC");
            if(!$recentPostSql){ die(mysqli_error($db)); }
            ?>
            <div class="panel panel-primary cate sidebar">
                <div class="panel-heading"><b class="sub_heading gentxt_fbasic">Recent Posts</b></div>
                <div class="panel-body">
                    <ul>
                        <?php
                        while($recentPostRow = $recentPostSql->fetch_array()) { ?>
                            <li><a href="<?php echo $base_url; ?>/OHSC-Blog/<?php echo $recentPostRow['slug'];?>" class="gentxt_fbasic"><?php echo $recentPostRow['title']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div id="Youtubediv"></div>
        </div>
    </div>
</div>
<?php
include("includes/footer.php");
if (!empty($page['videoId'])) {
?>
<script>
// Begin loading content
page_content_loaded = 'no';
if (document.documentElement.scrollTop != 0) {
    if (page_content_loaded == 'no') {
        loadPageContent();
    }
}
document.onscroll = function() {
    if (page_content_loaded == 'no') {
        loadPageContent();
    }
}
document.onmousemove = function() {
    if (page_content_loaded == 'no') {
        loadPageContent();
    }
}
function loadPageContent() {
    if (page_content_loaded == 'no') {
        page_content_loaded = 'yes';
        $("#Youtubediv").load("<?php echo $base_url ?>/ajax/general.php?task=Youtube_content&videoId=<?php echo $page['videoId']; ?>");
    }
}
// End loading content
</script>
<?php } ?>
</body>
</html>
