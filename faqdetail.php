<?php include("admin/classes/connection.php");
if(isset($_GET['id']))
{
$id = $_GET['id'];	
	
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<title>Oxford Learning Home</title>

<link rel="stylesheet" href="css/style.css" type="text/css" />

<!--tabs jquery stuff starts-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

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




</div>



<div id="wrapper">


<div id="lft-container-course">

<div id="sub-heading">
<h3>Frequently Asked Questions</h3>
</div>

<div id="carrier" style="height:auto; overflow:hidden;">



<div id="text" style="width:630px; text-align:justify; float:none; height:auto; overflow:hidden;">

<?php 
$aboutqry = $db->query("SELECT * FROM `tbl_faq` WHERE  faqid=$id");
$aboutresult = $aboutqry->fetch_array();

?>
<?php echo $aboutresult['detail'];?>
</div>
</div>
</div>

<?php include("includes/sidebar.php");?>

</div>

<?php include("includes/footer.php"); ?>





</body>
</html>
