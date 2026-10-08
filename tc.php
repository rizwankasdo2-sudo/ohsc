<?php include("admin/classes/connection.php");?>

<!DOCTYPE html>
<html>

<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php 

$qry=$db->query("select * from tbl_seo where seoid=3 and pagename='Terms and conditions'");

$row= $qry->fetch_array();

?>
<title><?php echo $row['pagetitle']; ?></title>

<meta name="keywords" content="<?php echo $row['metatitle']; ?>" />

<meta name="description" content="<?php echo $row['metadiscription']; ?>" />

<link rel="stylesheet" href="css/style.css" type="text/css" />
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
<?php include("includes/header.php");?>






<div id="wrapper">


<div id="lft-container-course">

<div id="sub-heading">
<h3>Terms and conditions</h3>
</div>

<div id="carrier" style="height:auto; overflow:hidden;">
<?php 
$tc_s=$db->query("select * from tandc where IStatus=1");
$tc_f= $tc_s->fetch_array();
extract($tc_f);
?>
 <?php echo $tctxt; ?>

<div id="text" style="width:630px; text-align:justify; float:none; height:auto; overflow:hidden;">
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
