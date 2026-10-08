<?php include("admin/classes/connection.php");

 $program = $_POST['cid'];
 $diploma = $_POST['did'];
$mathod = $_POST['mathod'];
?>

<!DOCTYPE html>
<html>

<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php 

$qry=$db->query("select * from tbl_seo where seoid=8 and pagename='Enrol'");

$row= $qry->fetch_array();

?>
<title><?php echo $row['pagetitle']; ?></title>

<meta name="keywords" content="<?php echo $row['metatitle']; ?>" />

<meta name="description" content="<?php echo $row['metadiscription']; ?>" />

<link rel="stylesheet" href="css/style.css" type="text/css" />

<!--tabs jquery stuff starts-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--tabs jquery stuff ends-->

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

<div id="courses"><h4></h4></div>

<div id="lft-container-course" style="border:none;">

<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
<script src="js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	     
		 jQuery(document).ready(function(){
			 // binds form submission and fields to the validation engine
		     jQuery("#form2").validationEngine();
		 });
		 
		 jQuery(document).ready(function(){
			 // binds form submission and fields to the validation engine
		     jQuery("#form1").validationEngine();
		 });
		 
     
    </script>
    
    <?php 
if(isset($_GET['msg']))
{
echo '<h4 style="text-align:center; color:red; padding-bottom:15px; font-family:Helvetica,Arial,  sans-serif;">Apply Succesfull we will contact you soon </h4>';	
	
}


?>
    
<form id="form2" name="form2" method="post" action="<?php echo $base_url; ?>/applytarget.php">
      <table width="100%" height="500" border="0" align="left" id="formsss">
  <tbody><tr>
    <td width="26%">Name:</td>
    <td width="74%"><select class="form-textfield2" name="mr"  style="width:71px; height:27px;  -moz-border-radius:7px; -webkit-border-radius:7px; -khtml-border-radius:7px; border-radius:7px; margin-left:0px">
    <option value="">-Select-</option>
    <option value="Mr.">Mr.</option>
    <option value="Mrs.">Mrs.</option>
    <option value="Miss.">Miss.</option>
    <option value="Ms.">Ms.</option>
    </select>
    <input type="text" name="namess" id="namess" class=" validate[required]] form-textfield2" style="margin:0; padding:0; width:235px;"></td>
  </tr>
  <tr>
    <td>Date of Birth:</td>
    <td><input name="dob" type="text" class="validate[required] form-textfield2" id="dob" style="margin:0; padding:0; width:310px;"></td>
  </tr>
  <tr>
    <td>Mobile:</td>
    <td><input type="text" name="mobile" class="form-textfield2 validate[required,custom[integer]]" style="margin:0; padding:0; width:310px;"></td>
  </tr>
  <tr>
    <td>Phone:</td>
    <td><input type="text" name="phone" class="form-textfield2 validate[required,custom[integer]]" style="margin:0; padding:0; width:310px;"></td>
  </tr>
  <tr>
    <td>Email:</td>
    <td><input type="text" name="email" id="email" class="form-textfield2 validate[required,custom[email]]" style="margin:0; padding:0;width:310px;"></td>
  </tr>
  <tr>
    <td>Address</td>
    <td><input type="text" name="address" id="address" class="form-textfield2 validate[required]" style="margin:0; padding:0;width:310px;"></td>
  </tr> 
  <tr>
    <td>City</td>
    <td><input type="text" name="city" id="city" class="form-textfield2 validate[required]" style="margin:0; padding:0;width:310px;"></td>
  </tr>
  <tr>
    <td>Country</td>
    <td><input type="text" name="country" id="country" class="form-textfield2 validate[required]" style="margin:0; padding:0;width:310px;"></td>
  </tr>
  
  <tr>
  <input type="hidden" name="dip" id="dip" value="<?php echo $program; ?>" />
  <input type="hidden" name="diplomad" id="diplomad" value="<?php echo $diploma; ?>" />
  <input type="hidden" name="mathod" id="mathod" value="<?php echo $mathod; ?>" />
    <td height="56">Education and Experience</td>
    <td rowspan="2"><textarea name="exp" id="exp" cols="40" rows="10" class="form-textfield2 validate[required]" style="margin:0; padding:0; height:140px; width:312px;"></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" align="center"><input type="submit" value="Submit" name="submit" class="sbmtbtn"></td>

  </tr>
</tbody></table>

    </form>


</div>

<?php include("includes/sidebar.php");?>

</div>

<?php include("includes/footer.php"); ?>




</body>
</html>
