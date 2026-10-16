<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
    header("location:index.php");
    exit();
}
$newsid=$_GET['id'];
if(isset($_GET['cid']))
{
    $cid = $_GET['cid'];
}
include("classes/connection.php");
$qry=$db->query("select * from  tbl_diploma where diplomaid=$newsid");
$newsreslt = $qry->fetch_array();
$certificate_ids = $newsreslt['certificate_ids'];
$msg = array();
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edit Diploma</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css?v=2" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <link rel="stylesheet" href="../Resources/admin_css/tabs_simple.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <!--<script src="cms/ckeditor/sample.js" type="text/javascript"></script>-->
    <!--[if lte IE 6]>
    <script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrap_dashboard">
        <?php
include("classes/connection.php");
include("includes/admin-header.php");
include("includes/admin-menu.php");
?>
        <ol id="msg_div" class="msg_con" style="display:none;"></ol>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="edit_diploma_dev.php?id=<?php echo $newsid; ?>&cid=<?php echo $cid; ?>" method="post" enctype="multipart/form-data"  novalidate>
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Update Diploma</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" name="main_form" value="Save" class="add_cancel_button" /></li>
                                <li><a href="diploma.php?cid=<?php echo $cid; ?>">Cancel</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="right_corner">
                    </div>
                    <div id="main_content">
                        <!-- Begin tabs -->
                        <div class="tab">
                            <button class="tablinks active" onclick="return openCity(event, 'course')">Overview</button>
                            <button class="tablinks" onclick="return openCity(event, 'course_detail')">Course Detail</button>
                            <button class="tablinks" onclick="return openCity(event, 'email')">Email</button>
                            <button class="tablinks" onclick="return openCity(event, 'full_payment')">Full Payment</button>
                            <button class="tablinks" onclick="return openCity(event, 'installments')">Installments</button>
                            <button class="tablinks" onclick="return openCity(event, 'seo')">SEO</button>
                            <button class="tablinks" onclick="return openCity(event, 'testimonial')">Testimonial</button>
                            <button class="tablinks" onclick="return openCity(event, 'modules')">Modules</button>
                            <button class="tablinks" onclick="return openCity(event, 'certificates')">Certificates</button>
                            <button class="tablinks" onclick="return openCity(event, 'key_aspects')">Key Aspects</button>
                            <button class="tablinks" onclick="return openCity(event, 'faqs')">FAQs</button>
                        </div>

                        <div id="course" class="tabcontent" style="display:block;">
                            <?php require_once('edit_diploma_inc.php'); ?>
                        </div>

                        <div id="course_detail" class="tabcontent">
                            <?php require_once('add_subject_inc.php'); ?>
                        </div>

                        <div id="email" class="tabcontent">
                            <?php require_once('email_inc.php'); ?>
                        </div>

                        <div id="full_payment" class="tabcontent">
                            <?php require_once('add_payment_inc.php'); ?>
                        </div>

                        <div id="installments" class="tabcontent">
                            <?php require_once('add_installment_inc.php'); ?>
                        </div>

                        <div id="seo" class="tabcontent">
                            <?php require_once('add_couseseo_inc.php'); ?>
                        </div>

                        <div id="testimonial" class="tabcontent">
                            <?php require_once('add_testimonial_inc.php'); ?>
                        </div>

                        <div id="modules" class="tabcontent">
                            <?php require_once('add_module_inc.php'); ?>
                        </div>

                        <div id="certificates" class="tabcontent">
                            <?php require_once('add_certificate_inc.php'); ?>
                        </div>

                        <div id="key_aspects" class="tabcontent">key aspects</div>

                        <div id="faqs" class="tabcontent">faqs</div>
                        <!-- End tabs -->
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>
            </div>
            <div class="clear">&nbsp;</div>
            <?php include("includes/admin-footer.php"); ?>
        </div>
    </div>
    <script>
    function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
    return false;
    }
    // Begin message handling
    let msg = "";
    <?php foreach ($msg as $i) { ?>
    document.getElementById('msg_div').style.display = 'block';
    msg += "<?php echo $i; ?>";
    <?php } ?>
    document.getElementById('msg_div').innerHTML = msg;
    // End message handling
    </script>
</body>
</html>