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
$qry=$db->query("select * from tbl_seo where seoid=6 and pagename='Contact Us'");
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
    <?php include("includes/header.php");?>
<div class="home_content">
<div class="row  clearfix">
<div class="col-lg-9 col-lg-push-3">
        <div class="panel panel-primary page_content">
          <div class="panel-heading gentxt_fbasic"><h1 class="gentxt_fbasic">Feedback Form</h1></div>
          <div class="panel-body gentxt">
            <b class="gentxt">Address</b>
                    <?php 
                        $contactqry = $db->query("SELECT * FROM `tbl_contactus` order by `IContactID` desc limit 0,1");
                        $contactresult = $contactqry->fetch_array();
                        echo $contactresult['IContactDetail'];
                        if(isset($_GET['msg']))
                        {
                        echo '<h4 style="text-align:center">Thank you for your feedback</h4>';	
                        }
                        ?>
                <form class="form-horizontal" method="post" action="<?php echo $base_url; ?>/contect_target.php" id="form2">
                  <div class="form-group">
                    <label for="name" class="col-sm-3 control-label gentxt_fbasic">Name: <span>*</span></label>
                    <div class="col-sm-9">
                      <input type="text" name="name" class="form-control" id="name" placeholder="Full Name" required/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="title" class="col-sm-3 control-label gentxt_fbasic">Title: <span>*</span></label>
                    <div class="col-sm-9">
                      <input type="text" name="title" class="form-control" id="title" placeholder="Title" required/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="country" class="col-sm-3 control-label gentxt_fbasic">Country: <span>*</span></label>
                    <div class="col-sm-9">
                      <input type="text" name="country" class="form-control" id="country" placeholder="Country" required/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="state" class="col-sm-3 control-label gentxt_fbasic">State/Province:</label>
                    <div class="col-sm-9">
                      <input type="text" name="province" class="form-control" id="state" placeholder="State/Province:"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label gentxt_fbasic">Email: <span>*</span></label>
                    <div class="col-sm-9">
                      <input type="email" name="email" class="form-control" id="email" placeholder="Email"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="phone" class="col-sm-3 control-label gentxt_fbasic">Phone: </label>
                    <div class="col-sm-9">
                      <input type="text" name="phone" class="form-control" id="phone" placeholder="phone"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="best_time_to_call" class="col-sm-3 control-label gentxt_fbasic">Best Time to Call: <span>*</span></label>
                    <div class="col-sm-9">
                      <input type="text" name="call" class="form-control" id="best_time_to_call" placeholder="" required/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="message" class="col-sm-3 control-label gentxt_fbasic">Message: <span>*</span></label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="message" id="message" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label">&nbsp; </label>
                      <div class="col-sm-9">
                          <div class="g-recaptcha rgcaptchacon" data-sitekey="6Le9v6cUAAAAAE_587dYgRKkEarXKME1cGUbHnbL"></div>
                      </div>
                  </div>
                  <div class="col-xs-9 col-xs-offset-3"><input type="submit" value="Submit" class="btn btn-primary bgdarkblue" required/></div>
                </form>
          </div>
        </div>
    </div>
<?php include('includes/sidebar.php'); ?>
    </div>   
 </div>
<?php include("includes/footer.php"); ?>
<div id="loadConJS"></div>
<script>
/* Begin join page captch handling */
$(document).on('submit','#form2',function(){
    var response = grecaptcha.getResponse();
    if (response.length === 0) {
        return false;
    }
});
/* End join page captch handling */
// Begin loading content
contactus_content_loaded = 'no';
if (document.documentElement.scrollTop != 0) {
    if (contactus_content_loaded == 'no') {
        loadContactusContent();
    }
}
document.onscroll = function() {
    if (contactus_content_loaded == 'no') {
        loadContactusContent();
    }
}
document.onmousemove = function() {
    if (contactus_content_loaded == 'no') {
        loadContactusContent();
    }
}
function loadContactusContent() {
    if (contactus_content_loaded == 'no') {
        contactus_content_loaded = 'yes';
        $("#loadConJS").load("<?php echo $base_url ?>/ajax/general.php?task=contactus_content");
    }
}
// End loading content
</script>
</body>
</html>
