<?php
include("admin/classes/connection.php");
if(isset($_GET['slug']))
{
    $slug = $_GET['slug'];
}
else
{
    header ("location:page-not-found");
}
$sel_id_query = "SELECT cid FROM tbl_courses WHERE slug='$slug' AND IStatus = '1' ";
$sel_id_result = $db->query($sel_id_query);

if($sel_id_result->num_rows == 0){
    header('HTTP/1.1 404 Not Found');
    include('page-not-found.php');
    exit();
}

if(!$sel_id_result){ die(mysqli_error($db)); }

$sel_id_row = $sel_id_result->fetch_array();
$sel_id = $sel_id_row['cid'];

$seo_query = "SELECT * FROM diploma_seo WHERE d_id='$sel_id' ";
$seo_result = $db->query($seo_query);
if(!$seo_result){ die(mysqli_error($db));}
$seo_row = $seo_result->fetch_array();

$menuqry = $db->query("SELECT * FROM tbl_courses WHERE slug='$slug';");

if ($menuqry->num_rows <= 0)
{
    header ("location:".$base_url."/page-not-found");
}

$meuresult = $menuqry->fetch_array();
if (trim($meuresult['cvideo']) != '') {
    $_SESSION['cvideo'] = $meuresult['cvideo'];
}

$id = $meuresult['cid'];
?>
<!doctype html>
<html>

<head lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


    <title><?php echo strip_tags($seo_row['page_title']); ?></title>
    <meta name="description" content="<?php echo strip_tags($seo_row['meta_desc']); ?>" />
    <meta name="keywords" content="<?php echo strip_tags($seo_row['meta_key']); ?>" />
    <meta name="robots" content="<?php echo $seo_row['robots']; ?>" />
    <?php include("includes/stylesheets.php");?>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/validationEngine.jquery.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>/js/respond.js"></script>
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
    <!-- Begin courses markup -->
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Course",
      "url": "<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug']; ?>",
      "name": "<?php echo $meuresult['cname'] ?>",
      "description": "<?php echo str_replace('"', '\'', strip_tags($meuresult['excerpt'])); ?>",
      "image": "<?php echo $base_url; ?>/images/<?php echo $meuresult['thumbnail']; ?>",
      "provider": {
        "@type": "Organization",
        "name": "Oxford Home Study Centre",
        "sameAs": "/"
      }
    }
    </script>
    <!-- End courses markup -->
    <script type="text/javascript" src="<?php echo $base_url; ?>/js/number_slideshow.js"></script>
<?php include("includes/header.php");?>
<?php
/*$menuqry = $db->query("SELECT * FROM tbl_courses WHERE slug='$slug';");

if ($menuqry->num_rows <= 0)
{
    header ("location:".$base_url."/page-not-found");
}

$meuresult = $menuqry->fetch_array();
if (trim($meuresult['cvideo']) != '') {
    $_SESSION['cvideo'] = $meuresult['cvideo'];
}

$id = $meuresult['cid'];*/ ?>
<div class="home_content">
    <div class="row  clearfix">
        <div class="col-xs-12">
            <ol class="breadcrumb" vocab="https://schema.org/" typeof="BreadcrumbList">
                <li property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage"  href="<?php echo $base_url; ?>"><span property="name" class="gentxt_basic">Home</span></a>
                    <meta property="position" content="1">
                </li>
                <li property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage" class="active"><span property="name" class="gentxt_basic"><?php echo $meuresult['cname'] ?></span></a>
                    <meta property="position" content="2">
                </li>
            </ol>
        </div>
        <div class="col-lg-9 col-lg-push-3">

            <div class="jumbotron">
                <?php
                if(!empty($meuresult['content'])){

                    $body = strlen($meuresult['content']);
                    if($body > 613){
                        echo '<div class="less_text gentxt">'.$meuresult['excerpt'].'<a href="#" class="text_expand_link gentxt_fbasic">Read More +</a></div>';
                        echo '<div class="all_text gentxt" style="display:none;"> '.$meuresult['content']. ' <a class="aboutus_link gentxt_fbasic">Read Less -</a></div>';
                    } else {
                        echo '<div class="less_text gentxt">'.$meuresult['content'].'</div>';
                    }

                } ?>
                <script>

                    $(".text_expand_link").click(function(){$(this).hide();$(".less_text, .less_text p").css('display','inline-block');$(".all_text").slideDown('slow');$(".all_text").css('display','inline-block');return false;});
                    $(".aboutus_link").click(function(){$(".all_text").slideUp('fast');$(".text_expand_link").show();});

                </script>
            </div>

            <div class="row clearfix">

                <?php
                $count = 1;
                $diplomaqry = $db->query("SELECT * FROM `tbl_diploma` WHERE IStatus=1 AND `cid`=$id AND show_in_category=1");
                if ($diplomaqry->num_rows > 0)
                {
                    $counter = 1;
                    while($diplomaresult = $diplomaqry->fetch_array())
                    {
                        $did = $diplomaresult['diplomaid'];
                        $mainqry = $db->query("SELECT main, courseoverview FROM `tbl_subject` WHERE `diplomaid`=$did");
                        $mainresult = $mainqry->fetch_array();
                        ?>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading course_hed txtcen"><h2 class="course_hed gentxt_fbasic"><?php echo str_replace(array(',','.'), array('',''), $diplomaresult['name']); ?></h2></div>
                                <div class="panel-body">
                                    <div class="excerpt_content">
                                        <img src="<?php echo $base_url; ?>/images/subject/<?php if(!empty($diplomaresult['thumbnail'])){ echo $diplomaresult['thumbnail']; } else { echo 'account.jpg' ;} ?>" alt="<?php if(!empty($diplomaresult['alternate'])){ echo $diplomaresult['alternate']; } ?>"  title="<?php if(!empty($diplomaresult['alternate'])){ echo $diplomaresult['alternate']; } ?>" width="262" height="82"/>
                                        <?php if ($discount_row['amount'] != 0) { ?>
                                        <div class="main_discount gentxt_fbasic"><?php echo $discount_row['amount']; ?>%<br />off</div>
                                        <?php } ?>
                                        <?php $body = strip_tags($mainresult['main']);
                                        $courseOverview = strip_tags($mainresult['courseoverview']);
                                        ?>
                                        <div class="gentxt"><?php if(!empty($body)){

                                                echo  substr($body, 0, strpos($courseOverview, ' ', 125));

                                            } else {

                                                echo  substr($courseOverview, 0, strpos($courseOverview, ' ', 125));

                                            } ?>...</div>
                                    </div>
                                    <a href="<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug']; ?>/<?php echo $diplomaresult['slug'];?>" class="find_link gentxt_fbasic" title="Learn More">Find out more<span class="glyphicon glyphicon-chevron-right"></span></a>

                                </div>
                            </div>
                        </div>

                        <?php
                        if($count==3){
                            echo '</div>
            <div class="row clearfix">';
                            $count = 0;
                        }
                        $count++;
                    }
                } ?>

            </div>

            <?php
            /*$blog_sql = "SELECT * FROM blog WHERE category='$id' ORDER BY publish_date DESC";
            $blog_result = $db->query($blog_sql);
            if(!$blog_result){
                die(mysqli_error($db));
            }

            if($blog_result->num_rows > 0){*/ ?>
                <!--<div class="blog_post_title">Latest Blogs</div>
                <div class="row">
                    <?php
                    //while($blog_row = $blog_result->fetch_array()){ ?> <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">
                                <img src="<?php //echo $base_url?>/images/blog/<?php //echo $blog_row['image']; ?>" alt="<?php //echo $blog_row['alt']; ?>" title="<?php //echo $blog_row['alt']; ?>">
                                <div class="caption">
                                    <h3 class="post_title"><?php //echo $blog_row['title']; ?></h3>
                                    <p style="font-style: italic;">Posted on <?php //echo date('d-m-Y', strtotime($blog_row['publish_date'])); ?></p>
                                    <div><?php //echo $blog_row['excerpt']; ?></div>
                                    <div><a href="<?php //echo $base_url?>/OHSC-Blog/<?php //echo $blog_row['slug']; ?>">Read More</a></div>
                                </div>
                            </div>
                        </div>

                    <?php //} ?>
                </div>-->
            <?php //} ?>
            
            <!-- Begin related blog posts -->
            <?php
            $related_blog_result = $db->query("SELECT title, slug FROM blog WHERE status = '1' AND category = '$id' ");
            if ($related_blog_result->num_rows > 0) {
            ?>
            <div class="blog_post_title mrt50 gentxt_fbasic">Recent Blog Posts</div>
            <div>
                <ul class="rb_list">
                    <?php while ($related_blog_row = $related_blog_result->fetch_array()) { ?>
                    <li>
                        <a href="<?php echo $base_url ?>/OHSC-Blog/<?php echo $related_blog_row['slug']; ?>" class="gentxt_fbasic"><?php echo $related_blog_row['title']; ?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
            <!-- End related blog posts -->
            
            <!-- Begin FAQs section -->
            <?php
            $faqs_result = $db->query("SELECT title, detail FROM tbl_faq WHERE IStatus = '1' AND category_id = '$id' ORDER BY ordernumber ASC ");
            if ($faqs_result->num_rows > 0) {
            ?>
            <div class="blog_post_title mrt50 gentxt_fbasic">Frequently Asked Questions</div>
            <div class="plusAccordion">
                <?php while ($faqs_row = $faqs_result->fetch_array()) { ?>
                <button class="collapsible nobdr">
                    <span style="max-width:90%;" class="gentxt_fbasic">
                        <strong><?php echo $faqs_row['title']; ?></strong>
                    </span>
                </button>
                <div class="content">
                    <div class="menubody gentxt">
                        <p class="gentxt"><?php echo $faqs_row['detail']; ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            <!-- End FAQs section -->



            <div class="panel panel-primary bg-panel pros_he">
                <?php
                //$prosqry = $db->query("SELECT * FROM `tbl_logo` WHERE cStatus=1 order by `clogoID` desc");
                //$preslut = $prosqry->fetch_array();
                ?>
                <!--<div class="panel-heading"><a href="<?php //echo $base_url ?>/Resources/logo_images/<?php //echo $preslut['cBannerPath'];?>" style="text-decoration:none;">
                        <span class="banner_img hidden-xs"></span>
                        <b class="h3_heading gentxt_fbasic">Download Prospectus</b>
                    </a></div>-->


                <div class="panel-body">
                    <div class="row">
                        <?php
                        $partnerqry = $db->query("SELECT * FROM `tbl_partner` WHERE cStatus=1 order by clogoID desc limit 0,4");
                        while($row = $partnerqry->fetch_array())
                        {
                            ?>
                            <div class="col-sm-3 col-xs-6">
                                <img src="<?php echo $base_url; ?>/Resources/logo_images/<?php echo $row['cBannerPath'];?>" class="img-responsive" alt="partners banner" /></div>
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

<script  async="async" type="text/javascript" src="<?php echo $base_url ?>/js/custom.js"></script>
</body>
</html>