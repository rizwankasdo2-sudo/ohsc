<?php
include("admin/classes/connection.php");
include_once("includes/functions.php");
?>

<!DOCTYPE html>
<html>
<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
$qry=$db->query("select * from tbl_seo where seoid=4");
$row= $qry->fetch_array(); ?>

<title><?php echo $row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>"/>
    <meta property="og:url" content="/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="noindex, nofollow" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <?php include("includes/stylesheets.php");?>

<!--Start of Zopim Live Chat Script
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?1IXArm03Y11oe672MZFVNMUitH1urKJ4';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
<?php include("includes/header.php");?>

 
<div class="home_content">
<div class="row  clearfix">

<div class="col-lg-9 col-lg-push-3">
    <div class="panel panel-primary page_content">
        <div class="panel-heading"><h1 class="gentxt_fbasic">Apply Online</h1></div>
        <div class="panel-body">

<?php

if(isset($_GET['msg'])) {
echo '<h4 style="text-align:center; color:red; padding-bottom:15px;" class="gentxt_fbasic">' .$_GET['msg']. '</h4>';
} ?>

<form id="form2" name="form2" method="post"  action="<?php echo $base_url; ?>/apply_target" class="form-horizontal form_apply">
    <div class="form-group clearfix">

        <div class="col-sm-2 col-md-offset-1 m_b_f">
            <select  name="name_title" class="form-control gentxt" required >
                <option selected value="">-Select-</option>
                <option value="Mr">Mr</option>
                <option value="Miss">Miss</option>
                <option value="Ms">Ms</option>
                <option value="Mrs">Mrs</option>
                <option value="Dr">Dr</option>
                <option value="Prof">Prof</option>
                <option value="Adv">Adv</option>
            </select>
        </div>

        <div class="col-sm-3 m_b_f">
            <input type="text" name="fname" id="fname"  class="form-control gentxt" value="<?php if(isset($_POST['fname'])){echo $_POST['fname'];}?>"  placeholder="First Name*" required  />
        </div>

        <div class="col-sm-3 m_b_f">
            <input type="text" name="lname" id="lname"  class="form-control gentxt" value="<?php if(isset($_POST['lname'])){echo $_POST['lname'];}?>" placeholder="Last Name*"  required  />
        </div>

    </div>

  <div class="form-group clearfix">
    <div class="col-md-offset-1 col-sm-8">
        <input type="text" name="mobile" class="form-control gentxt" value="<?php if(isset($_POST['mobile'])){echo $_POST['mobile'];}?>" placeholder="Mobile*" required/>
    </div>
  </div>

  <div class="form-group clearfix">
    <div class="col-md-offset-1 col-sm-8">
        <input type="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>" name="email" id="email" class="form-control gentxt" required  placeholder="Email*"/>
    </div>
  </div>

    <div class="form-group clearfix">
        <div class="col-md-offset-1 col-sm-8">
            <input type="text" value="<?php if(isset($_POST['country'])){echo $_POST['country'];}?>" name="country" id="country" class="form-control gentxt" required  placeholder="Country*"/>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="col-md-offset-1 col-md-8">
            <select  class="form-control gentxt" onchange="another();" id="dip" name="dip"  required >
                <option selected disabled value="">Select Course Area</option>
                <?php 
        		$menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 order by ordernumber asc");
        		while($meuresult = $menuqry->fetch_array())
        		{
        		?>
                <option value="<?php echo $meuresult['cid'];?>"><?php echo $meuresult['cname']; ?></option>
        		<?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="col-md-8 col-md-offset-1" id="tested"></div>
    </div>
    
    <div class="form-group clearfix">
        <label class="col-md-offset-1 col-sm-2 p_m gentxt" style="font-weight: normal; margin-top: 10px;"></label>
        <div class="col-sm-6 pay_m_o gentxt"></div>
    </div>
    
    <div class="form-group clearfix">
        <div class="col-md-offset-1 col-md-8">
            <div class="g-recaptcha rgcaptcha gentxt_fbasic" data-sitekey="6Le9v6cUAAAAAE_587dYgRKkEarXKME1cGUbHnbL"></div>
        </div>
    </div>
    
    <!--<p><strong>Please Note: <span style="color: red"> Only Level 1 courses are free. </span> </strong> Fee for all other courses is mentioned on the relevant course page.</p>-->

    <div class="col-md-offset-4 col-xs-1"><input class="btn btn-primary gentxt_fbasic" type="submit" value="Submit" name="submit" style="padding: 7px 35px; background: #61487c; border: none;" id="apply_submit"/></div>

    </form>
        </div>
    </div>
</div>

<?php include("includes/sidebar.php"); ?>
    </div>
 </div>

<?php include("includes/footer.php"); ?>
<script src="https://www.google.com/recaptcha/api.js"></script>

<script type="text/javascript">
		 
     $(document).ready(function(){

    $(".tested").hide();
});

function another()
	{
	
	var values = $('#dip').val();
	
	var vls = 'vl='+values;
	$.ajax({ url: 'degreed.php',
				   data: vls,
				   type: 'post',
				  success: function(output) {
					   if(output==1)
					   {
						    $(document).ready(function(){

    $(".tested, .tested").hide();
	$("#tested").hide();
	
});
						   
 } else {

					  $(document).ready(function(){
    $(".tested label").show();
    $(".tested").show();
	$("#tested").show();
});
					 $('#tested').html(output);

	}
		}
		 });
	 }



    </script>

<script>
$(document).ready(function(){
    $(document).on('change', '#diplomad', function(){
        var diplamaId = $('#diplomad').val();
        $.ajax({
            type: "get",
            url: "freeCourseAjax.php",
            data: "diplomaId="+diplamaId,
            cache: false,
            success: function(msg){
                if(parseInt(msg)==1){
                    $('.pay_m_o').html('');
                    $('.p_m').text('');
                } else {
                    $('.pay_m_o').html('<label class="radio-inline gentxt"> <input type="radio" id="full_fee" name="payment_method" value="Full Fee" required > Full Fee </label> <label class="radio-inline gentxt"> <input type="radio" id="installments" name="payment_method" value="Installments"> Installments </label> ');
                    $('.p_m').text('Payment Method');
                }
            }
        });
    });

});
/* Begin join page captch handling */
$(document).on('submit','#form2',function(){
	var response = grecaptcha.getResponse();
	if (response.length === 0) {
    	return false;
	}
});
/* End join page captch handling */
</script>
</body>
</html>
