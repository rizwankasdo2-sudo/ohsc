<?php
include("admin/classes/connection.php");
$dip = trim(mysqli_real_escape_string($db, filter_var($_POST['dip'], FILTER_SANITIZE_STRING)));
if($dip==0 || (!isset($_POST['diplomad']))) {
	header("location:" . $base_url);
	exit();
}
$payment_method = isset($_POST['payment_method']) ? trim(mysqli_real_escape_string($db, filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING))) : '';

// Begin complete form handling
$payment_schedule = trim(mysqli_real_escape_string($db, filter_var($_POST['payment_schedule'], FILTER_SANITIZE_STRING)));
$diplomad = isset($_POST['diplomad']) ? trim(mysqli_real_escape_string($db, filter_var($_POST['diplomad'], FILTER_SANITIZE_STRING))) : '';
if ($payment_schedule == 'Pay Now') {
    $diplomaqry = $db->query("SELECT * FROM `tbl_diploma` WHERE diplomaid = '$diplomad' ");
    if ($diplomaqry->num_rows == 0) {
        header("location:" . $base_url);
	    exit();
    }
    $diplomaresult = $diplomaqry->fetch_array();
    $did = $diplomaresult['diplomaid'];
    $catCid = $diplomaresult['cid'];
    $course_state = $diplomaresult['course_state'];
    $programqry = $db->query("SELECT * FROM `tbl_courses` WHERE cid='$catCid'");
    if ($programqry->num_rows == 0) {
        header("location:" . $base_url);
	    exit();
    }
    $result = $programqry->fetch_array();
    $program = $result['cid'];
    $mainqry = $db->query("SELECT * FROM `tbl_subject` WHERE `diplomaid`=$did AND IStatus=1");
    $mainresult = $mainqry->fetch_array();
    if ($course_state == 0) {
        $fullpayment = $db->query("SELECT * FROM `tbl_payment` WHERE `diplomaid`=$did AND IStatus=1");
        $fullnum = $fullpayment->num_rows;
        $paymentresult = $fullpayment->fetch_array();
        $total = $paymentresult['payment'];
        $discount = $paymentresult['discount'];
        $discounted = $total-$discount;
        $full = $discounted;
    }
    $discount_result = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ");
    $discount_row = $discount_result->fetch_array();
    if ($discount_row['amount'] != 0) {
        $discount_amount = number_format(($total * $discount_row['amount']) / 100, 2, '.', '');
        $discounted_amount = ($total - $discount_amount);
        $discount_installment_percent = ($discount_row['amount'] - 10);
    }
    $install = $db->query("SELECT * FROM `tbl_installment` WHERE `diplomaid`=$did AND IStatus=1");
    $installnum = $install->num_rows;
    $installreslut = $install->fetch_array();
    $fullinstall = $installreslut['fullpayment'];
    $advance = $installreslut['advance'];
    $months = $installreslut['months'];
    $installment = ($fullinstall-$advance)/$months;
    if ($discount_row['amount'] != 0) {
        $advance = $advance - number_format(($advance * $discount_installment_percent) / 100, 2, '.', '');
        $installment = $installment - number_format(($installment * $discount_installment_percent) / 100, 2, '.', '');
        $fullinstall = ($installment * $months) + $advance;
    }

    if ($payment_method == 'Full Fee') {
        if ($discount_row['amount'] != 0) {
            $link_result = $db->query("SELECT link FROM tbl_discount_links WHERE amount = '$discounted_amount' ");
            $link_row = $link_result->fetch_array();
            $full_payment_link = $link_row['link'];
        } else {
            $full_payment_link = $mainresult['f_payment'];
        }
    } else {
        if ($discount_row['amount'] != 0) {
            $link_result = $db->query("SELECT link FROM tbl_discount_links WHERE amount = '$advance' ");
            $link_row = $link_result->fetch_array();
            $full_payment_link = $link_row['link'];
        } else {
            $full_payment_link = $mainresult['i_payment'];
        }
    }
    header('location:' . $full_payment_link);
    exit;
}
// End complete form handling
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
    <meta property="og:url" content="https://www.oxfordhomestudy.com/" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="noindex, nofollow" />

    <?php include("includes/stylesheets.php");?>

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
<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
<script src="js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
		 
     $(document).ready(function(){

    $(".tested").hide();
});

function another() {
    var values = $('#dip').val();
	var vls = 'vl='+values;
	$.ajax({
        async: false,
        url: 'degreed.php',
		data: vls,
		type: 'post',
		success: function(output) {
            if (output==1) {
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

<script type="text/javascript" src="js/number_slideshow.js"></script>
<?php include("includes/header.php");?>

 
<div class="home_content">
<div class="row  clearfix">

<div class="col-lg-9 col-lg-push-3">
    <div class="panel panel-primary page_content">
        <div class="panel-heading"><h1>Start Your Course Now</h1></div>
        <div class="panel-body">

<?php

if (isset($_GET['msg'])) {
echo '<h4 style="text-align:center; color:red; padding-bottom:15px; font-family:Helvetica,Arial,sans-serif;">' . trim(mysqli_real_escape_string($db, filter_var($_GET['msg'], FILTER_SANITIZE_STRING))) . '</h4>';
}
$name_title = trim(mysqli_real_escape_string($db, filter_var($_POST['name_title'], FILTER_SANITIZE_STRING)));
?>

<form id="form2" name="form2" method="post"  action="" class="form-horizontal form_apply">
    <div class="form-group clearfix">

        <div class="col-sm-2 col-md-offset-1 m_b_f">
            <select  name="name_title" class="form-control" required >
                <option value="">-Select-</option>
                <option value="Mr" <?php echo ($name_title == 'Mr') ? 'selected' : ''; ?>>Mr</option>
                <option value="Miss" <?php echo ($name_title == 'Miss') ? 'selected' : ''; ?>>Miss</option>
                <option value="Ms" <?php echo ($name_title == 'Ms') ? 'selected' : ''; ?>>Ms</option>
                <option value="Mrs" <?php echo ($name_title == 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                <option value="Dr" <?php echo ($name_title == 'Dr') ? 'selected' : ''; ?>>Dr</option>
                <option value="Prof" <?php echo ($name_title == 'Prof') ? 'selected' : ''; ?>>Prof</option>
                <option value="Adv" <?php echo ($name_title == 'Adv') ? 'selected' : ''; ?>>Adv</option>
            </select>
        </div>

        <div class="col-sm-3 m_b_f">
            <input type="text" name="fname" id="fname"  class="form-control" value="<?php if(isset($_POST['fname'])){ echo trim(mysqli_real_escape_string($db, filter_var($_POST['fname'], FILTER_SANITIZE_STRING))); } ?>"  placeholder="First Name*" required  />
        </div>

        <div class="col-sm-3 m_b_f">
            <input type="text" name="lname" id="lname"  class="form-control" value="<?php if(isset($_POST['lname'])){ echo trim(mysqli_real_escape_string($db, filter_var($_POST['lname'], FILTER_SANITIZE_STRING))); } ?>" placeholder="Last Name*"  required  />
        </div>

    </div>

  <div class="form-group clearfix">
    <div class="col-md-offset-1 col-sm-8">
        <input type="text" name="mobile" class="form-control" value="<?php if (isset($_POST['mobile'])) { echo trim(mysqli_real_escape_string($db, filter_var($_POST['mobile'], FILTER_SANITIZE_STRING))); } ?>" placeholder="Mobile*" required/>
    </div>
  </div>

  <div class="form-group clearfix">
    <div class="col-md-offset-1 col-sm-8">
        <input type="email" value="<?php if (isset($_POST['email'])) { echo trim(mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL))); } ?>" name="email" id="email" class="form-control" required  placeholder="Email*"/>
    </div>
  </div>

    <div class="form-group clearfix">
        <div class="col-md-offset-1 col-sm-8">
            <input type="text" value="<?php if(isset($_POST['country'])){echo trim(mysqli_real_escape_string($db, filter_var($_POST['country'], FILTER_SANITIZE_STRING))); }  ?>" name="country" id="country" class="form-control" required  placeholder="Country*"/>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="col-md-offset-1 col-md-8">
            <select  class="form-control" onchange="another();" id="dip" name="dip"  required >
                <option disabled value="">Select Course Area</option>
                <?php 
        		$menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 order by ordernumber asc");
        		while ($meuresult = $menuqry->fetch_array()) {
        		?>
                <option value="<?php echo $meuresult['cid'];?>" <?php echo ($dip == $meuresult['cid']) ? 'selected' : ''; ?>><?php echo $meuresult['cname']; ?></option>
        		<?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="col-md-8 col-md-offset-1" id="tested"></div>
    </div>
    
    <div class="form-group clearfix">
        <label class="col-md-offset-1 col-sm-2 p_m" style="font-weight: normal; margin-top: 10px;">Payment Method</label>
        <div class="col-sm-6 pay_m_o">
            <label class="radio-inline"> <input type="radio" id="full_fee" name="payment_method" value="Full Fee" required="" <?php echo ($payment_method == 'Full Fee' || !isset($_POST['payment_method'])) ? 'checked' : ''; ?>> Full Fee </label> <label class="radio-inline"> <input type="radio" id="installments" name="payment_method" value="Installments"> Installments </label>
        </div>
    </div>
    <div class="form-group clearfix">
        <label class="col-md-offset-1 col-sm-2 p_m" style="font-weight: normal; margin-top: 10px;">Payment Mode</label>
        <div class="col-sm-6 pay_m_o">
            <div>
                <label class="radio-inline"> <input type="radio" name="payment_schedule" value="Pay Now" required <?php echo ($payment_schedule == 'Pay Now' || !isset($_POST['payment_schedule'])) ? 'checked' : ''; ?>> I want to pay now </label>
            </div>
            <div>
                <label class="radio-inline"> <input type="radio" name="payment_schedule" id="wants_invoice" value="Invoice First" <?php echo ($payment_schedule == 'Invoice First') ? 'checked' : ''; ?>> I want an invoice </label>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <div class="col-md-offset-1 col-md-8">
            <div class="g-recaptcha rgcaptcha" data-sitekey="6Le9v6cUAAAAAE_587dYgRKkEarXKME1cGUbHnbL"></div>
        </div>
    </div>
    
    <!--<p><strong>Please Note: <span style="color: red"> Only Level 1 courses are free. </span> </strong> Fee for all other courses is mentioned on the relevant course page.</p>-->

    <div class="col-md-offset-4 col-xs-1"><input class="btn btn-primary" type="submit" value="Submit" name="submit" style="padding: 7px 35px; background: #61487c; border: none;" id="apply_submit"/></div>

    </form>
        </div>
    </div>
</div>

<?php include("includes/sidebar.php"); ?>
    </div>
 </div>

<?php include("includes/footer.php"); ?>

<script>
$(document).ready(function(){
    // Begin diploma population
    another();
    $('#diplomad').val("<?php echo $diplomad; ?>");
    // Begin diploma population
});
/* Begin join page captch handling */
$(document).on('submit','#form2',function(){
	var response = grecaptcha.getResponse();
	if (response.length === 0) {
    	return false;
	}
	if (document.getElementById('wants_invoice').checked) {
	    $('#form2').attr('action', '<?php echo $base_url; ?>/apply_target');
	}
});
/* End join page captch handling */
</script>
</body>
</html>
