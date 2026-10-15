<?php
include("admin/classes/connection.php");
include_once("includes/functions.php");
$qry=$db->query("select * from tbl_seo where seoid=4");
$row= $qry->fetch_array(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome to Oxford Home Study College</title>
    <link href="<?php echo $base_url ?>/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $base_url ?>/css/voucher_css.css" rel="stylesheet" />
    <link href="css/fonts.googleapis.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <style>
    .gentxt, .gentxt_fbasic{
        font-family:'Poppins', sans-serif !important;
    }
    </style>
</head>

<body>
<div class="header-clr">
    <div class="container">
        <div class="row">

            <div class="col-md-5"></div>
            <div class="col-md-4">
                <img class="img-responsive" src="images/ohsc-logo-white.png" alt="OHSC" />
            </div>
            <div class="col-md-3"></div>

        </div>
    </div>
</div>
<br />
<br />

<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
     <div class="col-md-7">
      <div class="row">
       <div class="col-md-12">
          <h1 class="edu-heading gentxt_fbasic">Welcome to Oxford Home Study College</h1>
          <p class="awd-para gentxt_fbasic">Redeem your voucher here.</p> <br />
       </div>
      </div>  
         <br />      
     <div class="row">
       <div class="col-md-1"></div>
     
<div class="col-md-10">
        
  <form role="form" action="voucher-redemption-target.php" method="post" id="formvred">

      <div class="form-group">
        <input type="text" name="fname" class="form-control gentxt_fbasic" id="fname" placeholder="First Name" required>
      </div>

      <div class="form-group">      
        <input type="text" name="lname" class="form-control gentxt_fbasic" id="lname" placeholder="Last Name" required>
      </div>

      <div class="form-group">      
        <input type="email" name="email" class="form-control gentxt_fbasic" id="email" placeholder="Email" required>
      </div>

      <div class="form-group">
        <input type="text" name="mobile" class="form-control gentxt_fbasic" id="mobile" placeholder="Phone" required>
      </div>

      <div class="form-group">
          <input type="text" name="country" class="form-control gentxt_fbasic" id="country" placeholder="Country" required>
      </div>

      <div class="form-group">
          <select  class="form-control gentxt_fbasic" onchange="another();" id="dip" name="dip"  required >
              <option selected disabled value="">Select Course Area</option>
              <?php
              $menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 order by ordernumber asc");
              while($meuresult = $menuqry->fetch_array()) { ?>
                  <option value="<?php echo $meuresult['cid'];?>"><?php echo $meuresult['cname']; ?></option>
              <?php } ?>
          </select>
      </div>

      <div class="form-group clearfix" id="tested"></div>

      <div class="form-group">
          <input type="text" name="voucher_code" class="form-control gentxt_fbasic" id="voucher_code" placeholder="Coupon / Voucher Code" required>
      </div>
      
      <div class="form-group">
          <div class="g-recaptcha rgcaptcha gentxt_fbasic" data-sitekey="6Le9v6cUAAAAAE_587dYgRKkEarXKME1cGUbHnbL"></div>
      </div>

</div>

   </div>
   
   <div class="col-md-1"></div>
         <br /><br />
    <div class="row">
    
     <div class="col-md-1"></div>
       <div class="col-md-10">
         <div class="p-click gentxt_fbasic">By clicking Submit, you agree to our <a class="link-c gentxt_fbasic" href="/terms-and-conditions">Terms and Conditions</a> and that you have read our <a class="link-c gentxt_fbasic" href="/privacy-policy">Privacy Policy</a>.</div>  <br />
      
      <input type="submit" class="btn-link gentxt_fbasic" value="Submit">
     </div>
     
     <div class="col-md-1"></div>
    </div>
    </from>
     
     
 </div>
 </div>


    </div>

  <div class="col-md-2"></div>

  </div>
</div>

<script  type="text/javascript" src="<?php echo $base_url ?>/js/jquery.min.js"></script>
<script  type="text/javascript" src="<?php echo $base_url ?>/js/bootstrap.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
        $(".tested").hide();
    });

    function another() {
        var values = $('#dip').val();
        var vls = 'vl='+values;

        $.ajax({
            url: 'degreed.php',
            data: vls,
            type: 'post',
            success: function(output) {
                if(output==1) {
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
/* Begin join page captch handling */
$(document).on('submit','#formvred',function(){
	var response = grecaptcha.getResponse();
	if (response.length === 0) {
    	return false;
	}
});
/* End join page captch handling */

</script>
</body>
</html>
