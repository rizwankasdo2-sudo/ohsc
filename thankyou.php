<?php include("admin/classes/connection.php");
include_once("includes/functions.php");
?>

<!DOCTYPE html>
<html>

<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

 

<?php 

$qry=$db->query("select * from tbl_seo where seoid=16");

$row= $qry->fetch_array();

?>
    <title><?php echo $row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>"/>
    <meta property="og:url" content="/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="noindex, nofollow" />
    <?php echoCanonicalTag('/thanks'); ?>


<?php include("includes/stylesheets.php");?>
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
<!--tabs jquery stuff starts-->
<script type="text/javascript" src="js/jquery.min.js"></script>

<!--Start of Zopim Live Chat Script
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?1IXArm03Y11oe672MZFVNMUitH1urKJ4';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<script type="text/javascript" src="js/number_slideshow.js"></script>

 <!-- Google Code for Get Full Information Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 939125612;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "WjnhCPfwj2QQ7NbnvwM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" 
src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/939125612/?label=WjnhCPfwj2QQ7NbnvwM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<?php include("includes/header.php");?>
<div class="home_content">
<div class="row  clearfix">

<div class="col-lg-9 col-lg-push-3">
        <div class="panel panel-primary page_content">
          <div class="panel-heading"><h1 class="gentxt_fbasic">Thank You</h1></div>
          <div class="panel-body gentxt">
		  <?php
			$thanyou="select * from tbl_thankyou";
			$thanyouresult=$db->query($thanyou);
			$thanyoufetch= $thanyouresult->fetch_array();
		  ?>
<p class="gentxt"><?php echo $thanyoufetch['thankText']; ?></p>
</div>
</div>
</div>
     
<script src="js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	     
		 jQuery(document).ready(function(){
			 // binds form submission and fields to the validation engine
		     jQuery("#form1").validationEngine();
		 });
		
		  
    </script> 
<?php include("includes/sidebar.php");?>

    </div>   


</div>

<?php include("includes/footer.php"); ?>



 <!-- Google Code for Get Full Course Information Conversion Page -->
<script type="text/javascript">
/ <![CDATA[ /
var google_conversion_id = 977492151;
var google_conversion_language = "en";
var google_conversion_format = "1";
var google_conversion_color = "3366ff";
var google_conversion_label = "2u_fCJ_W-1kQt7GN0gM";
var google_remarketing_only = false;
/ ]]> /
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/977492151/?label=2u_fCJ_W-1kQt7GN0gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

</body>
</html>
