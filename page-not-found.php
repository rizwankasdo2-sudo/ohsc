<?php
http_response_code(410);
include("admin/classes/connection.php");
include_once("includes/functions.php");
?>

<!DOCTYPE html>
<html>

<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php 

$qry=$db->query("select * from tbl_seo where seoid=19");

$row= $qry->fetch_array(); ?>

    <title><?php echo $row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:url" content="https://www.oxfordhomestudy.com/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="<?php echo $row['robots']; ?>" />
    
 

<?php include("includes/stylesheets.php");?>

<!--tabs jquery stuff starts-->
<script type="text/javascript" src="<?php echo $base_url; ?>/js/jquery.min.js"></script>
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/validationEngine.jquery.css" type="text/css" />
<!--Start of Zopim Live Chat Script
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?1IXArm03Y11oe672MZFVNMUitH1urKJ4';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->

<?php include("includes/header.php");
    $aboutqry = $db->query("SELECT cAboutusText FROM `tbl_pagenotfound` where cAboutusID=1");
    $aboutresult = $aboutqry->fetch_array();
?>


 



<div id="wrapper">


<div id="lft-container-course">

<div id="sub-heading">
<?php echo $aboutresult['cAboutusText']; ?>
</div>

<div id="carrier" style="height:auto; overflow:hidden;">



<div id="text" style="width:630px; text-align:center; float:none; height:auto; overflow:hidden;">

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

</div>

<?php include("includes/footer.php"); ?>





</body>
</html>
