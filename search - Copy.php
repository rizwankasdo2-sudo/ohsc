<?php include("admin/classes/connection.php");

if (isset($_POST['course_name'])) {
    $course_name = trim(mysqli_real_escape_string($db, filter_var($_POST['course_name'], FILTER_SANITIZE_STRING)));
}
if (isset($_GET['slug'])) {
    $slug = trim(mysqli_real_escape_string($db, filter_var($_GET['slug'], FILTER_SANITIZE_STRING)));	
}
else
{
	//header ("location:page-not-found");
}
$sel_id_query = "SELECT cid FROM tbl_courses";
$sel_id_result = $db->query($sel_id_query);
if(!$sel_id_result){ die(mysqli_error($db)); }

$sel_id_row = $sel_id_result->fetch_array();
$sel_id = $sel_id_row['cid'];

$seo_query = "SELECT * FROM diploma_seo WHERE d_id='$sel_id'";
$seo_result = $db->query($seo_query);
if(!$seo_result){ die(mysqli_error($db));}
$seo_row = $seo_result->fetch_array();
?>
<!DOCTYPE html>
<html>

<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Search <?php if(isset($course_name) && !empty($course_name)){ echo 'For '. strip_tags($course_name);} ?></title>
<meta property="og:title" content="<?php echo strip_tags($seo_row['page_title']); ?>" />
<meta property="description" content="<?php echo strip_tags($seo_row['meta_desc']); ?>"/>
<meta property="og:description" content="<?php echo strip_tags($seo_row['meta_desc']); ?>"/>
<meta property="og:url" content="/" />
<meta property="og:site_name" content="Oxford Home Study College" />
<meta name="robots" content="<?php echo $seo_row['robots']; ?>" />
<?php include("includes/stylesheets.php");?>
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/validationEngine.jquery.css" type="text/css" />
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
<?php include("includes/header.php");?>
<?php
		$menuqry = $db->query("SELECT * FROM tbl_courses ");
		
		if ($menuqry->num_rows <= 0)
		{
			header ("location:".$base_url."/page-not-found");
		}
		
		$meuresult = $menuqry->fetch_array();
		
		$id = $meuresult['cid'];
$searched_text = '';
if (!empty($_POST['cid'])) {
    $cid = trim(mysqli_real_escape_string($db, filter_var($_POST['cid'], FILTER_SANITIZE_STRING)));
    $category_result = $db->query("SELECT cname FROM tbl_courses WHERE IStatus = '1' AND cid = '" . $cid . "' ");
    $category_row = $category_result->fetch_array();
    $searched_text .= ' ' . $category_row['cname'];
}
if (!empty($_POST['level'])) {
    $searched_text .= ' Level ' . trim(mysqli_real_escape_string($db, filter_var($_POST['level'], FILTER_SANITIZE_STRING)));
}
?>
<div class="home_content">
<div class="row  clearfix">
<div class="col-xs-12">
</div>
    <div class="col-lg-9 col-lg-push-3">
        <div class="row clearfix">
       <div class="all_course clearfix">
       <span class="all_course_heading gentxt_fbasic">Search Result for "<?php echo $course_name . $searched_text; ?>"</span>
        <?php
        $count = 1;
		$diplomasql = "SELECT * FROM `tbl_diploma` WHERE IStatus = 1 AND cid IN (SELECT cid FROM tbl_courses WHERE IStatus = 1 AND classification_id IN (SELECT id FROM classification WHERE show_at_frontend = 1)) AND name LIKE '%$course_name%'";
		// Check if category id has been passed
		if (!empty($_POST['cid'])) {
            $cid = trim(mysqli_real_escape_string($db, filter_var($_POST['cid'], FILTER_SANITIZE_STRING)));
            //Update query
            $diplomasql .= " AND cid = '$cid' ";
        }
        // Check if course level has been passed
		if (!empty($_POST['level'])) {
            $level = trim(mysqli_real_escape_string($db, filter_var($_POST['level'], FILTER_SANITIZE_STRING)));
            //Update query
            $diplomasql .= " AND name LIKE '%level $level%' ";
        }
        $diplomaqry = $db->query($diplomasql);
        if ($diplomaqry->num_rows > 0)
        {
        $counter = 1;
        while($diplomaresult = $diplomaqry->fetch_array())
        {
            $cat_id_found = $diplomaresult['cid'];

            $cat_sql = $db->query("SELECT slug FROM tbl_courses WHERE cid='$cat_id_found'");
            if(!$cat_sql){
                die(mysqli_error($db));
            }

            $slug_row = $cat_sql->fetch_array();

            $slug_found = $slug_row['slug'];


            $did = $diplomaresult['diplomaid'];
            $mainqry = $db->query("SELECT main FROM `tbl_subject` WHERE `diplomaid`=$did");
            $mainresult = $mainqry->fetch_array();
        ?>
         
         <a class="a_t_courses gentxt" title="Learn More" href="<?php echo $base_url; ?>/courses/<?php echo $slug_found."/".$diplomaresult['slug'];?>"><?php echo $diplomaresult['name']; ?></a>
             
        <?php
        if($count==3){ 
            $count = 0;
        }
        $count++;
         }} else {
            echo "No Result For ". $course_name;
         } ?>
        </div>
        </div>
        <div class="panel panel-primary bg-panel pros_he">
        <?php
        //$prosqry = $db->query("SELECT * FROM `tbl_logo` WHERE cStatus=1 order by `clogoID` desc");
        //$preslut = $prosqry->fetch_array();
        ?>
                <!--<div class="panel-heading ">
                <a href="Resources/logo_images/<?php //echo $preslut['cBannerPath'];?>" style="text-decoration:none;">
                    <span class="banner_img hidden-xs"></span>
                 	<b class="h3_heading btn-">Download Prospectus</b>
                </a></div>-->
                <div class="panel-body">
                    <div class="row">
        <?php 
        $partnerqry = $db->query("SELECT * FROM `tbl_partner` WHERE cStatus=1 order by clogoID desc limit 0,4");
        while($row = $partnerqry->fetch_array())
            {
            ?>
           <div class="col-sm-3 col-xs-6"><img src="<?php echo $base_url; ?>/Resources/logo_images/<?php echo $row['cBannerPath'];?>" class="img-responsive" alt="OHSC partners"/></div>
           <?php } ?>
                    </div>
                </div>
            </div>
        </div>
<?php include('includes/sidebar.php'); ?>
</div>
    </div>   
  




<script src="<?php echo $base_url; ?>/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $base_url; ?>/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	     
		 jQuery(document).ready(function(){
			 // binds form submission and fields to the validation engine
		     jQuery("#form1").validationEngine();
		 });
		 
		 jQuery(document).ready(function(){
			 // binds form submission and fields to the validation engine
		     jQuery("#form2").validationEngine();
		 });
		  
    </script> 

<?php include("includes/footer.php"); ?>


</body>
</html>