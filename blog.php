<?php include("admin/classes/connection.php");
include_once("includes/functions.php");
 
?>
<!DOCTYPE html>
<html>

<head lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php 

$qry=$db->query("select * from tbl_seo where seoid=10");

$row= $qry->fetch_array();

?>
    <title><?php echo $row['pagetitle']; ?></title>
    <meta property="og:title" content="<?php echo strip_tags($row['pagetitle']); ?>" />
    <meta name="description" content="<?php echo strip_tags($row['metadiscription']); ?>" />
    <meta property="og:description" content="<?php echo strip_tags($row['metadiscription']); ?>"/>
    <meta property="og:url" content="/OHSC-Blog" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo $row['metatitle']; ?>" />
    <meta name="robots" content="<?php echo $row['robots']; ?>" />
    <?php echoCanonicalTag('/OHSC-Blog'); ?>

<link href="<?php echo $base_url; ?>/css/bootstrap.min.css" rel="stylesheet" />
<link href="<?php echo $base_url; ?>/css/custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js/respond.js"></script>


<!--tabs jquery stuff starts-->
<script type="text/javascript" src="<?php echo $base_url; ?>/js/jquery.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){
$('#tabs div').hide();
$('#tabs div:first').show();
$('#tabs ul li:first').addClass('active');
$('#tabs ul li a').click(function(){
$('#tabs ul li').removeClass('active');
$(this).parent().addClass('active');
var currentTab = $(this).attr('href');
$('#tabs div').hide();
$(currentTab).show();
return false;
});
});
</script>


<!--tabs jquery stuff ends-->

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?1IXArm03Y11oe672MZFVNMUitH1urKJ4';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<script type="text/javascript" src="<?php echo $base_url; ?>/js/number_slideshow.js"></script>
</head>


<body>
		
  <script type="text/javascript">
$(document).ready(function(){

    $(".testing").hide();
});
	function change()
	{
	
	var values = $('#degree').val();
	
	var vls = 'vl='+values;
	$.ajax({ url: '<?php echo $base_url; ?>/degree.php',
				   data: vls,
				   type: 'post',
				  success: function(output) {
					  
					   if(output==1)
					   {
						    $(document).ready(function(){

    $(".testing").hide();
	$("#test").hide();
	$(".ta").hide();
	
});
						   
						   }
						   else
						   {
					  $(document).ready(function(){

    $(".testing").show();
	$(".ta").show();
	$("#test").show();
});
					 $('#test').html(output);
					
				   
						   }
				   }
		 });
	 }
</script>  
    
    
<?php include("includes/header.php");?>

 
<div class="home_content">
<div class="row  clearfix">

	<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <a itemprop="item" href="<?php echo $base_url; ?>"><span itemprop="name">Home</span></a></li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <a itemprop="item" class="active"><span itemprop="name">Blog</span></a></li>
    </ol>

<div class="col-lg-9 col-lg-push-3">

    <div class="panel panel-info page_content">

        <div class="panel-heading" style="padding:1px 15px;"><h1 class="course_header"><?php echo $row['pagetitle']; ?></h1></div>

        <div class="panel-body">

<?php
$allPostSql = "SELECT * FROM blog WHERE status = '1' ORDER BY publish_date DESC LIMIT 12";
$allPostResult = $db->query($allPostSql);
if(!$allPostResult){
    die(mysqli_error($db));
}
while($allPostRow = $allPostResult->fetch_array()){ ?>

    <div class="row clearfix" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">

        <div class="col-xs-12">

            <div class="row">

                <div style="margin-left: 15px">
                    <h2 class="post_title"><a href="<?php echo $base_url ?>/OHSC-Blog/<?php echo $allPostRow['slug'];?>"><?php echo $allPostRow['title']; ?></a></h2>
                    <p><?php echo date('F, d Y', strtotime($allPostRow['publish_date'])); ?></p>
                </div>

                <div class="col-sm-6">
                    <img src="<?php echo $base_url ?>/images/blog/<?php echo $allPostRow['image']; ?>" class="img-responsive" />
                </div>

                <div class="col-sm-6"></div>

            </div>

            <div><?php echo $allPostRow['excerpt']; ?></div>

            <div><a href="<?php echo $base_url ?>/OHSC-Blog/<?php echo $allPostRow['slug'];?>">Read More</a></div>

        </div>

    </div>

<?php } ?>


        </div>

    </div>
            
    <div class="panel panel-primary bg-panel">

        <div class="row clearfix">


            <div class="col-sm-12 pros_he">

                <div class="panel-heading">
                <a href="<?php echo $base_url; ?>/file_download1.php?f=<?php echo $preslut['cBannerPath'];?>" style="text-decoration:none;">
                        <span class="banner_img hidden-xs"></span>
                        <b class="h3_heading">Download Prospectus</b>
                    </a></div>

            </div>

        </div>

    </div>
 
</div>

    <div class="col-lg-3 col-lg-pull-9">

        <?php
            $recentPostSql = $db->query("SELECT * FROM `blog` WHERE status=1 ORDER BY publish_date DESC");
            if(!$recentPostSql){ die(mysqli_error($db)); }
        ?>

        <div class="panel panel-primary cate sidebar">
            <div class="panel-heading"><b class="sub_heading">Recent Posts</b></div>
            <div class="panel-body">
                <ul>
                    <?php
                    while($recentPostRow = $recentPostSql->fetch_array()) { ?>
                        <li><a href="<?php echo $base_url; ?>/OHSC-Blog/<?php echo $recentPostRow['slug'];?>" ><?php echo $recentPostRow['title']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </div>

    <div class="col-lg-3 col-lg-pull-9">

        <?php
        $menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus=1 ORDER BY ordernumber ASC");
        if(!$menuqry){ die(mysqli_error($db)); }
        ?>

        <div class="panel panel-primary cate sidebar">
            <div class="panel-heading"><b class="sub_heading">Categories</b></div>
            <div class="panel-body">
                <ul>
                    <?php
                    while($meuresult = $menuqry->fetch_array()) { ?>
                        <li><a href="<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug'];?>" title="<?php echo str_replace(',', '', $meuresult['cname']); ?>"><?php echo str_replace(',', '', $meuresult['cname']); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

       <!--  <div class="panel panel-primary cate sidebar">
           <div class="panel-heading"><b class="sub_heading">Facebook</b></div>
           <div class="panel-body">
               <div class="fb-page" data-href="https://www.facebook.com/oxfordhomestudy" data-tabs="timeline" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore">
               <blockquote cite="https://www.facebook.com/oxfordhomestudy">
               <a href="https://www.facebook.com/oxfordhomestudy">Oxford Home Study College</a>
               </blockquote></div></div>
           </div>
       </div>
        -->
       <!--  <div class="panel panel-primary cate sidebar">
           <div class="panel-heading"><b class="sub_heading">Twitter</b></div>
           <div class="panel-body">
               <a class="twitter-timeline" href="https://twitter.com/OHSCUK" data-widget-id="677436989101252609">Tweets by @OHSCUK</a>
               <script type="text/javascript">
                       !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
           </div>
       </div> -->

    </div>
 </div>
</div>
<?php include("includes/footer.php"); ?>

</body>
</html>
