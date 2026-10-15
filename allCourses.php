<?php include("admin/classes/connection.php");

if(isset($_GET['slug']))

{

$slug = $_GET['slug'];	

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



<?php 



$qry=$db->query("select * from tbl_seo where seoid=5");



$row= $qry->fetch_array();



?>

    <title><?php echo $row['pagetitle']; ?></title>

    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />

    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />

    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>"/>

    <meta property="og:url" content="/" />

    <meta property="og:site_name" content="Oxford Home Study College" />

    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />

    <meta name="robots" content="<?php echo $row['robots']; ?>" />

    



 

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

		$menuqry = $db->query("SELECT * FROM tbl_courses");

		

		if ($menuqry->num_rows <= 0)

		{

			header ("location:".$base_url."/page-not-found");

		}

		

		$meuresult = $menuqry->fetch_array();

		

		$id = $meuresult['cid']; ?>

<div class="home_content">

<div class="row  clearfix">

<div class="col-xs-12">



</div>

    <div class="col-lg-9 col-lg-push-3">

        <div class="row clearfix">

       <div class="all_course clearfix">

       <span class="all_course_heading gentxt_fbasic"><h1>A-Z Courses</h1></span>

<div class="row acc">





<div class="col-sm-6 searching">

    <form action="<?php echo $base_url ?>/search.php" method="post" class="inner_search">

      <div class="form-group">

        <input type="search" class="form-control gentxt" id="searchingcourse" placeholder="Search courses" name="course_name" value="<?php if(isset($course_name) && !empty($course_name)){ echo $course_name; } ?>"/>

        <button type="submit" class="btn btn-default gentxt_fbasic" value="Search" name="searching"><span class="mobile-hide">Search </span><i class="fa fa-search"></i></button>

      </div>

    </form>

</div>



        <?php

$tab_count = 1;

$menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 order by ordernumber asc");

while($meuresult = $menuqry->fetch_array()){ ?>

<div class="col-sm-6">

<div class="panel-group" id="accordion1_<?php echo $meuresult['cid']; ?>" role="tablist" aria-multiselectable="true">

  <div class="panel panel-default">

    <div class="panel-heading" role="tab" id="heading<?php echo $tab_count; ?>">

      <h4 class="panel-title">

      

        <a data-toggle="collapse23" data-parent="#accordion" href="#collapse<?php echo $tab_count; ?>" aria-expanded="true" aria-controls="collapse<?php echo $tab_count; ?>" class="gentxt">

          <?php echo $meuresult['cname']; ?>

        </a>

        

        <span class="ar_st"></span>

      </h4>

    </div>

    <div id="collapse<?php echo $tab_count; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $tab_count; ?>">

      <div class="panel-body">

      <?php



    $id=$meuresult['cid'];

        $count = 1;

		$diplomaqry = $db->query("SELECT * FROM `tbl_diploma` WHERE IStatus=1 AND `cid`=$id");

        if ($diplomaqry->num_rows > 0)

        {

        $counter = 1;

        while($diplomaresult = $diplomaqry->fetch_array())

        {

            $did = $diplomaresult['diplomaid'];

            $mainqry = $db->query("SELECT main FROM `tbl_subject` WHERE `diplomaid`=$did");

            $mainresult = $mainqry->fetch_array();

            

        ?>

                 <a href="<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug']; ?>/<?php echo $diplomaresult['slug'];?>" class="a_t_courses gentxt" title="Learn More"><?php echo str_replace(array(',','.'), array('',''), $diplomaresult['name']); ?></a>

             

        <?php

        if($count==3){ 

            $count = 0;

        }

        $count++;

         }} ?>

      

      

     </div>

    </div>

  </div>

</div>

</div>





        <?php

        $tab_count++;

         }

         ?>

        </div>

 </div>

        </div>

        <div class="panel panel-primary bg-panel pros_he">

        <?php

                //$prosqry = $db->query("SELECT * FROM `tbl_logo` WHERE cStatus=1 order by `clogoID` desc");

                //$preslut = $prosqry->fetch_array();

                ?>

                <!--<div class="panel-heading"><a href="<?php //echo $base_url ?>/Resources/logo_images/<?php //echo $preslut['cBannerPath'];?>" style="text-decoration:none;">

                        <span class="banner_img hidden-xs"></span>

                        <b class="h3_heading">Download Prospectus</b>

                    </a></div>-->     

                

                

                <div class="panel-body">

                    <div class="row">

        <?php 

        $partnerqry = $db->query("SELECT * FROM `tbl_partner` WHERE cStatus=1 order by clogoID desc limit 0,4");

        while($row = $partnerqry->fetch_array())

            {

            ?>

           <div class="col-sm-3 col-xs-6"><img src="<?php echo $base_url; ?>/Resources/logo_images/<?php echo $row['cBannerPath'];?>" alt="Oxford Home Study Logo" class="img-responsive"/></div>

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