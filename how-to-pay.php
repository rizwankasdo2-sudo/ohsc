<?php
include("admin/classes/connection.php");
include_once("includes/functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
$qry=$db->query("select * from tbl_seo where seoid=11");
$row= $qry->fetch_array();
?>
    <title><?php echo $row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>"/>
    <meta property="og:url" content="https://www.oxfordhomestudy.com/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="<?php echo $row['robots']; ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <?php include("includes/stylesheets.php");?>
    <?php include("includes/header.php");
$aboutresult = $db->query("SELECT * FROM `tbl_howtopay` order by`payid` desc limit 0,1")->fetch_array();
?>
<div class="home_content">
<div class="row  clearfix">
<div class="col-lg-9 col-lg-push-3">
    <div class="panel panel-primary page_content">
      <div class="panel-heading"><h1 class="gentxt_fbasic">How to Pay</h1></div>
      <div class="panel-body gentxt">
       <?php echo $aboutresult['detail']; ?>
      </div>
    </div>
     <div class="panel panel-primary bg-panel pros_he">
                <div class="panel-body">
                    <div class="row">
                        <?php
                        $partnerqry = $db->query("SELECT * FROM `tbl_partner` WHERE cStatus=1 order by clogoID desc limit 0,4");
                        while ($row = $partnerqry->fetch_array()) {
                            list($temp_width, $temp_height) = getimagesize(dirname(__FILE__) . '/Resources/logo_images/' . $row['cBannerPath']);
                            ?>
                            <div class="col-sm-3 col-xs-6">
                            <img src="<?php echo $base_url; ?>/Resources/logo_images/<?php echo $row['cBannerPath'];?>" class="img-responsive" alt="partners banner" width="<?php echo $temp_width; ?>" height="<?php echo $temp_height; ?>" /></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
</div>
<?php include("includes/sidebar.php");?>
    </div>   
 </div>
<?php include("includes/footer.php"); ?>
</body>
</html>
