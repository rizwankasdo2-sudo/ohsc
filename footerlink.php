<?php include("admin/classes/connection.php");
include_once("includes/functions.php");

if (!isset($_GET['slug']) || trim($_GET['slug']) === '') {
    include('page-not-found-404.php');
    exit;
}

$slug = $_GET['slug'];
$linkheading = $db->query("SELECT * FROM tbl_footer WHERE slug='$slug'");
if ($linkheading->num_rows == 0) {
    include('page-not-found-404.php');
    exit;
}
$linkresult = $linkheading->fetch_array();
?>

<!DOCTYPE html>
<html>

<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Oxford Learning Home</title>
<?php echoCanonicalTag('/page/' . preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $slug)); ?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/css/style.css" type="text/css" />

<!--tabs jquery stuff starts-->
<script type="text/javascript" src="<?php echo $base_url; ?>/js/jquery.min.js"></script>

<!--Start of Zopim Live Chat Script
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?1IXArm03Y11oe672MZFVNMUitH1urKJ4';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<script type="text/javascript" src="<?php echo $base_url; ?>/js/number_slideshow.js"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/validationEngine.jquery.css" type="text/css" />
<?php include("includes/header.php");?>


<div id="wrapper">


<div id="lft-container-course">

<div id="sub-heading">
<h3><?php echo $linkresult['footertitle'];?></h3>
</div>

<div id="carrier" style="height:auto; overflow:hidden;">



<div id="text" style="width:630px; text-align:justify; float:none; height:auto; overflow:hidden;">
<p><?php echo $linkresult['footerdetail'];?></p>
</div>
</div>
</div>

<script src="<?php echo $base_url; ?>/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $base_url; ?>/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	     
		 jQuery(document).ready(function(){
			 // binds form submission and fields to the validation engine
		     jQuery("#form1").validationEngine();
		 });
		 
		
		  function checkHELLO(field, rules, i, options){
			  if (field.val() != "HELLO") {
				  // this allows to use i18 for the error msgs
			      return options.allrules.validate2fields.alertText;
			  }
		  }
     
    </script>
<?php include("includes/sidebar.php");?>

</div>


<?php include("includes/footer.php"); ?>


</body>
</html>
