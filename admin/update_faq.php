<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$newsid = $_GET['id'];
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FAQ</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>
    <!--[if lte IE 6]>
        <script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
        <![endif]-->
    <?php include 'includes/related-diploma-styles.php'; ?>
</head>
<body>
    <div id="wrap_dashboard">
        <?php
include "classes/connection.php";
include "includes/related-diploma-helper.php";
include "includes/admin-header.php";
include "includes/admin-menu.php";
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <?php
if (isset($_POST['update'])) {
    $category_id = $_POST['category_id'];
    $subcat_ids = ($_POST['subcat_ids']) ? implode(',', $_POST['subcat_ids']) : '';
    $cat_ids = ($_POST['cat_ids']) ? implode(',', $_POST['cat_ids']) : '';
    $course_id = $_POST['diploma_id'];
    $NewsDetail = $_POST['NewsDetail'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $show_in_free_courses = ($_POST['show_in_free_courses'] == 1) ? 1 : 0;
    $show_on_home_page  =  ($_POST['show_on_home_page'] == 1) ? 1 : 0;
	$show_in_classifications = ($_POST['show_in_classifications']) ? $_POST['show_in_classifications'] : '';
	$show_in_faq_topics = ($_POST['show_in_faq_topics']) ? implode(', ', $_POST['show_in_faq_topics']) : '';
    $related_diploma_ids = formatRelatedDiplomaIds(isset($_POST['related_diploma_ids']) ? $_POST['related_diploma_ids'] : []);
    $ordernumber  =  $_POST['ordernumber'];
    $NewsUpdate = $db->query("UPDATE tbl_faq SET category_id = '$category_id', subcat_ids = '$subcat_ids', cat_ids = '$cat_ids', course_id = '$course_id', title='$NewsDetail',detail='$description',IStatus='$status', show_in_free_courses = '$show_in_free_courses', show_on_home_page = '$show_on_home_page', show_in_classifications = '$show_in_classifications', show_in_faq_topics = '$show_in_faq_topics', related_diploma_ids = '$related_diploma_ids', ordernumber = $ordernumber where faqid=$newsid") or die("update query error") . mysqli_error($db);
    $msg = "Updated Sucssefully";
    echo "<script language='javascript'>window.location='faq.php?msg=$msg'</script>";
}
?>
                <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>FAQ</h3>
                        </div>
                    </div>
                    <div id="right_corner">
                    </div>
                    <div id="main_content">
                        <div id="addproducts">
                            <div id="uper">
                                <div id="upertop">
                                    <h4>Overview</h4>
                                </div>
                                <div id="uperdetail">
                                    <div id="general">
                                        <?php
$qry = $db->query("select * from tbl_faq where faqid=$newsid");
$newsreslt = $qry->fetch_array();
$show_in_classifications = explode(',', $newsreslt['show_in_classifications']);
$show_in_faq_topics = explode(',', $newsreslt['show_in_faq_topics']);
$subcat_ids = explode(',', $newsreslt['subcat_ids']);
$cat_ids = explode(',', isset($newsreslt['cat_ids']) ? $newsreslt['cat_ids'] : '');
$related_diplomas = buildRelatedDiplomasFromIds($db, isset($newsreslt['related_diploma_ids']) ? $newsreslt['related_diploma_ids'] : '');
?>
                                        <div class="fieldrepeat">
                                            <p>FAQ Category : </p>
                                            <select name="category_id" id="category_id">
                                                <option selected value="0"
                                                    <?php echo ($newsreslt['category_id'] == 0) ? 'selected' : ''; ?>>
                                                    Select Category</option>
                                                <?php
$category_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE IStatus = '1' ORDER BY cname ASC");
while ($category_row = $category_result->fetch_array()) {
    ?>
                                                <option value="<?php echo $category_row['cid']; ?>"
                                                    <?php echo ($category_row['cid'] == $newsreslt['category_id']) ? 'selected' : ''; ?>>
                                                    <?php echo $category_row['cname']; ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Related Course Subcategories : </p>
                                            <input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'flex';" <?php echo ($newsreslt['subcat_ids'] == '') ? '' : 'checked'; ?> /> Show Subcategories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'none';" <?php echo ($newsreslt['subcat_ids'] != '') ? '' : 'checked'; ?> /> Hide Subcategories
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:<?php echo ($newsreslt['subcat_ids'] == '') ? 'none' : 'flex'; ?>;" id="pcatdiv">
                                            <p> &nbsp;</p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $parent_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids <> '' ORDER BY cname ASC");
                                                while ($parent_ids_row = $parent_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="subcat_ids[]" value="<?php echo $parent_ids_row['cid']; ?>" <?php echo (in_array($parent_ids_row['cid'], $subcat_ids)) ? 'checked' : ''; ?> /> <?php echo $parent_ids_row['cname']; ?>
                                                <?php
                                                    if ($i < 3) {
                                                        echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                                    } else {
                                                        echo '<br /><br />';
                                                        $i = 0;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Related Course Categories : </p>
                                            <input type="radio" name="show_cats" onclick="document.getElementById('catdiv').style.display = 'flex';" <?php echo (empty($newsreslt['cat_ids'])) ? '' : 'checked'; ?> /> Show categories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="show_cats" onclick="document.getElementById('catdiv').style.display = 'none';" <?php echo (!empty($newsreslt['cat_ids'])) ? '' : 'checked'; ?> /> Hide categories
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:<?php echo (empty($newsreslt['cat_ids'])) ? 'none' : 'flex'; ?>;" id="catdiv">
                                            <p> &nbsp;</p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $cat_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids = '' ORDER BY cname ASC");
                                                while ($cat_ids_row = $cat_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="cat_ids[]" value="<?php echo $cat_ids_row['cid']; ?>" <?php echo (in_array($cat_ids_row['cid'], $cat_ids)) ? 'checked' : ''; ?> /> <?php echo $cat_ids_row['cname']; ?>
                                                <?php
                                                    if ($i < 3) {
                                                        echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                                    } else {
                                                        echo '<br /><br />';
                                                        $i = 0;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>FAQ Free Course Category:</p>
                                            <select name="diploma_id" id="diploma_id">
                                                <option value="0">Select Free Course</option>
                                                <?php
                                                $diploma_result = $db->query("SELECT diplomaid, name FROM tbl_diploma WHERE type = 3 ORDER BY name ASC");
                                                
                                                while ($diploma_row = $diploma_result->fetch_array()) {
                                                    ?>
                                                    <option value="<?php echo $diploma_row['diplomaid']; ?>"
                                                        <?php echo ($diploma_row['diplomaid'] == $newsreslt['course_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $diploma_row['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php include 'includes/related-diploma-field.php'; ?>
                                        <div class="fieldrepeat">
                                            <p>FAQ Description : </p>
                                            <textarea name="NewsDetail" id="NewsDetail" cols="60"
                                                rows="10"><?php echo $newsreslt['title']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>FAQ Detail : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="description" name="description" class="ckeditor" cols="30"
                                                    rows="15">
                                                    <?php echo $newsreslt['detail']; ?>
                                                    </textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <select name="status" id="status" class="status" style="width:150px;">
                                                <?php
if ($newsreslt['IStatus'] == 1) {
    echo "<option value=" . $newsreslt['IStatus'] . " 'selected'>Enable</option>";
    echo "<option value='0'>Disable</option>";
} else {
    echo "<option value=" . $newsreslt['IStatus'] . " 'selected'>Disable</option>";
    echo "<option value='1'>Enable</option>";
}
?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p style="margin-top:-2px;">Show In Free Courses : </p>
                                            <input type="checkbox" name="show_in_free_courses" value="1"
                                                <?php echo ($newsreslt['show_in_free_courses'] == 1) ? 'checked' : ''; ?> />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p style="margin-top:-2px;">Show on Home Page : </p>
                                            <input type="checkbox" name="show_on_home_page" value="1" <?php echo ($newsreslt['show_on_home_page'] == 1) ? 'checked' : ''; ?> />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:flex;">
                                            <p>Show in FAQ Topics : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $topic_result = $db->query("SELECT id, `name` FROM tbl_faq_topics ORDER BY `name` ASC");
                                                while ($topic_row = $topic_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="show_in_faq_topics[]" value="<?php echo $topic_row['id']; ?>" <?php echo (in_array($topic_row['id'], $show_in_faq_topics)) ? 'checked' : ''; ?> /> <?php echo $topic_row['name']; ?>
                                                <?php
                                                    if ($i < 4) {
                                                        echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                                    } else {
                                                        echo '<br /><br />';
                                                        $i = 0;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:flex;">
                                            <p>Show on Main Courses Page : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <input type="checkbox" name="show_in_classifications" value="1" <?php echo ($newsreslt['show_in_classifications']) ? 'checked' : ''; ?> />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Order Number : </p>
                                            <input name="ordernumber" type="number" value="<?php echo $newsreslt['ordernumber']; ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;">
                                            <input type="submit" name="update" id="update" value="update" />
                                            <input type="button" value="Cancel"
                                                onClick="window.location.href='faq.php'" />
                                        </div>
                                    </div>
                                </div>
                                <div class="clear">&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>
            </div>
            <div class="clear">&nbsp;</div>
            <?php include "includes/admin-footer.php";?>
        </div>
    </div>
    <script>
    CKEDITOR.replace('description', {
        filebrowserBrowseUrl: 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
    </script>
    <?php include 'includes/related-diploma-script.php'; ?>
</body>
</html>
